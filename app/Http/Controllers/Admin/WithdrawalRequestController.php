<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalApprovedMail;
use App\Mail\WithdrawalRejectedMail;

class WithdrawalRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = WithdrawalRequest::with('user');
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        
        $withdrawals = $query->latest()->paginate(15);
        
        $filters = [
            'status' => $request->status ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
            'user_id' => $request->user_id ?? '',
        ];
        
        // Statistics
        $totalPending = WithdrawalRequest::where('status', 'pending')->count();
        $totalProcessing = WithdrawalRequest::where('status', 'processing')->count();
        $totalCompleted = WithdrawalRequest::where('status', 'completed')->count();
        $totalRejected = WithdrawalRequest::where('status', 'rejected')->count();
        
        $totalPendingAmount = WithdrawalRequest::where('status', 'pending')->sum('net_amount');
        $totalProcessingAmount = WithdrawalRequest::where('status', 'processing')->sum('net_amount');
        
        return view('admin.withdrawals.index', compact(
            'withdrawals', 
            'filters',
            'totalPending',
            'totalProcessing',
            'totalCompleted',
            'totalRejected',
            'totalPendingAmount',
            'totalProcessingAmount'
        ));
    }

    public function show($id)
    {
        $withdrawal = WithdrawalRequest::with(['user', 'user.wallet'])->findOrFail($id);
        
        // Get user's withdrawal history
        $userWithdrawals = WithdrawalRequest::where('user_id', $withdrawal->user_id)
            ->where('id', '!=', $id)
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.withdrawals.show', compact('withdrawal', 'userWithdrawals'));
    }

    public function process(Request $request, $id)
    {
        $withdrawal = WithdrawalRequest::with('user')->findOrFail($id);
        
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal request has already been processed.');
        }

        \DB::transaction(function () use ($withdrawal, $request) {
            // Update withdrawal status
            $withdrawal->update([
                'status' => 'completed',
                'processed_at' => now(),
                'admin_note' => $request->admin_note ?? 'Withdrawal processed successfully.',
            ]);

            // Create transaction record
            Transaction::create([
                'user_id' => $withdrawal->user_id,
                'txn_id' => 'TXN' . strtoupper(uniqid()),
                'txn_type' => 'withdraw',
                'amount' => -$withdrawal->amount, // Negative amount for withdrawal
                'status' => 'completed',
                'details' => 'Withdrawal processed - Net: ' . $withdrawal->net_amount . ' USDT',
            ]);

            // Update user's wallet
            $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();
            if ($wallet) {
                $wallet->decrement('earning_balance', $withdrawal->amount);
                $wallet->increment('total_withdrawn', $withdrawal->amount);
            }

            // Create notification for user
            Notification::create([
                'user_id' => $withdrawal->user_id,
                'title' => 'Withdrawal Approved',
                'message' => 'Your withdrawal request of ' . $withdrawal->net_amount . ' USDT has been approved and processed.',
                'type' => 'success'
            ]);

            // Send approval email (queued)
            try {
                Mail::to($withdrawal->user->email)
                    ->queue(new WithdrawalApprovedMail($withdrawal));
            } catch (\Exception $e) {
                \Log::error('Failed to send withdrawal approval email: ' . $e->getMessage());
            }
        });

        return back()->with('success', 'Withdrawal processed successfully and user notified.');
    }

    public function reject(Request $request, $id)
    {
        $withdrawal = WithdrawalRequest::with('user')->findOrFail($id);
        
        $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal request has already been processed.');
        }

        \DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_note' => $request->admin_note,
                'processed_at' => now(),
            ]);

            // Return funds to user's wallet
            $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();
            if ($wallet) {
                $wallet->increment('earning_balance', $withdrawal->amount);
            }

            // Create notification for user
            Notification::create([
                'user_id' => $withdrawal->user_id,
                'title' => 'Withdrawal Rejected',
                'message' => 'Your withdrawal request has been rejected. Reason: ' . $request->admin_note,
                'type' => 'error'
            ]);

            // Send rejection email (queued)
            try {
                Mail::to($withdrawal->user->email)
                    ->queue(new WithdrawalRejectedMail($withdrawal, $request->admin_note));
            } catch (\Exception $e) {
                \Log::error('Failed to send withdrawal rejection email: ' . $e->getMessage());
            }
        });

        return back()->with('success', 'Withdrawal rejected successfully and user notified.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'withdrawals' => 'required|array',
            'withdrawals.*' => 'exists:withdrawal_requests,id',
            'admin_note' => 'required_if:action,reject|string|max:500',
        ]);

        $withdrawals = WithdrawalRequest::with('user')
            ->whereIn('id', $request->withdrawals)
            ->where('status', 'pending')
            ->get();

        if ($withdrawals->isEmpty()) {
            return back()->with('error', 'No valid pending withdrawals selected.');
        }

        $processed = 0;
        $failed = 0;

        foreach ($withdrawals as $withdrawal) {
            try {
                if ($request->action === 'approve') {
                    $this->processBulkWithdrawal($withdrawal);
                } else {
                    $this->rejectBulkWithdrawal($withdrawal, $request->admin_note);
                }
                $processed++;
            } catch (\Exception $e) {
                \Log::error('Bulk withdrawal processing failed for ID ' . $withdrawal->id . ': ' . $e->getMessage());
                $failed++;
            }
        }

        $message = "Processed: {$processed}, Failed: {$failed}";
        $type = $failed > 0 ? 'warning' : 'success';

        return back()->with($type, $message);
    }

    private function processBulkWithdrawal($withdrawal)
    {
        \DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'status' => 'completed',
                'processed_at' => now(),
                'admin_note' => 'Bulk processed - ' . now()->format('Y-m-d H:i'),
            ]);

            // Create transaction record
            Transaction::create([
                'user_id' => $withdrawal->user_id,
                'txn_id' => 'TXN' . strtoupper(uniqid()),
                'txn_type' => 'withdraw',
                'amount' => -$withdrawal->amount,
                'status' => 'completed',
                'details' => 'Withdrawal processed - Net: ' . $withdrawal->net_amount . ' USDT',
            ]);

            // Update wallet
            $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();
            if ($wallet) {
                $wallet->decrement('earning_balance', $withdrawal->amount);
                $wallet->increment('total_withdrawn', $withdrawal->amount);
            }

            // Send email
            Mail::to($withdrawal->user->email)
                ->queue(new WithdrawalApprovedMail($withdrawal));
        });
    }

    private function rejectBulkWithdrawal($withdrawal, $adminNote)
    {
        \DB::transaction(function () use ($withdrawal, $adminNote) {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_note' => $adminNote,
                'processed_at' => now(),
            ]);

            // Return funds
            $wallet = Wallet::where('user_id', $withdrawal->user_id)->first();
            if ($wallet) {
                $wallet->increment('earning_balance', $withdrawal->amount);
            }

            // Send email
            Mail::to($withdrawal->user->email)
                ->queue(new WithdrawalRejectedMail($withdrawal, $adminNote));
        });
    }

    public function getStats()
    {
        $today = now()->format('Y-m-d');
        $weekStart = now()->startOfWeek()->format('Y-m-d');
        $monthStart = now()->startOfMonth()->format('Y-m-d');

        return [
            'today' => [
                'count' => WithdrawalRequest::whereDate('created_at', $today)->count(),
                'amount' => WithdrawalRequest::whereDate('created_at', $today)->sum('net_amount'),
            ],
            'this_week' => [
                'count' => WithdrawalRequest::whereDate('created_at', '>=', $weekStart)->count(),
                'amount' => WithdrawalRequest::whereDate('created_at', '>=', $weekStart)->sum('net_amount'),
            ],
            'this_month' => [
                'count' => WithdrawalRequest::whereDate('created_at', '>=', $monthStart)->count(),
                'amount' => WithdrawalRequest::whereDate('created_at', '>=', $monthStart)->sum('net_amount'),
            ],
        ];
    }
}
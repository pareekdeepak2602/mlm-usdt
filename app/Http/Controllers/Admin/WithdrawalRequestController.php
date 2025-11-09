<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\WithdrawalProcessingService;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    private $withdrawalService;

    public function __construct(WithdrawalProcessingService $withdrawalService)
    {
        $this->withdrawalService = $withdrawalService;
    }

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
        $totalFailed = WithdrawalRequest::where('status', 'failed')->count();
        
        $totalPendingAmount = WithdrawalRequest::where('status', 'pending')->sum('net_amount');
        $totalProcessingAmount = WithdrawalRequest::where('status', 'processing')->sum('net_amount');
        
        // Check blockchain status
        $blockchainAvailable = $this->withdrawalService->isBlockchainAvailable();

        return view('admin.withdrawals.index', compact(
            'withdrawals', 
            'filters',
            'totalPending',
            'totalProcessing',
            'totalCompleted',
            'totalRejected',
            'totalFailed',
            'totalPendingAmount',
            'totalProcessingAmount',
            'blockchainAvailable'
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

        // Check blockchain service availability
        if (!$this->withdrawalService->isBlockchainAvailable()) {
            return back()->with('error', 'Blockchain service is currently unavailable. Please try again later.');
        }

        try {
            $result = $this->withdrawalService->processWithdrawal(
                $withdrawal, 
                $request->admin_note ?? 'Withdrawal processed successfully.'
            );

            $message = 'Withdrawal processed successfully and user notified.';
            if ($result['txHash']) {
                $message .= ' Transaction Hash: ' . $result['txHash'];
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Withdrawal processing failed: ' . $e->getMessage());
        }
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

        try {
            $this->withdrawalService->rejectWithdrawal($withdrawal, $request->admin_note);
            return back()->with('success', 'Withdrawal rejected successfully and user notified.');
        } catch (\Exception $e) {
            return back()->with('error', 'Withdrawal rejection failed: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'withdrawals' => 'required|array',
            'withdrawals.*' => 'exists:withdrawal_requests,id',
            'admin_note' => 'required_if:action,reject|string|max:500',
        ]);

        // Check blockchain service availability for approve action
        if ($request->action === 'approve' && !$this->withdrawalService->isBlockchainAvailable()) {
            return back()->with('error', 'Blockchain service is currently unavailable. Cannot process withdrawals.');
        }

        if ($request->action === 'approve') {
            $results = $this->withdrawalService->processBulkWithdrawals(
                $request->withdrawals,
                $request->admin_note ?? 'Bulk processed'
            );
        } else {
            $results = $this->withdrawalService->rejectBulkWithdrawals(
                $request->withdrawals,
                $request->admin_note
            );
        }

        // Build response message
        $message = "Processed: {$results['processed']}";
        if ($results['failed'] > 0) {
            $message .= ", Failed: {$results['failed']}";
        }

        if ($request->action === 'approve' && !empty($results['successful'])) {
            $message .= ". Successful transactions: " . count($results['successful']);
        }

        $type = $results['failed'] > 0 ? 'warning' : 'success';

        return back()->with($type, $message);
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

    /**
     * Check blockchain service status
     */
    public function checkBlockchainStatus()
    {
        try {
            $status = $this->withdrawalService->getBlockchainStatus();
            $available = $this->withdrawalService->isBlockchainAvailable();

            return response()->json([
                'available' => $available,
                'status' => $status,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\UserInvestment;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalInvestments = UserInvestment::count();
        $activeInvestments = UserInvestment::where('status', 'active')->count();
        
        $totalDeposits = Transaction::where('txn_type', 'deposit')
                                  ->where('status', 'completed')
                                  ->sum('amount');
                                  
        $totalWithdrawals = Transaction::where('txn_type', 'withdraw')
                                     ->where('status', 'completed')
                                     ->sum('amount');
        
        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();
        
        // Recent growth data (last 7 days)
        $userGrowth = User::where('created_at', '>=', Carbon::now()->subDays(7))
                         ->get()
                         ->groupBy(function($date) {
                             return Carbon::parse($date->created_at)->format('Y-m-d');
                         })
                         ->map->count();
        
        $depositGrowth = Transaction::where('txn_type', 'deposit')
                                  ->where('status', 'completed')
                                  ->where('created_at', '>=', Carbon::now()->subDays(7))
                                  ->get()
                                  ->groupBy(function($date) {
                                      return Carbon::parse($date->created_at)->format('Y-m-d');
                                  })
                                  ->map->sum('amount');

        return view('admin.reports.index', compact(
            'totalUsers', 'activeUsers', 'totalInvestments', 'activeInvestments',
            'totalDeposits', 'totalWithdrawals', 'pendingWithdrawals',
            'userGrowth', 'depositGrowth'
        ));
    }

    public function usersReport(Request $request)
    {
        $query = User::with(['wallet', 'investments']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $users = $query->latest()->paginate(20);
        
        $filters = [
            'status' => $request->status ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
        ];

        return view('admin.reports.users', compact('users', 'filters'));
    }

    public function transactionsReport(Request $request)
    {
        $query = Transaction::with('user');
        
        if ($request->type) {
            $query->where('txn_type', $request->type);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $transactions = $query->latest()->paginate(30);
        
        $summary = [
            'total_amount' => $query->sum('amount'),
            'completed_count' => $query->where('status', 'completed')->count(),
            'pending_count' => $query->where('status', 'pending')->count(),
        ];
        
        $filters = [
            'type' => $request->type ?? '',
            'status' => $request->status ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
        ];

        return view('admin.reports.transactions', compact('transactions', 'summary', 'filters'));
    }

    public function financialReport(Request $request)
    {
        $dateFrom = $request->date_from ?: Carbon::now()->subMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?: Carbon::now()->format('Y-m-d');
        
        // Deposit Summary
        $deposits = Transaction::where('txn_type', 'deposit')
                             ->where('status', 'completed')
                             ->whereBetween('created_at', [$dateFrom, $dateTo])
                             ->sum('amount');
        
        // Withdrawal Summary
        $withdrawals = Transaction::where('txn_type', 'withdraw')
                                ->where('status', 'completed')
                                ->whereBetween('created_at', [$dateFrom, $dateTo])
                                ->sum('amount');
        
        // Income Distribution
        $incomeByType = Transaction::where('status', 'completed')
                                 ->whereBetween('created_at', [$dateFrom, $dateTo])
                                 ->whereIn('txn_type', ['income', 'referral', 'level_bonus', 'leadership_bonus'])
                                 ->selectRaw('txn_type, SUM(amount) as total')
                                 ->groupBy('txn_type')
                                 ->get()
                                 ->keyBy('txn_type');
        
        // Daily trends
        $dailyDeposits = Transaction::where('txn_type', 'deposit')
                                  ->where('status', 'completed')
                                  ->whereBetween('created_at', [$dateFrom, $dateTo])
                                  ->get()
                                  ->groupBy(function($date) {
                                      return Carbon::parse($date->created_at)->format('Y-m-d');
                                  })
                                  ->map->sum('amount');
        
        $dailyWithdrawals = Transaction::where('txn_type', 'withdraw')
                                     ->where('status', 'completed')
                                     ->whereBetween('created_at', [$dateFrom, $dateTo])
                                     ->get()
                                     ->groupBy(function($date) {
                                         return Carbon::parse($date->created_at)->format('Y-m-d');
                                     })
                                     ->map->sum('amount');

        return view('admin.reports.financial', compact(
            'deposits', 'withdrawals', 'incomeByType', 'dailyDeposits', 'dailyWithdrawals',
            'dateFrom', 'dateTo'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        // Base Query
        $baseQuery = \App\Models\LoanRequest::query();
        if (!$isAdmin) {
            $baseQuery->where('user_id', $user->id);
        }

        // Stats
        $stats = [
            'total_loans' => (clone $baseQuery)->count(),
            'pending_loans' => (clone $baseQuery)->where('status', 'Submitted for verification')->count(),
            'approved_loans' => (clone $baseQuery)->where('status', 'Approved')->count(),
            'total_amount' => (clone $baseQuery)->sum('loan_amount'),
        ];

        // Latest Loans (for table)
        $latestLoans = (clone $baseQuery)->with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        // Data for Graphs
        // 1. Loans by Type
        $loanTypes = (clone $baseQuery)->select('loan_type', \DB::raw('count(*) as count'))
            ->groupBy('loan_type')
            ->get();

        // 2. Applications Trend (Last 9 Months)
        $monthlyTrend = (clone $baseQuery)->select(
            \DB::raw('DATE_FORMAT(created_at, "%m") as month'),
            \DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(9))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $trendData = [];
        for ($i = 8; $i >= 0; $i--) {
            $m = now()->subMonths($i)->format('m');
            $trendData[] = $monthlyTrend[$m] ?? 0;
        }

        // 3. Status Distribution
        $statusCounts = (clone $baseQuery)->select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // 4. Recent Activities
        $recentActivities = (clone $baseQuery)->with('user')
            ->orderBy('updated_at', 'DESC')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestLoans', 'loanTypes', 'trendData', 'statusCounts', 'recentActivities'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_loans' => \App\Models\LoanRequest::count(),
            'pending_loans' => \App\Models\LoanRequest::where('status', 'Submitted for verification')->count(),
            'approved_loans' => \App\Models\LoanRequest::where('status', 'Approved')->count(),
            'total_amount' => \App\Models\LoanRequest::sum('loan_amount'),
        ];

        $latestLoans = \App\Models\LoanRequest::with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestLoans'));
    }
}

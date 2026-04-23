<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\Guarantor;
use App\Models\LoanIncome;
use App\Models\LoanCommitment;
use App\Models\LoanExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoanRequestController extends Controller
{
    public function index()
    {
        $loans = LoanRequest::with(['guarantors', 'incomes', 'commitments', 'expenses'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('loans.index', compact('loans'));
    }

    public function show(LoanRequest $loan)
    {
        // Ensure user can only view their own loans
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        $loan->load(['guarantors', 'incomes', 'commitments', 'expenses']);
        return view('loans.show', compact('loan'));
    }

    public function create()
    {
        return view('loans.request');
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'loan_type' => 'required',
            'loan_amount' => 'required|numeric',
            'loan_tenure' => 'required',
            'employment_status' => 'required',
            'basic_salary' => 'required|numeric',
            'gross_salary' => 'required|numeric|gte:basic_salary',
            'nic_copy' => 'required|file',
            'salary_slips.*' => 'file',
            'bank_statement' => 'required|file',
            'employment_letter' => 'required|file',
        ]);

        $data = $request->only([
            'loan_type', 'loan_amount', 'loan_tenure', 'employment_status', 
            'basic_salary', 'gross_salary', 'active_loans_count'
        ]);
        
        $data['user_id'] = Auth::id();

        // File uploads
        if ($request->hasFile('nic_copy')) {
            $data['nic_copy'] = $request->file('nic_copy')->store('loans/nic', 'public');
        }
        if ($request->hasFile('bank_statement')) {
            $data['bank_statement'] = $request->file('bank_statement')->store('loans/bank', 'public');
        }
        if ($request->hasFile('employment_letter')) {
            $data['employment_letter'] = $request->file('employment_letter')->store('loans/employment', 'public');
        }

        if ($request->hasFile('salary_slips')) {
            $slips = [];
            foreach ($request->file('salary_slips') as $file) {
                $slips[] = $file->store('loans/salaries', 'public');
            }
            $data['salary_slips'] = $slips;
        }

        $loan = LoanRequest::create($data);

        // Save Other Incomes
        if ($request->other_incomes) {
            foreach ($request->other_incomes as $income) {
                if (!empty($income['name']) && !empty($income['amount'])) {
                    $loan->incomes()->create([
                        'name' => $income['name'],
                        'amount' => $income['amount']
                    ]);
                }
            }
        }

        // Save Commitments
        if ($request->financial_commitments) {
            foreach ($request->financial_commitments as $commitment) {
                if (!empty($commitment['name']) && !empty($commitment['amount'])) {
                    $loan->commitments()->create([
                        'name' => $commitment['name'],
                        'amount' => $commitment['amount']
                    ]);
                }
            }
        }

        // Save Expenses
        if ($request->personal_expenses) {
            foreach ($request->personal_expenses as $expense) {
                if (!empty($expense['name']) && !empty($expense['amount'])) {
                    $loan->expenses()->create([
                        'name' => $expense['name'],
                        'amount' => $expense['amount']
                    ]);
                }
            }
        }

        // Guarantors
        if ($request->guarantors) {
            foreach ($request->guarantors as $index => $gData) {
                $guarantor = new Guarantor([
                    'name' => $gData['name'],
                    'nic' => $gData['nic'],
                    'address' => $gData['address'],
                    'age' => $gData['age'],
                    'job_title' => $gData['job_title'],
                ]);

                if ($request->hasFile("guarantors.$index.nic_copy")) {
                    $guarantor->nic_copy = $request->file("guarantors.$index.nic_copy")->store('guarantors/nic', 'public');
                }

                if ($request->hasFile("guarantors.$index.salary_slips")) {
                    $gSlips = [];
                    foreach ($request->file("guarantors.$index.salary_slips") as $file) {
                        $gSlips[] = $file->store('guarantors/salaries', 'public');
                    }
                    $guarantor->salary_slips = $gSlips;
                }

                $loan->guarantors()->save($guarantor);
            }
        }

        return redirect()->route('loans.index')->with('status', 'Loan request submitted for verification!');
    }
}

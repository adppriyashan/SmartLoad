<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\Guarantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoanRequestController extends Controller
{
    public function index()
    {
        $loans = LoanRequest::with('guarantors')->where('user_id', Auth::id())->latest()->get();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        return view('loans.request');
    }

    public function store(Request $request)
    {
        // Validation (simplified for now to handle complex data)
        $request->validate([
            'loan_type' => 'required',
            'loan_amount' => 'required|numeric',
            'loan_tenure' => 'required',
            'employment_status' => 'required',
            'basic_salary' => 'required|numeric',
            'gross_salary' => 'required|numeric',
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
        $data['other_incomes'] = $request->other_incomes; // JSON from JS
        $data['financial_commitments'] = $request->financial_commitments; // JSON from JS
        $data['personal_expenses'] = $request->personal_expenses; // JSON from JS

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

                // Handle guarantor files
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

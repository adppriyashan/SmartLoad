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
    public function index(Request $request)
    {
        $isAdmin = Auth::user()->role === 'admin';

        $query = LoanRequest::with(['user', 'guarantors', 'incomes', 'commitments', 'expenses']);

        if (!$isAdmin) {
            $query->where('user_id', Auth::id());
        }

        // Search by NIC (Join with users table)
        if ($request->filled('nic')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nic', 'like', '%' . $request->nic . '%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sorting: 'Submitted for verification' status first, then latest
        $loans = $query->orderByRaw("CASE WHEN status = 'Submitted for verification' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('loans.index', compact('loans', 'isAdmin'));
    }

    public function show(LoanRequest $loan)
    {
        // Ensure user can only view their own loans, unless they are admin
        if (Auth::user()->role !== 'admin' && $loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Automatically change status to 'In Progress' if admin views a newly submitted loan
        if (Auth::user()->role === 'admin' && $loan->status === 'Submitted for verification') {
            $loan->update(['status' => 'In Progress']);
        }

        $loan->load(['user', 'guarantors', 'incomes', 'commitments', 'expenses']);
        return view('loans.show', compact('loan'));
    }

    public function updateStatus(Request $request, LoanRequest $loan)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Verified,Rejected,In Progress'
        ]);

        $loan->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Loan status updated successfully to ' . $request->status);
    }

    public function create()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('loans.index')->with('error', 'Administrators cannot apply for loans.');
        }
        return view('loans.request');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('loans.index')->with('error', 'Administrators cannot apply for loans.');
        }

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
            'bank_statements.*' => 'file',
            'employment_letter' => 'required|file',
        ]);

        $data = $request->only([
            'loan_type',
            'loan_amount',
            'loan_tenure',
            'employment_status',
            'basic_salary',
            'gross_salary',
            'active_loans_count'
        ]);

        $data['user_id'] = Auth::id();

        // File uploads
        if ($request->hasFile('nic_copy')) {
            $data['nic_copy'] = $request->file('nic_copy')->store('loans/nic', 'public');
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

        if ($request->hasFile('bank_statements')) {
            $statements = [];
            foreach ($request->file('bank_statements') as $file) {
                $statements[] = $file->store('loans/bank', 'public');
            }
            $data['bank_statements'] = $statements;
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

    public function updateFinancials(Request $request, LoanRequest $loan)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'purposed_loan_rental' => 'nullable|numeric',
            'past_default_loan' => 'nullable|string',
            'financial_commitments.*.name' => 'required|string',
            'financial_commitments.*.amount' => 'required|numeric',
            'personal_expenses.*.name' => 'required|string',
            'personal_expenses.*.amount' => 'required|numeric',
        ]);

        // Update basic fields
        $loan->update([
            'purposed_loan_rental' => $request->purposed_loan_rental,
            'past_default_loan' => $request->past_default_loan,
        ]);

        // Smart Update Commitments
        $existingCommitments = $loan->commitments;
        $keptCommitmentIds = [];

        if ($request->financial_commitments) {
            foreach ($request->financial_commitments as $item) {
                if (!empty($item['name']) && !empty($item['amount'])) {
                    // Check if an identical record already exists
                    $match = $existingCommitments->where('name', $item['name'])
                        ->where('amount', $item['amount'])
                        ->first();

                    if ($match) {
                        // Keep the original record
                        $keptCommitmentIds[] = $match->id;
                    } else {
                        // Create new flagged record
                        $newRecord = $loan->commitments()->create([
                            'name' => $item['name'],
                            'amount' => $item['amount'],
                            'added_by_admin' => true
                        ]);
                        $keptCommitmentIds[] = $newRecord->id;
                    }
                }
            }
        }
        // Remove any records that are no longer in the list
        $loan->commitments()->whereNotIn('id', $keptCommitmentIds)->delete();

        // Smart Update Expenses
        $existingExpenses = $loan->expenses;
        $keptExpenseIds = [];

        if ($request->personal_expenses) {
            foreach ($request->personal_expenses as $item) {
                if (!empty($item['name']) && !empty($item['amount'])) {
                    // Check if an identical record already exists
                    $match = $existingExpenses->where('name', $item['name'])
                        ->where('amount', $item['amount'])
                        ->first();

                    if ($match) {
                        // Keep the original record
                        $keptExpenseIds[] = $match->id;
                    } else {
                        // Create new flagged record
                        $newRecord = $loan->expenses()->create([
                            'name' => $item['name'],
                            'amount' => $item['amount'],
                            'added_by_admin' => true
                        ]);
                        $keptExpenseIds[] = $newRecord->id;
                    }
                }
            }
        }
        // Remove any records that are no longer in the list
        $loan->expenses()->whereNotIn('id', $keptExpenseIds)->delete();

        return redirect()->back()->with('success', 'Loan financials updated. Only new or modified entries are flagged as Admin Edits.');
    }

    public function getLatestInProgress()
    {
        $loan = LoanRequest::where('user_id', Auth::id())
            ->where('status', 'In Progress')
            ->latest()
            ->with(['incomes', 'commitments', 'expenses'])
            ->first();

        if (!$loan) {
            return response()->json(['message' => 'No in-progress loan found'], 404);
        }

        $totalIncome = $loan->gross_salary + $loan->incomes->sum('amount');
        $totalCommitments = $loan->commitments->sum('amount');
        $totalExpenses = $loan->expenses->sum('amount');

        $loan['financials'] = [
            'basic_salary' => (float) $loan->basic_salary,
            'total_income' => (float) $totalIncome,
            'total_commitments' => (float) $totalCommitments,
            'total_living_expenses' => (float) $totalExpenses,
            'gross_salary' => (float) $loan->gross_salary,
            'other_incomes_total' => (float) $loan->incomes->sum('amount'),
        ];

        return response()->json($loan);
    }

    public function apiUpdateDecision(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loan_requests,id',
            'status' => 'required|string', // e.g., Approved, Rejected
            'possible_loan_amount' => 'nullable|numeric'
        ]);

        $loan = LoanRequest::find($request->loan_id);
        
        $updateData = [
            'status' => $request->status,
            'possible_loan_amount' => $request->possible_loan_amount
        ];

        $loan->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Loan decision updated successfully',
            'loan_id' => $loan->id,
            'new_status' => $loan->status,
            'possible_loan_amount' => $loan->possible_loan_amount
        ]);
    }
}

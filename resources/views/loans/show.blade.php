@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Loan Overview Card -->
                <div class="card mb-4 shadow-sm border-radius-xl">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0">Loan Request #{{ $loan->id }} - <span
                                        class="text-primary">{{ $loan->loan_type }}</span></h6>
                            </div>
                            <div class="col-md-4 text-end">
                                @if ($loan->status == 'Verified')
                                    <span class="badge badge-sm bg-gradient-info">Verified</span>
                                @elseif($loan->status == 'Approved')
                                    <span class="badge badge-sm bg-gradient-success">Approved</span>
                                @elseif($loan->status == 'Rejected')
                                    <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                @elseif($loan->status == 'In Progress')
                                    <span class="badge badge-sm bg-gradient-warning">In Progress</span>
                                @else
                                    <span class="badge badge-sm bg-gradient-info">Under Verification</span>
                                @endif
                            </div>
                        </div>

                        @if (Auth::user()->role === 'admin')
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div
                                        class="bg-gray-100 p-3 border-radius-lg d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="d-flex align-items-center mb-2 mb-md-0">
                                            <span class="text-sm font-weight-bold me-3">Change Status:</span>
                                            <form action="{{ route('loans.updateStatus', $loan->id) }}" method="POST"
                                                class="d-flex align-items-center mb-0">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm me-2"
                                                    style="width: 150px;">
                                                    <option value="In Progress"
                                                        {{ $loan->status == 'In Progress' ? 'selected' : '' }}>In Progress
                                                    </option>
                                                    <option value="Verified"
                                                        {{ $loan->status == 'Verified' ? 'selected' : '' }}>Verified
                                                    </option>
                                                    <option value="Approved"
                                                        {{ $loan->status == 'Approved' ? 'selected' : '' }}>Approved
                                                    </option>
                                                    <option value="Rejected"
                                                        {{ $loan->status == 'Rejected' ? 'selected' : '' }}>Rejected
                                                    </option>
                                                </select>
                                                <button type="submit"
                                                    class="btn btn-sm bg-gradient-dark mb-0">Update</button>
                                            </form>
                                        </div>

                                        @if ($loan->possible_loan_amount && !$loan->is_decision_published)
                                            <div class="ms-md-auto">
                                                <form action="{{ route('loans.publish', $loan->id) }}" method="POST"
                                                    class="mb-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm bg-gradient-success mb-0">
                                                        <i class="fas fa-paper-plane me-1"></i> Publish Decision to User
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($loan->is_decision_published)
                                            <div class="ms-md-auto">
                                                <span class="badge badge-sm bg-gradient-secondary">
                                                    <i class="fas fa-check-circle me-1"></i> Decision Published
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 text-end">
                                    <button class="btn btn-link text-primary text-xs mb-0" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#editFinancialsForm">
                                        <i class="fas fa-edit me-1"></i> Edit Financials & New Fields
                                    </button>
                                </div>
                            </div>
                            <div class="collapse" id="editFinancialsForm">
                                <div class="bg-gray-100 p-3 border-radius-lg mt-2">
                                    <form action="{{ route('loans.updateFinancials', $loan->id) }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-control-label">Purposed Loan Rental</label>
                                                <input type="number" name="purposed_loan_rental"
                                                    class="form-control form-control-sm"
                                                    value="{{ $loan->purposed_loan_rental }}" step="0.01">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-control-label">Past Default Loan</label>
                                                <input type="text" name="past_default_loan"
                                                    class="form-control form-control-sm"
                                                    value="{{ $loan->past_default_loan }}" placeholder="None">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-xs font-weight-bold">Financial Commitments</h6>
                                                <div id="admin-commitments-container">
                                                    @foreach ($loan->commitments as $index => $commitment)
                                                        <div class="row mb-2">
                                                            <div class="col-7 pe-1">
                                                                <input type="text"
                                                                    name="financial_commitments[{{ $index }}][name]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $commitment->name }}">
                                                            </div>
                                                            <div class="col-5 ps-1">
                                                                <input type="number"
                                                                    name="financial_commitments[{{ $index }}][amount]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $commitment->amount }}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-xs btn-outline-primary mt-2"
                                                    onclick="addAdminRow('commitments')">+ Add</button>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-xs font-weight-bold">Living Expenses</h6>
                                                <div id="admin-expenses-container">
                                                    @foreach ($loan->expenses as $index => $expense)
                                                        <div class="row mb-2">
                                                            <div class="col-7 pe-1">
                                                                <input type="text"
                                                                    name="personal_expenses[{{ $index }}][name]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $expense->name }}">
                                                            </div>
                                                            <div class="col-5 ps-1">
                                                                <input type="number"
                                                                    name="personal_expenses[{{ $index }}][amount]"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $expense->amount }}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-xs btn-outline-primary mt-2"
                                                    onclick="addAdminRow('expenses')">+ Add</button>
                                            </div>
                                        </div>
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-sm bg-gradient-info mb-0">Save
                                                Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-body p-3">
                        @if (session('success'))
                            <div class="alert alert-success text-white alert-dismissible fade show" role="alert">
                                <span class="text-sm">{{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-3 text-center border-end">
                                <h2 class="font-weight-bolder text-dark mb-0">LKR
                                    {{ number_format($loan->loan_amount, 2) }}</h2>
                                <p class="text-xs text-secondary text-uppercase font-weight-bold">Amount Requested</p>
                            </div>
                            @if ($loan->possible_loan_amount && (Auth::user()->role === 'admin' || $loan->is_decision_published))
                                <div class="col-md-3 text-center border-end">
                                    <h2
                                        class="font-weight-bolder text-{{ $loan->possible_status == 'Rejected' ? 'danger' : 'success' }} mb-0">
                                        LKR
                                        {{ number_format($loan->possible_loan_amount, 2) }}</h2>
                                    <p
                                        class="text-xs text-{{ $loan->possible_status == 'Rejected' ? 'danger' : 'success' }} text-uppercase font-weight-bold">
                                        {{ $loan->possible_status }} Amount</p>
                                </div>
                            @endif
                            <div
                                class="col-md-{{ $loan->possible_loan_amount ? '3' : '4' }} text-center {{ $loan->possible_loan_amount ? 'border-end' : '' }}">
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $loan->loan_tenure }}</h4>
                                <p class="text-xs text-secondary text-uppercase font-weight-bold">Tenure</p>
                            </div>
                            <div class="col-md-{{ $loan->possible_loan_amount ? '3' : '4' }} text-center">
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $loan->created_at->format('M d, Y') }}
                                </h4>
                                <p class="text-xs text-secondary text-uppercase font-weight-bold">Applied Date</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Details Card -->
                <div class="card mb-4 shadow-sm border-radius-xl">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Financial Background</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Employment
                                            Status:</strong> &nbsp; {{ $loan->employment_status }}</li>
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Basic
                                            Salary:</strong> &nbsp; LKR {{ number_format($loan->basic_salary, 2) }}</li>
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Gross
                                            Salary:</strong> &nbsp; LKR {{ number_format($loan->gross_salary, 2) }}</li>
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Purposed
                                            Rental:</strong> &nbsp; LKR
                                        {{ number_format($loan->purposed_loan_rental ?? 0, 2) }}</li>
                                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Past
                                            Default:</strong> &nbsp; {{ $loan->past_default_loan ?? 'None' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-light border-0 shadow-none p-3 border-radius-lg">
                                    <h6 class="text-dark mb-2">Income Summary</h6>
                                    <div class="d-flex justify-content-between text-xs mb-1">
                                        <span>Gross Salary:</span>
                                        <span class="font-weight-bold">LKR
                                            {{ number_format($loan->gross_salary, 2) }}</span>
                                    </div>
                                    @foreach ($loan->incomes as $income)
                                        <div class="d-flex justify-content-between text-xs mb-1">
                                            <span>{{ $income->name }}:</span>
                                            <span class="font-weight-bold">LKR
                                                {{ number_format($income->amount, 2) }}</span>
                                        </div>
                                    @endforeach
                                    <hr class="horizontal dark my-2">
                                    <div class="d-flex justify-content-between text-sm">
                                        <span class="font-weight-bold">Total Monthly Income:</span>
                                        <span class="font-weight-bold text-dark">LKR
                                            {{ number_format($loan->gross_salary + $loan->incomes->sum('amount'), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-xs text-uppercase font-weight-bold text-secondary mb-3">Commitments</h6>
                                @forelse($loan->commitments as $commitment)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-minus-circle text-danger me-2"></i>
                                            <span class="text-sm">{{ $commitment->name }}: LKR
                                                {{ number_format($commitment->amount, 2) }}</span>
                                        </div>
                                        @if ($commitment->added_by_admin)
                                            <span class="badge badge-xxs bg-gradient-secondary ms-2"
                                                style="font-size: 0.6rem;">Admin Edit</span>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-secondary">No active commitments listed.</p>
                                @endforelse
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-xs text-uppercase font-weight-bold text-secondary mb-3">Living Expenses
                                </h6>
                                @forelse($loan->expenses as $expense)
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-shopping-cart text-warning me-2"></i>
                                            <span class="text-sm">{{ $expense->name }}: LKR
                                                {{ number_format($expense->amount, 2) }}</span>
                                        </div>
                                        @if ($expense->added_by_admin)
                                            <span class="badge badge-xxs bg-gradient-secondary ms-2"
                                                style="font-size: 0.6rem;">Admin Edit</span>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-secondary">No specific expenses listed.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guarantors Card -->
                <div class="card mb-4 shadow-sm border-radius-xl">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Guarantors</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            @foreach ($loan->guarantors as $index => $guarantor)
                                <div class="col-md-6">
                                    <div class="card bg-gray-100 shadow-none border-radius-lg p-3 mb-3">
                                        <h6 class="text-sm mb-2">Guarantor {{ $index + 1 }}</h6>
                                        <p class="text-xs mb-1"><strong>Name:</strong> {{ $guarantor->name }}</p>
                                        <p class="text-xs mb-1"><strong>NIC:</strong> {{ $guarantor->nic }}</p>
                                        <p class="text-xs mb-1"><strong>Job:</strong> {{ $guarantor->job_title }}</p>
                                        <p class="text-xs mb-0"><strong>Address:</strong> {{ $guarantor->address }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Documents Card -->
                <div class="card mb-4 shadow-sm border-radius-xl">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Uploaded Documents</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                <div class="avatar avatar-sm me-3 bg-gradient-dark shadow-dark border-radius-md">
                                    <i class="fas fa-id-card text-white"></i>
                                </div>
                                <div class="d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">NIC / Passport</h6>
                                    <a href="{{ asset('storage/' . $loan->nic_copy) }}" target="_blank"
                                        class="text-xs text-primary font-weight-bold">View Document</a>
                                </div>
                            </li>
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                <div class="avatar avatar-sm me-3 bg-gradient-dark shadow-dark border-radius-md">
                                    <i class="fas fa-briefcase text-white"></i>
                                </div>
                                <div class="d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">Employment Letter</h6>
                                    <a href="{{ asset('storage/' . $loan->employment_letter) }}" target="_blank"
                                        class="text-xs text-primary font-weight-bold">View Document</a>
                                </div>
                            </li>
                        </ul>

                        @if ($loan->bank_statements)
                            <hr class="horizontal dark my-3">
                            <h6 class="text-xs font-weight-bold text-uppercase text-secondary mb-3">Bank Statements</h6>
                            <div class="d-flex flex-wrap">
                                @foreach ($loan->bank_statements as $index => $statement)
                                    <a href="{{ asset('storage/' . $statement) }}" target="_blank"
                                        class="btn btn-sm btn-outline-info me-2 mb-2">
                                        Statement {{ $index + 1 }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if ($loan->salary_slips)
                            <hr class="horizontal dark my-3">
                            <h6 class="text-xs font-weight-bold text-uppercase text-secondary mb-3">Salary Slips</h6>
                            <div class="d-flex flex-wrap">
                                @foreach ($loan->salary_slips as $index => $slip)
                                    <a href="{{ asset('storage/' . $slip) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary me-2 mb-2">
                                        Slip {{ $index + 1 }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <hr class="horizontal dark my-3">
                        <h6 class="text-xs font-weight-bold text-uppercase text-secondary mb-3">Guarantor Documents</h6>
                        @foreach ($loan->guarantors as $index => $guarantor)
                            <div class="mb-3">
                                <p class="text-xs font-weight-bold mb-1">Guarantor {{ $index + 1 }}:</p>
                                <div class="d-flex flex-wrap">
                                    @if ($guarantor->nic_copy)
                                        <a href="{{ asset('storage/' . $guarantor->nic_copy) }}" target="_blank"
                                            class="btn btn-xs btn-link text-primary ps-0 mb-0">
                                            <i class="fas fa-id-card me-1"></i> NIC Copy
                                        </a>
                                    @endif
                                    @if ($guarantor->salary_slips)
                                        @foreach ($guarantor->salary_slips as $sIndex => $gSlip)
                                            <a href="{{ asset('storage/' . $gSlip) }}" target="_blank"
                                                class="btn btn-xs btn-link text-primary mb-0">
                                                <i class="fas fa-file-invoice-dollar me-1"></i> Slip {{ $sIndex + 1 }}
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card shadow-sm border-radius-xl bg-gradient-dark">
                    <div class="card-body p-3 text-center">
                        <h6 class="text-white mb-3">Need Help?</h6>
                        <p class="text-white opacity-8 text-xs">If you have any questions regarding your loan status,
                            please contact our support team.</p>
                        <a href="mailto:support@smartload.com" class="btn btn-white btn-sm w-100 mb-0">Contact Support</a>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('loans.index') }}" class="btn btn-link text-secondary mb-0">
                        <i class="fas fa-arrow-left me-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            let adminCommitmentIndex = {{ $loan->commitments->count() }};
            let adminExpenseIndex = {{ $loan->expenses->count() }};

            function addAdminRow(type) {
                if (type === 'commitments') {
                    let container = document.getElementById('admin-commitments-container');
                    let row = document.createElement('div');
                    row.className = 'row mb-2';
                    row.innerHTML = `
                <div class="col-7 pe-1">
                    <input type="text" name="financial_commitments[${adminCommitmentIndex}][name]" class="form-control form-control-sm" placeholder="Name">
                </div>
                <div class="col-5 ps-1">
                    <input type="number" name="financial_commitments[${adminCommitmentIndex}][amount]" class="form-control form-control-sm" placeholder="0">
                </div>
            `;
                    container.appendChild(row);
                    adminCommitmentIndex++;
                } else {
                    let container = document.getElementById('admin-expenses-container');
                    let row = document.createElement('div');
                    row.className = 'row mb-2';
                    row.innerHTML = `
                <div class="col-7 pe-1">
                    <input type="text" name="personal_expenses[${adminExpenseIndex}][name]" class="form-control form-control-sm" placeholder="Name">
                </div>
                <div class="col-5 ps-1">
                    <input type="number" name="personal_expenses[${adminExpenseIndex}][amount]" class="form-control form-control-sm" placeholder="0">
                </div>
            `;
                    container.appendChild(row);
                    adminExpenseIndex++;
                }
            }
        </script>
    @endpush
@endsection

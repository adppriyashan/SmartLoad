@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 text-center">
                        <h4 class="font-weight-bolder">Loan Request Form</h4>
                        <p class="mb-0 text-sm">Please follow the steps to complete your loan application</p>
                    </div>
                    <div class="card-body">
                        <!-- Progress Bar -->
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="progress-wrapper mx-auto" style="max-width: 800px;">
                                    <div class="progress" style="height: 4px;">
                                        <div id="progress-bar" class="progress-bar bg-gradient-primary w-15"
                                            role="progressbar"></div>
                                    </div>
                                    <div
                                        class="d-flex justify-content-between mt-3 text-xs font-weight-bold text-uppercase">
                                        <span class="step-indicator active">Details</span>
                                        <span class="step-indicator">Income</span>
                                        <span class="step-indicator">Financials</span>
                                        <span class="step-indicator">Uploads</span>
                                        <span class="step-indicator">Guarantors</span>
                                        <span class="step-indicator">Finalize</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="loanForm" method="POST" action="{{ route('loans.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Step 1: Loan Details -->
                            <div class="step-content active" id="step-1">
                                <h5 class="font-weight-bolder mb-4">Step 1: Loan Details</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Loan Type</label>
                                        <select name="loan_type" class="form-control shadow-none" required>
                                            <option value="Personal">Personal</option>
                                            <option value="Leasing">Leasing</option>
                                            <option value="Housing">Housing</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Loan Amount Requested (LKR)</label>
                                        <input type="number" name="loan_amount" class="form-control"
                                            placeholder="e.g. 50000" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Loan Tenure</label>
                                        <select name="loan_tenure" class="form-control shadow-none" required>
                                            <option value="1 Year">1 Year</option>
                                            <option value="2 Years">2 Years</option>
                                            <option value="3 Years">3 Years</option>
                                            <option value="4 Years">4 Years</option>
                                            <option value="5 Years">5 Years</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Income & Employment -->
                            <div class="step-content" id="step-2" style="display:none;">
                                <h5 class="font-weight-bolder mb-4">Step 2: Income & Employment</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-control-label">Employment Status</label>
                                        <select name="employment_status" class="form-control shadow-none" required>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Probation">Probation</option>
                                            <option value="Contract">Contract</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-control-label">Basic Salary (LKR)</label>
                                        <input type="number" name="basic_salary" id="basic_salary"
                                            class="form-control income-calc" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-control-label">Gross Salary (LKR)</label>
                                        <input type="number" name="gross_salary" id="gross_salary"
                                            class="form-control income-calc" oninput="updateTotals()" required>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-4">
                                <h6 class="text-uppercase text-xs font-weight-bolder opacity-6">Other Incomes (Optional)
                                </h6>
                                <div id="other-incomes-container">
                                    <!-- Dynamic rows here -->
                                </div>
                                <button type="button" class="btn btn-sm bg-gradient-dark mb-4"
                                    onclick="addOtherIncomeRow()">
                                    <i class="fas fa-plus me-2"></i> Add Other Income
                                </button>

                                <div class="alert alert-info text-white border-0 shadow-none">
                                    <h6 class="text-white mb-0">Total Monthly Income: <span
                                            id="total-income-display">LKR0</span></h6>
                                </div>
                            </div>

                            <!-- Step 3: Financial Situation -->
                            <div class="step-content" id="step-3" style="display:none;">
                                <h5 class="font-weight-bolder mb-4">Step 3: Financial Situation</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-control-label">Presently Active Loans (Number)</label>
                                        <input type="number" name="active_loans_count" class="form-control" value="0"
                                            required>
                                    </div>
                                </div>

                                <hr class="horizontal dark my-4">
                                <h6 class="text-uppercase text-xs font-weight-bolder opacity-6">Monthly Financial
                                    Commitments</h6>
                                <div id="commitments-container">
                                    <!-- Dynamic rows -->
                                </div>
                                <button type="button" class="btn btn-sm bg-gradient-dark mb-4"
                                    onclick="addCommitmentRow()">
                                    <i class="fas fa-plus me-2"></i> Add Commitment
                                </button>

                                <hr class="horizontal dark my-4">
                                <h6 class="text-uppercase text-xs font-weight-bolder opacity-6">Monthly Personal Expenses
                                </h6>
                                <div id="expenses-container">
                                    <!-- Dynamic rows -->
                                </div>
                                <button type="button" class="btn btn-sm bg-gradient-dark mb-4"
                                    onclick="addExpenseRow()">
                                    <i class="fas fa-plus me-2"></i> Add Expense
                                </button>

                                <div class="alert alert-warning text-white border-0 shadow-none">
                                    <h6 class="text-white mb-0">Total Monthly Expenses: <span
                                            id="total-expenses-display">LKR0</span></h6>
                                </div>
                            </div>

                            <!-- Step 4: Required Uploads -->
                            <div class="step-content" id="step-4" style="display:none;">
                                <h5 class="font-weight-bolder mb-4">Step 4: Required Uploads</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">NIC / Passport Copy</label>
                                        <input type="file" name="nic_copy" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Salary Slips (Last 3 months)</label>
                                        <input type="file" name="salary_slips[]" class="form-control" multiple
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Bank Statement (Last 3-6 months)</label>
                                        <input type="file" name="bank_statement" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-control-label">Employment Letter</label>
                                        <input type="file" name="employment_letter" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 5: Guarantor Details -->
                            <div class="step-content" id="step-5" style="display:none;">
                                <h5 class="font-weight-bolder mb-4">Step 5: Guarantor Details (Min 2)</h5>
                                <div class="row" id="guarantors-details-container">
                                    <!-- Guarantor 1 -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-gray-100 shadow-none p-3 border-radius-lg">
                                            <h6 class="mb-3">Guarantor 1</h6>
                                            <div class="form-group mb-2">
                                                <input type="text" name="guarantors[0][name]"
                                                    class="form-control form-control-sm" placeholder="Full Name" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input type="text" name="guarantors[0][nic]"
                                                    class="form-control form-control-sm" placeholder="NIC" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input type="text" name="guarantors[0][address]"
                                                    class="form-control form-control-sm" placeholder="Address" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="number" name="guarantors[0][age]"
                                                        class="form-control form-control-sm" placeholder="Age" required>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" name="guarantors[0][job_title]"
                                                        class="form-control form-control-sm" placeholder="Job Title"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Guarantor 2 -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-gray-100 shadow-none p-3 border-radius-lg">
                                            <h6 class="mb-3">Guarantor 2</h6>
                                            <div class="form-group mb-2">
                                                <input type="text" name="guarantors[1][name]"
                                                    class="form-control form-control-sm" placeholder="Full Name" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input type="text" name="guarantors[1][nic]"
                                                    class="form-control form-control-sm" placeholder="NIC" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input type="text" name="guarantors[1][address]"
                                                    class="form-control form-control-sm" placeholder="Address" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="number" name="guarantors[1][age]"
                                                        class="form-control form-control-sm" placeholder="Age" required>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" name="guarantors[1][job_title]"
                                                        class="form-control form-control-sm" placeholder="Job Title"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 6: Guarantor Uploads -->
                            <div class="step-content" id="step-6" style="display:none;">
                                <h5 class="font-weight-bolder mb-4">Step 6: Guarantor Uploads</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="text-sm">Guarantor 1 Documents</h6>
                                        <label class="form-control-label text-xs">NIC / Passport Copy</label>
                                        <input type="file" name="guarantors[0][nic_copy]" class="form-control mb-2"
                                            required>
                                        <label class="form-control-label text-xs">Salary Slips (3 months)</label>
                                        <input type="file" name="guarantors[0][salary_slips][]" class="form-control"
                                            multiple required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="text-sm">Guarantor 2 Documents</h6>
                                        <label class="form-control-label text-xs">NIC / Passport Copy</label>
                                        <input type="file" name="guarantors[1][nic_copy]" class="form-control mb-2"
                                            required>
                                        <label class="form-control-label text-xs">Salary Slips (3 months)</label>
                                        <input type="file" name="guarantors[1][salary_slips][]" class="form-control"
                                            multiple required>
                                    </div>
                                </div>

                                <div class="text-center mt-5">
                                    <p class="text-sm text-secondary">By submitting, you agree that all information
                                        provided is accurate.</p>
                                    <button type="submit" class="btn bg-gradient-primary btn-lg w-100">Submit Loan
                                        Request</button>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4" id="form-navigation">
                                <button type="button" class="btn btn-light mb-0" id="prevBtn" onclick="nextPrev(-1)"
                                    style="display:none;">Previous</button>
                                <button type="button" class="btn bg-gradient-dark mb-0 ms-auto" id="nextBtn"
                                    onclick="nextPrev(1)">Next</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentStep = 1;
            const totalSteps = 6;

            function showStep(n) {
                let steps = document.getElementsByClassName("step-content");
                let indicators = document.getElementsByClassName("step-indicator");

                for (let i = 0; i < steps.length; i++) {
                    steps[i].style.display = "none";
                    indicators[i].classList.remove("active");
                }

                document.getElementById("step-" + n).style.display = "block";
                indicators[n - 1].classList.add("active");

                // Progress bar
                let progress = (n / totalSteps) * 100;
                document.getElementById("progress-bar").style.width = progress + "%";

                // Nav buttons
                if (n == 1) {
                    document.getElementById("prevBtn").style.display = "none";
                } else {
                    document.getElementById("prevBtn").style.display = "inline";
                }

                if (n == totalSteps) {
                    document.getElementById("nextBtn").style.display = "none";
                } else {
                    document.getElementById("nextBtn").style.display = "inline";
                }
            }

            function nextPrev(n) {
                if (n == 1 && !validateForm()) return false;
                currentStep += n;
                if (currentStep > totalSteps) {
                    // Form is handled by the submit button in step 6
                    return false;
                }
                showStep(currentStep);
            }

            function validateForm() {
                // Basic validation for required fields in current step
                let valid = true;
                let inputs = document.getElementById("step-" + currentStep).querySelectorAll("[required]");
                inputs.forEach(input => {
                    if (input.value == "") {
                        input.classList.add("is-invalid");
                        valid = false;
                    } else {
                        input.classList.remove("is-invalid");
                    }
                });
                return valid;
            }

            // Dynamic Income Rows
            function addOtherIncomeRow() {
                let container = document.getElementById("other-incomes-container");
                let row = document.createElement("div");
                row.className = "row mb-3 align-items-end dynamic-income-row";
                row.innerHTML = `
            <div class="col-md-5">
                <label class="text-xs">Income Name</label>
                <input type="text" name="other_incomes[][name]" class="form-control form-control-sm" placeholder="e.g. Rent">
            </div>
            <div class="col-md-5">
                <label class="text-xs">Amount (LKR)</label>
                <input type="number" name="other_incomes[][amount]" class="form-control form-control-sm income-calc" oninput="updateTotals()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-md btn-link text-danger mb-0" onclick="this.closest('.row').remove(); updateTotals();">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
                container.appendChild(row);
            }

            // Dynamic Commitments Rows
            function addCommitmentRow() {
                let container = document.getElementById("commitments-container");
                let row = document.createElement("div");
                row.className = "row mb-3 align-items-end dynamic-expense-row";
                row.innerHTML = `
            <div class="col-md-5">
                <label class="text-xs">Commitment Name</label>
                <input type="text" name="financial_commitments[][name]" class="form-control form-control-sm" placeholder="e.g. Car Loan">
            </div>
            <div class="col-md-5">
                <label class="text-xs">Amount (LKR)</label>
                <input type="number" name="financial_commitments[][amount]" class="form-control form-control-sm expense-calc" oninput="updateTotals()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-md btn-link text-danger mb-0" onclick="this.closest('.row').remove(); updateTotals();">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
                container.appendChild(row);
            }

            // Dynamic Expense Rows
            function addExpenseRow() {
                let container = document.getElementById("expenses-container");
                let row = document.createElement("div");
                row.className = "row mb-3 align-items-end dynamic-expense-row";
                row.innerHTML = `
            <div class="col-md-5">
                <label class="text-xs">Expense Name</label>
                <input type="text" name="personal_expenses[][name]" class="form-control form-control-sm" placeholder="e.g. Food">
            </div>
            <div class="col-md-5">
                <label class="text-xs">Amount (LKR)</label>
                <input type="number" name="personal_expenses[][amount]" class="form-control form-control-sm expense-calc" oninput="updateTotals()">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-md btn-link text-danger mb-0" onclick="this.closest('.row').remove(); updateTotals();">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
                container.appendChild(row);
            }

            function updateTotals() {
                // Income calculation
                let gross = parseFloat(document.getElementById("gross_salary").value) || 0;
                let others = document.querySelectorAll(".income-calc");
                let totalIncome = gross;
                others.forEach(input => {
                    if (input.id !== "basic_salary" && input.id !== "gross_salary") {
                        totalIncome += parseFloat(input.value) || 0;
                    }
                });
                document.getElementById("total-income-display").innerText = "LKR" + totalIncome.toLocaleString();

                // Expense calculation
                let expenses = document.querySelectorAll(".expense-calc");
                let totalExpenses = 0;
                expenses.forEach(input => {
                    totalExpenses += parseFloat(input.value) || 0;
                });
                document.getElementById("total-expenses-display").innerText = "LKR" + totalExpenses.toLocaleString();
            }

            document.getElementById("basic_salary").oninput = updateTotals;
            document.getElementById("gross_salary").oninput = updateTotals;

            // Initial show
            showStep(1);
        </script>
    @endpush

    <style>
        .step-indicator {
            position: relative;
            color: #adb5bd;
        }

        .step-indicator.active {
            color: #cb0c9f;
        }

        .step-content {
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

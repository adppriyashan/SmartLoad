@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-radius-xl">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="mb-0">Filter Records</h6>
                                <p class="text-xs text-secondary mb-0">Search by application date range</p>
                            </div>
                            <div class="col-md-8">
                                <form method="GET" action="{{ route('loans.index') }}" class="row g-2 align-items-end justify-content-md-end">
                                    <div class="col-md-3">
                                        <label class="text-xs font-weight-bold mb-1">From Date</label>
                                        <input type="date" name="from_date" class="form-control form-control-sm border-radius-md" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-xs font-weight-bold mb-1">To Date</label>
                                        <input type="date" name="to_date" class="form-control form-control-sm border-radius-md" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm bg-gradient-primary mb-0 border-radius-md px-3">
                                            <i class="fas fa-filter me-1"></i> Apply
                                        </button>
                                        @if(request()->hasAny(['from_date', 'to_date']))
                                            <a href="{{ route('loans.index') }}" class="btn btn-sm btn-link text-danger mb-0 px-2">
                                                <i class="fas fa-times me-1"></i> Clear
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>My Loan Requests</h6>
                        <a href="{{ route('loans.request') }}" class="btn btn-sm bg-gradient-primary mb-0">
                            <i class="fas fa-plus me-2"></i> New Request
                        </a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Loan
                                            Type</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Amount</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Applied On</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($loans as $loan)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <div
                                                            class="avatar avatar-sm me-3 bg-gradient-faded-info border-radius-md text-center">
                                                            <i class="fas fa-hand-holding-usd text-white pt-2"></i>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $loan->loan_type }}</h6>
                                                        <p class="text-xs text-secondary mb-0">Tenure:
                                                            {{ $loan->loan_tenure }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    LKR {{ number_format($loan->loan_amount, 2) }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @if ($loan->status == 'Verified')
                                                    <span class="badge badge-sm bg-gradient-success">Verified</span>
                                                @elseif($loan->status == 'Rejected')
                                                    <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-info">Submitted for
                                                        verification</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $loan->created_at->format('Y-m-d') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('loans.show', $loan->id) }}"
                                                    class="btn btn-sm btn-outline-primary mb-0 border-radius-lg">
                                                    Details
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-sm text-secondary">No loan requests found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

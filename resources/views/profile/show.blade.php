@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="{{ $user->image ? asset('storage/' . $user->image) : 'https://demos.creative-tim.com/soft-ui-dashboard/assets/img/bruce-mars.jpg' }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ $user->customer_name }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ $user->job }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Profile Information</h6>
                </div>
                <div class="card-body p-3">
                    <p class="text-sm">
                        Hi, I’m {{ $user->customer_name }}. Welcome to my profile.
                    </p>
                    <hr class="horizontal gray-light my-4">
                    <ul class="list-group">
                        <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; {{ $user->customer_name }}</li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp; {{ $user->tel }}</li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; {{ $user->email }}</li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Location:</strong> &nbsp; {{ $user->address }}</li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">NIC:</strong> &nbsp; {{ $user->nic }}</li>
                        <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">DOB:</strong> &nbsp; {{ $user->dob ? $user->dob->format('Y-m-d') : 'N/A' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

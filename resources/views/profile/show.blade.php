@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('https://demos.creative-tim.com/soft-ui-dashboard/assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
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
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" id="view-tab" data-bs-toggle="tab" href="#view-profile" role="tab" aria-selected="true">
                                <i class="fas fa-user"></i>
                                <span class="ms-1">View</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" id="edit-tab" data-bs-toggle="tab" href="#edit-profile" role="tab" aria-selected="false">
                                <i class="fas fa-edit"></i>
                                <span class="ms-1">Edit</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="tab-content">
        <!-- View Profile Tab -->
        <div class="tab-pane fade show active" id="view-profile" role="tabpanel">
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card h-100 shadow-sm border-radius-xl">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-md-8 d-flex align-items-center">
                                    <h6 class="mb-0">Profile Information</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <p class="text-sm text-secondary">
                                Hi, I’m <strong>{{ $user->customer_name }}</strong>. I am currently working as a <strong>{{ $user->job }}</strong>. I'm passionate about my work and always looking for new opportunities to learn and grow.
                            </p>
                            <hr class="horizontal gray-light my-4">
                            <ul class="list-group">
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="avatar avatar-sm me-3 bg-gradient-primary shadow-primary border-radius-md">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Full Name</h6>
                                        <p class="mb-0 text-xs text-secondary">{{ $user->customer_name }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="avatar avatar-sm me-3 bg-gradient-info shadow-info border-radius-md">
                                        <i class="fas fa-phone text-white"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Mobile</h6>
                                        <p class="mb-0 text-xs text-secondary">{{ $user->tel }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="avatar avatar-sm me-3 bg-gradient-success shadow-success border-radius-md">
                                        <i class="fas fa-envelope text-white"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Email</h6>
                                        <p class="mb-0 text-xs text-secondary">{{ $user->email }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="avatar avatar-sm me-3 bg-gradient-warning shadow-warning border-radius-md">
                                        <i class="fas fa-map-marker-alt text-white"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Location</h6>
                                        <p class="mb-0 text-xs text-secondary text-truncate" style="max-width: 200px;">{{ $user->address }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="avatar avatar-sm me-3 bg-gradient-dark shadow-dark border-radius-md">
                                        <i class="fas fa-id-card text-white"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">NIC</h6>
                                        <p class="mb-0 text-xs text-secondary">{{ $user->nic }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                                    <div class="avatar avatar-sm me-3 bg-gradient-danger shadow-danger border-radius-md">
                                        <i class="fas fa-birthday-cake text-white"></i>
                                    </div>
                                    <div class="d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">Age</h6>
                                        <p class="mb-0 text-xs text-secondary">{{ $user->age }} Years Old</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8 mt-4 mt-xl-0">
                    <div class="card h-100 shadow-sm border-radius-xl">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Account Status</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card bg-gradient-dark shadow-dark border-radius-lg">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="numbers">
                                                        <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Current Role</p>
                                                        <h5 class="text-white font-weight-bolder mb-0 text-capitalize">
                                                            {{ $user->role }}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-4 text-end">
                                                    <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                        <i class="fas fa-shield-alt text-dark text-lg opacity-10" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card bg-gradient-success shadow-success border-radius-lg">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="numbers">
                                                        <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Profile Status</p>
                                                        <h5 class="text-white font-weight-bolder mb-0">
                                                            {{ $user->is_profile_complete ? 'Verified' : 'Pending' }}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-4 text-end">
                                                    <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                        <i class="fas fa-check-circle text-success text-lg opacity-10" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Tab -->
        <div class="tab-pane fade" id="edit-profile" role="tabpanel">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Edit Profile Information</h6>
                </div>
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img id="preview" src="{{ $user->image ? asset('storage/' . $user->image) : 'https://demos.creative-tim.com/soft-ui-dashboard/assets/img/bruce-mars.jpg' }}" class="rounded-circle border border-4 border-white shadow" style="width: 150px; height: 150px; object-fit: cover;">
                                    <label for="image" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle p-2">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="image" name="image" class="d-none" onchange="previewImage(this)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_name" class="form-control-label">Full Name</label>
                                    <input class="form-control @error('customer_name') is-invalid @enderror" type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $user->customer_name) }}" required>
                                    @error('customer_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nic" class="form-control-label">NIC</label>
                                    <input class="form-control @error('nic') is-invalid @enderror" type="text" id="nic" name="nic" value="{{ old('nic', $user->nic) }}" required>
                                    @error('nic')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tel" class="form-control-label">Telephone</label>
                                    <input class="form-control @error('tel') is-invalid @enderror" type="text" id="tel" name="tel" value="{{ old('tel', $user->tel) }}" required>
                                    @error('tel')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dob" class="form-control-label">Date of Birth</label>
                                    <input class="form-control @error('dob') is-invalid @enderror" type="date" id="dob" name="dob" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}" required>
                                    @error('dob')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="job" class="form-control-label">Job Title</label>
                                    <input class="form-control @error('job') is-invalid @enderror" type="text" id="job" name="job" value="{{ old('job', $user->job) }}" required>
                                    @error('job')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address" class="form-control-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                                    @error('address')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-primary btn-md mt-4 mb-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

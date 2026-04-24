@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white p-4">
                        <h4 class="mb-0">Complete Your Profile</h4>
                        <p class="text-white-50 mb-0">Please provide the missing information to get started.</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-12 text-center mb-3">
                                    <div class="position-relative d-inline-block">
                                        <img id="preview"
                                            src="https://demos.creative-tim.com/soft-ui-dashboard/assets/img/bruce-mars.jpg"
                                            class="rounded-circle border border-4 border-white shadow"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                        <label for="image"
                                            class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle p-2">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                        <input type="file" id="image" name="image" class="d-none"
                                            onchange="previewImage(this)">
                                    </div>
                                    @error('image')
                                        <div class="text-danger mt-2 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">Customer Name</label>
                                    <input type="text" id="customer_name" name="customer_name"
                                        class="form-control @error('customer_name') is-invalid @enderror"
                                        value="{{ old('customer_name', auth()->user()->name) }}" required>
                                    @error('customer_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nic" class="form-label">NIC (National Identity Card)</label>
                                    <input type="text" id="nic" name="nic"
                                        class="form-control @error('nic') is-invalid @enderror" value="{{ old('nic') }}"
                                        placeholder="e.g. 199012345678 or 901234567V" required>
                                    @error('nic')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tel" class="form-label">Telephone</label>
                                    <input type="text" id="tel" name="tel"
                                        class="form-control @error('tel') is-invalid @enderror" value="{{ old('tel') }}"
                                        required>
                                    @error('tel')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" id="dob" name="dob"
                                        class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}"
                                        required>
                                    @error('dob')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="job" class="form-label">Job Title</label>
                                    <input type="text" id="job" name="job"
                                        class="form-control @error('job') is-invalid @enderror" value="{{ old('job') }}"
                                        required>
                                    @error('job')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                        required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow">Complete
                                    Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(310deg, #7928ca 0%, #ff0080 100%);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #e9ecef;
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px rgba(121, 40, 202, 0.25);
            border-color: #7928ca;
        }
    </style>

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

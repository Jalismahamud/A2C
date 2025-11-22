@extends('backend.layouts.master')
@section('title', 'Setting Edit')
@section('content')
    <style>
        .form-check,
        .form-group {
            padding: 0;
        }

        .select2-container--default .select2-selection--single {
            border: 2px solid #ebedf2;
        }

        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 70%;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }
    </style>
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4>Setting</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="card-title">Edit Setting</div>
                                <div class="py-0 ms-md-auto py-md-0">
                                    <a href="{{ route('setting.index') }}" class="btn btn-primary btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2">
                                    <!-- Name -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="company_name">Company Name<span style="color: red">*</span></label>
                                            <input type="text" name="company_name"
                                                class="form-control @error('company_name') is-invalid @enderror"
                                                value="{{ old('company_name', $setting->company_name ?? '') }}">
                                            @error('company_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="company_email">Company Email<span
                                                    style="color: red">*</span></label>
                                            <input type="text" name="company_email"
                                                class="form-control @error('company_email') is-invalid @enderror"
                                                value="{{ old('company_email', $setting->company_email ?? '') }}">
                                            @error('company_email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="company_phone">Company Phone<span
                                                    style="color: red">*</span></label>
                                            <input type="text" name="company_phone"
                                                class="form-control @error('company_phone') is-invalid @enderror"
                                                value="{{ old('company_phone', $setting->company_phone ?? '') }}">
                                            @error('company_phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="company_address">Company Address<span
                                                    style="color: red">*</span></label>
                                            <input type="text" name="company_address"
                                                class="form-control @error('company_address') is-invalid @enderror"
                                                value="{{ old('company_address', $setting->company_address ?? '') }}">
                                            @error('company_address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="registration_bonus">Registration Bonus<span
                                                    style="color: red">*</span></label>
                                            <input type="text" name="registration_bonus"
                                                class="form-control @error('registration_bonus') is-invalid @enderror"
                                                value="{{ old('registration_bonus', $setting->registration_bonus ?? '') }}">
                                            @error('registration_bonus')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="agent_minimum_withdraw">agent minimum withdraw<span
                                                    style="color: red">*</span></label>
                                            <input type="text" name="agent_minimum_withdraw"
                                                class="form-control @error('agent_minimum_withdraw') is-invalid @enderror"
                                                value="{{ old('agent_minimum_withdraw', $setting->agent_minimum_withdraw ?? '') }}">
                                            @error('agent_minimum_withdraw')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Image -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="company_logo">Image(Preferred size: 400X400)</label>
                                            <input type="file" name="company_logo"
                                                class="form-control @error('company_logo') is-invalid @enderror"
                                                id="image" onchange="previewImage(event)" />
                                            @if ($setting?->company_logo)
                                                <!-- Image preview -->
                                                <div class="mt-2">
                                                    <img id="imagePreview"
                                                        src="{{ asset('backend/images/banner/' . $setting->company_logo)}}"
                                                        alt="Customer Image" width="75">
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <img id="imagePreview" width="50">
                                                </div>
                                            @endif
                                            @error('company_logo')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush

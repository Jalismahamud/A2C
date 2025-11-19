

@extends('backend.incharge.layouts.master')
@section('title', 'Customer Create')
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
                <h4>Client</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="card-title">Add Customer</div>
                                <div class="py-0 ms-md-auto py-md-0">
                                    <a href="{{ route('incharge.customers.index') }}" class="btn btn-primary btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('incharge.customers.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2">
                                    <!-- Name -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Full Name<span style="color: red">*</span></label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone') }}">
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="present-address">Address</label>
                                            <textarea name="address" id="" class="form-control" cols="" rows="3"></textarea>
                                        </div>
                                    </div>


                                    <!-- Customer Type -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="type">Customer Type</label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="">Select Type</option>
                                                <option value="agent">Agent</option>
                                                <option value="general">General</option>
                                                <option value="student">Student</option>
                                                <option value="driver">Driver</option>

                                            </select>
                                        </div>
                                    </div>
                                    <!-- NID Number (For General & Driver) -->
                                    <div class="col-12 col-md-6 col-lg-4" id="nid_field" style="display: none;">
                                        <div class="form-group">
                                            <label for="nid">NID Number</label>
                                            <input type="number" name="nid_number" class="form-control"
                                                value="{{ old('nid_number') }}">
                                        </div>
                                    </div>

                                    <!-- Student Fields (For Student Type) -->

                                    <div class="col-12 col-md-6 col-lg-4" id="student_fields" style="display: none;">
                                        <div class="form-group">
                                            <label for="school_name">School Name</label>
                                            <input type="text" name="school_name" class="form-control"
                                                value="{{ old('school_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4" id="student_fields1" style="display: none;">
                                        <div class="form-group">
                                            <label for="teacher_name">Teacher Name</label>
                                            <input type="text" name="teacher_name" class="form-control"
                                                value="{{ old('teacher_name') }}">
                                        </div>
                                    </div>


                                    <!-- Driver Fields (For Driver Type) -->

                                    <div id="driver_fields" class="col-12 col-md-6 col-lg-4" style="display: none">
                                        <div class="form-group">
                                            <label for="vehicle_type">Vehicle Type</label>
                                            <input type="text" name="vehicle_type" class="form-control"
                                                value="{{ old('vehicle_type') }}">
                                        </div>
                                    </div>
                                    <div id="driver_fields1" class="col-12 col-md-6 col-lg-4" style="display: none">
                                        <div class="form-group">
                                            <label for="license_number">License Number</label>
                                            <input type="text" name="license_number" class="form-control"
                                                value="{{ old('license_number') }}">
                                        </div>
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="image">Image(Preferred size: 400X400)</label>
                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror" id="image"
                                                onchange="previewImage(event)" />
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                            <div class="mt-2">
                                                <img id="imagePreview" width="50">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Save</button>
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

    <script type="text/javascript">
        function previewSignatureImage(event) {
            var reader1 = new FileReader();
            reader1.onload = function() {
                var output1 = document.getElementById('imageSignaturePreview');
                output1.src = reader1.result;
                output1.style.display = 'block';
            };
            reader1.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        $(document).ready(function() {

            $(".js-example-templating").select2({
                placeholder: "Select",
                allowClear: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initially hide all conditional fields
            $('#nid_field, #student_fields, #student_fields1, #driver_fields, #driver_fields1').hide();

            $('#type').on('change', function() {
                var selectedType = $(this).val();

                // Hide all fields initially
                $('#nid_field, #student_fields, #student_fields1, #driver_fields, #driver_fields1').hide();

                // Show specific fields based on the selected type
                if (selectedType === 'general') {
                    $('#nid_field').show();
                } else if (selectedType === 'agent') {
                    $('#nid_field').show();
                } else if (selectedType === 'student') {
                    $('#student_fields, #student_fields1').show();
                } else if (selectedType === 'driver') {
                    $('#nid_field, #driver_fields, #driver_fields1').show();
                }
            });
        });
    </script>
@endpush

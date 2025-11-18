@extends('backend.layouts.master')
@section('title', 'Customer Edit')
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
                                <div class="card-title">Edit Customer</div>
                                <div class="py-0 ms-md-auto py-md-0">
                                    <a href="{{ route('users.customer-list') }}" class="btn btn-primary btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.customer-update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2">
                                    <!-- Name -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Full Name<span style="color: red">*</span></label>
                                            <input type="text" name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $customer->name ?? '') }}">
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
                                                   value="{{ old('phone', $customer->phone ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="present-address">Address</label>
                                            <textarea name="address" id="" class="form-control" cols="" rows="3">{!! $customer->address ?? '' !!}</textarea>
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="type">Customer Type</label>
                                            <select name="type" id="type" class="form-control">
                                                <option value="">Select Type</option>
                                                <option value="general" @if($customer->type == 'general') selected @endif>General</option>
                                                <option value="student" @if($customer->type == 'student') selected @endif>Student</option>
                                                <option value="driver" @if($customer->type == 'driver') selected @endif>Driver</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- NID Number -->
                                    <div class="col-12 col-md-6 col-lg-4" id="nid_field" style="display: none;">
                                        <div class="form-group">
                                            <label for="nid">NID Number</label>
                                            <input type="number" name="nid_number" class="form-control" value="{{ old('nid_number', $customer->nid_number) }}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4" id="student_fields" style="display: none;">
                                        <div class="form-group">
                                            <label for="school_name">School Name</label>
                                            <input type="text" name="school_name" class="form-control" value="{{ old('school_name', $customer->school_name) }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4" id="student_fields1" style="display: none;">
                                        <div class="form-group">
                                            <label for="teacher_name">Teacher Name</label>
                                            <input type="text" name="teacher_name" class="form-control" value="{{ old('teacher_name', $customer->teacher_name) }}">
                                        </div>
                                    </div>

                                    <div id="driver_fields" class="col-12 col-md-6 col-lg-4" style="display: none">
                                        <div class="form-group">
                                            <label for="vehicle_type">Vehicle Type</label>
                                            <input type="text" name="vehicle_type" class="form-control" value="{{ old('vehicle_type', $customer->vehicle_type) }}">
                                        </div>
                                    </div>
                                    <div id="driver_fields1" class="col-12 col-md-6 col-lg-4" style="display: none">
                                        <div class="form-group">
                                            <label for="license_number">License Number</label>
                                            <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $customer->license_number) }}">
                                        </div>
                                    </div>
                                    <!-- Image -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="image">Image(Preferred size: 400X400)</label>
                                            <input type="file" name="image"
                                                   class="form-control @error('image') is-invalid @enderror" id="image"
                                                   onchange="previewImage(event)" />
                                        @if ($customer->image)
                                            <!-- Image preview -->
                                                <div class="mt-2">
                                                    <img id="imagePreview"
                                                         src="{{ asset('backend/images/customer/' . $customer->image) }}"
                                                         alt="Customer Image" width="75">
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <img id="imagePreview" width="50">
                                                </div>
                                            @endif
                                            @error('image')
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

    <script>
        $(document).ready(function() {
            function toggleFields() {
                var selectedType = $('#type').val();
                $('#nid_field, #student_fields, #teacher_fields, #driver_fields, #license_fields').hide();

                if (selectedType === 'general') {
                    $('#nid_field').show();
                } else if (selectedType === 'student') {
                    $('#student_fields, #teacher_fields').show();
                } else if (selectedType === 'driver') {
                    $('#nid_field, #driver_fields, #license_fields').show();
                }
            }
            toggleFields();
            $('#type').on('change', toggleFields);
        });
    </script>
@endpush


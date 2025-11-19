@extends('backend.incharge.layouts.master')
@section('title', 'Agent Edit')
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
                <h4>Agent</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="card-title">Edit Agent</div>
                                <div class="py-0 ms-md-auto py-md-0">
                                    <a href="{{ route('incharge.agents.index') }}" class="btn btn-primary btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('incharge.agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2">
                                    <!-- Name -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Full Name<span style="color: red">*</span></label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $agent->name ?? '') }}" required>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="phone">Phone<span style="color: red">*</span></label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', $agent->phone ?? '') }}" required>
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="address">Address<span style="color: red">*</span></label>
                                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" cols="30" rows="3" required>{{ old('address', $agent->address ?? '') }}</textarea>
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- NID Number -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="nid_number">NID Number</label>
                                            <input type="text" name="nid_number" class="form-control"
                                                value="{{ old('nid_number', $agent->nid_number ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1" @if($agent->status == 1) selected @endif>Active</option>
                                                <option value="0" @if($agent->status == 0) selected @endif>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Approved -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="approved">Approved</label>
                                            <select name="approved" id="approved" class="form-control">
                                                <option value="0" @if($agent->approved == 0) selected @endif>Not Approved</option>
                                                <option value="1" @if($agent->approved == 1) selected @endif>Approved</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="image">Image (Preferred size: 400x400)</label>
                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror" id="image"
                                                onchange="previewImage(event)" />
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                            @if ($agent->image)
                                                <div class="mt-2">
                                                    <img id="imagePreview"
                                                        src="{{ asset('backend/images/user/' . $agent->image) }}"
                                                        alt="Agent Image" width="100">
                                                </div>
                                            @else
                                                <div class="mt-2">
                                                    <img id="imagePreview" width="100">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm mt-3">Update</button>
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

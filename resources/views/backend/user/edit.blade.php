@extends('backend.layouts.master')
@section('title', 'User Edit')
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
                <h4>User</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="card-title">Edit User</div>
                                <div class="py-0 ms-md-auto py-md-0">
                                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row g-2">
                                    <!-- Name -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">Full Name<span style="color: red">*</span></label>
                                            <input type="text" name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $user->name ?? '') }}">
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
                                                   value="{{ old('phone', $user->phone ?? '') }}">
                                        </div>
                                    </div>


                                    <!-- User Role -->
                                    @if($user->role != 1)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="role">User Role</label>
                                            <select name="role" id="role"
                                                    class="form-control">
                                                <option value="">Select Role</option>
                                                <option value="1" @if($user->role == 1) selected @endif>Admin</option>
                                                <option value="2" @if($user->role == 2) selected @endif>Incharge</option>
                                                <option value="3" @if($user->role == 3) selected @endif>Agent</option>

                                            </select>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Address -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="present-address">Address</label>
                                            <textarea name="address" id="" class="form-control" cols="" rows="3">{!! $user->address ?? '' !!}</textarea>
                                        </div>
                                    </div>


                                    <!-- NID Number -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="nid">NID Number</label>
                                            <input type="number" name="nid_number" class="form-control"
                                                   value="{{ old('nid_number', $user->nid_number ?? '') }}">
                                        </div>
                                    </div>

                                    <!-- Image -->
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="image">Image(Preferred size: 400X400)</label>
                                            <input type="file" name="image"
                                                   class="form-control @error('image') is-invalid @enderror" id="image"
                                                   onchange="previewImage(event)" />
                                        @if ($user->image)
                                            <!-- Image preview -->
                                                <div class="mt-2">
                                                    <img id="imagePreview"
                                                         src="{{ asset('backend/images/user/' . $user->image) }}"
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
@endpush


@extends('backend.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Users</a>
                    </li>
                </ul>
            </div>
            <div class="row ">
                <div class="col-xl-6 offset-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="employee__history__photo ">
                                @if ($user->image ?? '')
                                    <img style="height: 120px;width: 120px;border-radius: 50%; margin-left: 170px"
                                         src="{{ asset('backend/images/user/' . $user->image) }}" alt="no image">
                                @else
                                    <img src="{{ url('backend/images/no-image.png') }}" alt="customer" width="50">
                                @endif
                                <h3 class="employee_name"></h3>

                            </div>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Name</strong>
                                    <span>{{ $user->name ?? '' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Phone</strong>
                                    <span>{{ $user->phone ?? '' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-xl-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" float-left">Profile Update</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="post" id="basicform"
                                  data-parsley-validate="" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group from_group_mobile">
                                            <label for="site_name">Name</label>
                                            <input id="site_name" type="text" name="name"
                                                   value="{{ Auth::user()->name ?? old('name') }}"
                                                   data-parsley-trigger="change" autocomplete="off"
                                                   class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group from_group_mobile">
                                            <label for="phone">Phone </label>
                                            <input id="phone" type="text" name="phone"
                                                   value="{{ Auth::user()->phone ?? old('phone') }}"
                                                   data-parsley-trigger="change" autocomplete="off"
                                                   class="form-control @error('phone') is-invalid @enderror">
                                            @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group ">
                                            <label for="image">Profile Image <small class="text-success">(Preferred
                                                    size: 250X51)</small></label>
                                            <input id="imageUpload" type="file" name="image"
                                                   data-parsley-trigger="change" autocomplete="off" class="form-control">
                                        </div>
                                        @if (Auth()->user()->image)
                                            <img src="{{ asset('backend/images/user/' . Auth()->user()->image) }}"
                                                 id="imagePreview" alt="image" style="height: 80px; width: 80px"
                                                 class="ps-3 custom-img">
                                        @else
                                            <img src="{{ url('backend/images/no-image.png') }}" id="imagePreview"
                                                 alt="customer" width="50" class="ps-3">
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-5">
                                        <p class="text-right">
                                            <button type="submit" class="btn btn-space btn-primary btn-sm">
                                                Update
                                            </button>
                                            <button type="reset" class="btn btn-space btn-secondary btn-sm">
                                                Cancel
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-xl-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" float-left">Password Update</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user.update-password') }}" method="post" id="basicform"
                                  data-parsley-validate="" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group from_group_mobile">
                                            <label for="current_password">Current Password</label>
                                            <input id="current_password" type="password" name="current_password"
                                                   value="" data-parsley-trigger="change" autocomplete="off"
                                                   class="form-control @error('current_password') is-invalid @enderror">
                                            @error('current_password')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group from_group_mobile">
                                            <label for="new_password">New Password </label>
                                            <input id="new_password" type="password" name="new_password" value=""
                                                   data-parsley-trigger="change" autocomplete="off"
                                                   class="form-control @error('new_password') is-invalid @enderror">
                                            @error('new_password')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group from_group_mobile">
                                            <label for="confirm_password">Confirm Password </label>
                                            <input id="confirm_password" type="password" name="confirm_password"
                                                   value="" data-parsley-trigger="change" autocomplete="off"
                                                   class="form-control  @error('confirm_password') is-invalid @enderror">
                                            @error('confirm_password')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 pl-0">
                                        <p class="text-right">
                                            <button type="submit" class="btn btn-space btn-primary btn-sm">
                                                Update
                                            </button>
                                            <button type="reset" class="btn btn-space btn-secondary btn-sm">
                                                Cancel
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#imageUpload').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imagePreview').attr('src', event.target.result).show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreview').hide();
                }
            });
        });
    </script>
@endpush

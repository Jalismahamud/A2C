@extends('backend.layouts.master')
@section('content')
    <style>
        .form-check,
        .form-group {
            padding: 0;
        }
    </style>
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
                        <a href="#">Posts</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Post Create</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Add Post</h4>
                                <div class="ms-md-auto py-0 py-md-0">
                                    <a href="{{ route('posts.index') }}" class="btn btn-dark btn-sm">Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" value="{{ old('title') }}"
                                                   class="form-control @error('title') is-invalid @enderror"
                                                   placeholder="Enter Title" />
                                            @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="image">Images (Preferred size: 400x400)</label>
                                            <input type="file" name="image[]" class="form-control @error('image') is-invalid @enderror"
                                                   multiple id="image" onchange="previewImage(event)" />

                                            @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                            <div class="row mt-2" id="imagePreviewContainer">
                                                <!-- Preview will be inserted here -->
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <button type="submit" class="btn btn-primary btn-sm mt-4">
                                    Submit
                                </button>
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
            var container = document.getElementById('imagePreviewContainer');
            container.innerHTML = '';

            var rowDiv = document.createElement('div');
            rowDiv.classList.add('row');

            for (var i = 0; i < event.target.files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {

                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-fluid', 'rounded', 'border');
                    img.style.maxHeight = '100px';


                    var div = document.createElement('div');
                    div.classList.add('col-3', 'mb-2');
                    div.appendChild(img);

                    rowDiv.appendChild(div);


                    if (rowDiv.children.length === 4) {
                        output.appendChild(rowDiv);
                        rowDiv = document.createElement('div');
                        rowDiv.classList.add('row');
                    }
                };
                reader.readAsDataURL(event.target.files[i]);
            }


            if (rowDiv.children.length > 0) {
                container.appendChild(rowDiv);
            }
        }
    </script>


@endpush

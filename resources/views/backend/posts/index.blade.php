@extends('backend.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                {{--                <h3 class="fw-bold mb-3">DataTables</h3>--}}
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
                        <a href="#">Tables</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Post</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="card-title">Post List</div>
                                <div class="ms-md-auto py-0 py-md-0">
                                    <a href="{{ route('posts.create')  }}" class="btn btn-primary btn-sm">Add</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body showtable">
                            @include('backend.posts.index_table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        function updateDataTable() {
            const params = {
                page: $(".page-item.active .page-link").text(),
            };
            const url = "{{ route('posts.index') }}?" + $.param(params);

            $.get(url, function(response) {
                if (response) {
                    $(".showtable").html(response);
                    $('.pagination').html(response?.pagination);
                }
            });
        }



        // change page number
        $(document).on("click", ".pagination li a", function(e) {
            e.preventDefault();
            $(this).parent().addClass("active").siblings().removeClass("active");
            updateDataTable();
        });
    </script>

@endpush

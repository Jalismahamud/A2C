@extends('backend.layouts.master')
@section('title', 'Customer List')
@section('content')

    <style>
        @media only screen and (max-width: 600px) {
            .client {
                flex-direction: column;
            }
        }
        table.display.table.table-striped.table-hover thead tr th {
            background: #E2EFDA;
            padding: 10px !important;
            border-top: 2px solid #000 !important;
            border-bottom: 2px solid #000 !important;
        }
    </style>

    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="#"><i class="icon-home"></i></a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Tables</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Customer</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between client">
                                <div class="card-title">Customer List</div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 d-flex">
                                    <input type="text" id="search"
                                           placeholder='Search Here...'
                                           class="form-control searchData custom_form_control"
                                           aria-label="Sizing example input"
                                           aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                        </div>

                        <div class="card-body showtable">
                            @include('backend.user.customer_list_table')
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
                searchData: $(".searchData").val(),
                page: $(".page-item.active .page-link").text(),
            };
            const url = "{{ route('users.customer-list') }}?" + $.param(params);

            $.get(url, function(response) {
                if (response) {
                    $(".showtable").html(response);
                    $('.pagination').html(response?.pagination);
                }
            });
        }
        // search change
        $(document).on("keyup", ".searchData", updateDataTable);


        // change page number
        $(document).on("click", ".pagination li a", function(e) {
            e.preventDefault();
            $(this).parent().addClass("active").siblings().removeClass("active");
            updateDataTable();
        });
    </script>

@endpush

@extends('backend.layouts.master')
@section('title', 'Package Balance')
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
                    <li class="nav-item"><a href="#">Package Balance</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between client">
                                <div class="card-title">Package Balance List</div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Package User Name</th>
                                        <th>Amount</th>
                                        <th>Package Created Date</th>
                                        <th>Package Created Name</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($package_balance as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $row->user->name }}</td>
                                            <td>{{ $row->amount }}</td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                                            <td>{{ $row->createdBy->name ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#user_items').click(function(e) {
                e.preventDefault();
                var searchItems = $('#search_items').val();
                window.location.href = "{{ route('users.index') }}?search_items=" + searchItems;
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#user_items').click(function(e) {
                e.preventDefault();
                var searchItems = $('#search_items').val();
                window.location.href = "{{ route('users.index') }}?search_items=" + searchItems;
            });
        });

    </script>

@endpush

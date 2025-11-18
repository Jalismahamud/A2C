@extends('backend.layouts.master')
@section('title', 'Agent / Incharge List')
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
                    <li class="nav-item"><a href="#">Agent / Incharge</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between client">
                                <div class="card-title">Wallet Transaction List</div>
                                <div class="ms-md-auto py-0 py-md-0">
                                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Add New</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($withdraw_requests && count($withdraw_requests) > 0)
    @foreach ($withdraw_requests as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
             <td>{{ optional($row->user)->name ?? 'N/A' }}</td>
            <td>{{ $row->amount }}</td>
            <td>{{ $row->type == 1 ? 'Deposit' : 'Withdraw' }}</td>
            <td>{{ $row->status == 1 ? 'Approved' : 'Pending' }}</td>
            <td>
                <a href="{{ route('withdraw.approve', $row->id) }}"
                   class="btn btn-sm {{ $row->status == 1 ? 'btn-danger' : 'btn-primary' }}">
                    {{ $row->status == 1 ? 'Cancel' : 'Approve' }}
                </a>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center">No withdrawal requests found.</td>
    </tr>
@endif
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $withdraw_requests->links() }}
                                </div>
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

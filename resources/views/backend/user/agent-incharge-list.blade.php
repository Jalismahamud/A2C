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
                            <div class="d-flex align-items-center justify-content-between client">
                                <div class="card-title">Agent / Incharge List</div>
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
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Phone</th>
                                        <th>NID Number</th>
                                        <th>Address</th>
                                        <th>Balance</th>
                                        <th>Account Balance</th>
                                        <th>Status</th>
                                        <th>Approved</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach ($agents as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if (isset($row->image))
                                                    <img src="{{ url('backend/images/user/' . $row->image) }}" alt="customer" width="50">
                                                @else
                                                    <img src="{{ url('backend/images/no-image.png') }}" alt="customer" width="50">
                                                @endif
                                            </td>
                                            <td>{{ $row->name }}</td>
                                            <td>
                                                {{ $row->role == 2 ? 'Incharge' : 'Agent' }}
                                            </td>
                                            <td>{{ $row->phone }}</td>
                                            <td>{{ $row->nid_number ?? '' }}</td>
                                            <td>{!! $row->address ?? '' !!}</td>
                                            <td>{{ $row->balance ?? '' }}</td>
                                            <td>{{ $row->account_balance ?? '' }}</td>
                                            <td>
                                                <a href="{{ route('users.change-agent-status', $row->id) }}" class="btn btn-sm btn-{{ $row->status == 1 ? 'primary':'danger' }}">
                                                    {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ $row->approved == 1 ? 'Approved' : 'Not Approved' }}
                                            </td>
                                            <td class="text-center">{{ $row->addBY->name ?? '' }}</td>
                                            <td>
                                                @if($row->role == 3)
                                                    <button type="button" class="btn btn-sm btn-info"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmBalanceModal-{{ $row->id }}">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                            @endif

                                            <!-- Modal -->
                                                    <div class="modal fade" id="confirmBalanceModal-{{ $row->id }}" tabindex="-1"
                                                         aria-labelledby="confirmBalanceModalLabel-{{ $row->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Confirm Balance Addition</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <form action="{{ route('users.balance-add', $row->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                                <div class="form-group">

                                                                                    <input type="number" id="amount-{{ $row->id }}" name="amount"
                                                                                           class="form-control" style="width: 400px" placeholder="Enter amount"
                                                                                           value="{{ old('amount') }}" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-info">Yes, Add Balance</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <a href="{{ route('users.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                    <a href="{{ route('users.package-sheet', $row->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-book-open"></i>
                                                    </a>
                                                <form action="{{ route('users.destroy', $row->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this?');">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
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

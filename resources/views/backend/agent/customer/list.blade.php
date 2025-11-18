@extends('backend.agent.layouts.master')
@section('title', 'Customer List')
@section('content')
    <style>
        @media only screen and (max-width: 600px) {


            .client {
                flex-direction: column
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
                        <a href="#">Customer</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between client">
                                <div class="card-title">Customer List</div>
                                <div class="ms-md-auto py-0 py-md-0">
                                    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Add New</a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 d-flex">
                                    <input type="text" id="search_items" class="form-control"
                                        placeholder="Search with Client Id, Name, Phone, NID Number">
                                    <button id="user_items" class="btn-sm btn btn-primary d-flex align-items-center">
                                        <i class="fas fa-search me-2"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead class="text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Nid Number</th>
                                            <th>School Name</th>
                                            <th>Teacher Name</th>
                                            <th>Vehicle Type</th>
                                            <th>License Number</th>
                                            <th>Status</th>
                                            <th>Approved</th>
                                            <th>Created By</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($customers as $row)
                                            <tr>
                                                <td class="text-end">{{ $loop->iteration }}</td>
                                                <td class="text-end">
                                                    @if (isset($row->image))
                                                        <img src="{{ url('backend/images/customer/' . $row->image) }}"
                                                            alt="customer" width="50">
                                                    @else
                                                        <img src="{{ url('backend/images/no-image.png') }}" alt="customer"
                                                            width="50">
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $row->name }}</td>
                                                <td class="text-start">
                                                    @if ($row->type == 'general')
                                                        General
                                                    @elseif($row->type == 'student')
                                                        Student
                                                    @elseif($row->type == 'driver')
                                                        Driver
                                                    @else
                                                        Agent
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $row->phone ?? 'N\A'}}</td>
                                                <td class="text-center">{!! $row->address ?? 'N\A' !!}</td>
                                                <td class="text-center">{{ $row->nid_number ?? 'N\A' }}</td>
                                                <td class="text-center">{{ $row->school_name ?? 'N\A' }}</td>
                                                <td class="text-center">{{ $row->teacher_name ?? 'N\A' }}</td>
                                                <td class="text-center">{{ $row->vehicle_type ?? 'N\A' }}</td>
                                                <td class="text-center">{{ $row->license_number ?? 'N\A' }}</td>
                                                <td class="text-center">
                                                    {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $row->approved == 1 ? 'Approved' : 'Not Approved' }}
                                                </td>
                                                <td class="text-center">{{ $row->addBY->name ?? '' }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('customers.edit', $row->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="showDeleteConfirm({{ $row->id }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>


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
                window.location.href = "{{ route('customers.index') }}?search_items=" + searchItems;
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    <script>

        function showDeleteConfirm(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "If you delete this, it cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }


        function deleteItem(id) {
            NProgress.start();

            $.ajax({
                url: "{{ route('customers.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    NProgress.done();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message || 'Customer deleted successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    NProgress.done();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message ||
                            'Something went wrong while deleting the record.',
                    });
                }
            });
        }
    </script>
@endpush

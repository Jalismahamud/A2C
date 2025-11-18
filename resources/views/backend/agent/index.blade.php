@extends('backend.agent.layouts.master')
@section('title', 'Agent / Incharge')
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

            {{-- Dashboard Header --}}
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                    <h6 class="op-7 mb-2">Free Bootstrap 5 Agent Dashboard</h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('customers.index') }}" class="btn btn-label-info btn-round me-2">Manage</a>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-round">Add Customer</a>
                </div>
            </div>

            {{-- Dashboard Stats --}}
            <div class="row mb-4">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Customers</p>
                                        <h4 class="card-title">
                                            {{ $totalCustomer }} /
                                            <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysCustomer }} Today's Customer
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Table Section --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between client">
                                <div class="card-title">Latest Customers (Last 10)</div>
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
                                                <td class="text-start">{{ $row->name }}</td>
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
                                                <td class="text-start">{{ $row->phone }}</td>
                                                <td class="text-center">{!! $row->address ?? '' !!}</td>
                                                <td class="text-start">{{ $row->nid_number ?? '' }}</td>
                                                <td class="text-start">{{ $row->school_name ?? '' }}</td>
                                                <td class="text-start">{{ $row->teacher_name ?? '' }}</td>
                                                <td class="text-start">{{ $row->vehicle_type ?? '' }}</td>
                                                <td class="text-start">{{ $row->license_number ?? '' }}</td>
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

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $customers->links('pagination::bootstrap-5') }}
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

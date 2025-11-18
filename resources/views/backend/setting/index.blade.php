@extends('backend.layouts.master')
@section('title', 'Banner List')
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
                        <a href="#">Setting</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between client">
                                <div class="card-title">Company Information</div>
                                <div class="ms-md-auto py-0 py-md-0">
                                    <a href="{{ route('setting.edit') }}" class="btn btn-primary btn-sm">Edit
                                        Setting</a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Company Logo</th>
                                            <th>company name</th>
                                            <th>company email</th>
                                            <th>company phone</th>
                                            <th>company address</th>
                                            <th>registration bonus</th>
                                            <th>agent minimum withdraw</th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($settings as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if (isset($row->company_logo))
                                                        <img src="{{ url('backend/images/banner/' . $row->company_logo) }}"
                                                            alt="customer" width="50">
                                                    @else
                                                        <img src="{{ url('backend/images/no-image.png') }}" alt="customer"
                                                            width="50">
                                                    @endif
                                                </td>
                                                <td>{{ $row->company_name }}</td>
                                                <td>{{ $row->company_email }}</td>
                                                <td>{{ $row->company_phone }}</td>
                                                <td>{{ $row->company_address }}</td>
                                                <td>{{ $row->registration_bonus }}</td>
                                                <td>{{ $row->agent_minimum_withdraw }}</td>
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

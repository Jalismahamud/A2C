<table class="display table table-striped table-hover">
    <thead class="text-center">
    <tr>
        <th>#</th>
        <th>Image</th>
        <th>Name</th>
        <th>Type</th>
        <th>Phone</th>
        <th>NID Number</th>
        <th>Address</th>
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
    @foreach ($customers as $key => $row)
        <tr>
            <td>{{$startIndex + $key + 1 }}</td>
            <td class="text-end">
                @if (isset($row->image))
                    <img src="{{ url('backend/images/customer/' . $row->image) }}" alt="customer" width="50">
                @else
                    <img src="{{ url('backend/images/no-image.png') }}" alt="customer" width="50">
                @endif
            </td>
            <td class="text-start">{{ $row->name }}</td>
            <td class="text-start">{{ $row->name }}</td>
            <td class="text-start">
                {{ $row->type }}
            </td>
            <td class="text-start">{{ $row->phone }}</td>
            <td class="text-start">{{ $row->nid_number ?? '' }}</td>
            <td class="text-center">{!! $row->address ?? '' !!}</td>
            <td class="text-center">{{ $row->school_name ?? '' }}</td>
            <td class="text-center">{{ $row->teacher_name ?? '' }}</td>
            <td class="text-center">{{ $row->vehicle_type ?? '' }}</td>
            <td class="text-center">{{ $row->license_number ?? '' }}</td>
            <td class="text-center">
                <a href="{{ route('users.change-customer-status', $row->id) }}" class="btn btn-sm btn-{{ $row->status == 1 ? 'primary':'danger' }}">
                    {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                </a>
            </td>
            <td class="text-center">
                {{ $row->approved == 1 ? 'Approved' : 'Not Approved' }}
            </td>
            <td class="text-center">{{ $row->addBY->name ?? '' }}</td>
            <td class="text-end">

                <a href="{{ route('users.customer-edit', $row->id) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="{{ route('users.customer-delete', $row->id) }}" method="POST" style="display:inline;">
                    @csrf
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
<div class="pagination">
    {{ $customers->links() }}
</div>






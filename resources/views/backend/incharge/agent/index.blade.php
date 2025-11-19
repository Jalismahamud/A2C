@extends('backend.incharge.layouts.master')
@section('title', 'Incharge Agents')
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
                    <h3 class="fw-bold mb-3">Agents</h3>
                    <h6 class="op-7 mb-2">Manage your agents</h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="{{ route('incharge.agents.create') }}" class="btn btn-primary btn-round">Add Agent</a>
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
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Agents</p>
                                        <h4 class="card-title">
                                            {{ $totalAgent }} /
                                            <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysAgent }} Today's Agents
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Agent Table Section --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between client">
                            <div class="card-title">Latest Agents</div>
                            <div class="d-flex" style="gap:8px; align-items:center;">
                                <div>
                                    <label for="per-page-select" class="me-2">Per Page:</label>
                                    <select id="per-page-select" class="form-select form-select-sm" style="width:auto; display:inline-block;">
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                        <option value="all">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div id="pagination-info" class="text-muted small"></div>
                            </div>
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead class="text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>NID Number</th>
                                            <th>Status</th>
                                            <th>Approved</th>
                                            <th>Balance</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="agents-table-body" class="text-center">
                                        <!-- Filled by AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Page navigation" class="mt-3">
                                <ul class="pagination justify-content-center" id="pagination-container"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        (function(){
            const tbody = document.getElementById('agents-table-body');
            const perPageSelect = document.getElementById('per-page-select');
            const paginationContainer = document.getElementById('pagination-container');
            const paginationInfo = document.getElementById('pagination-info');
            if (!tbody) return;

            let currentPage = 1;

            function fetchAgents(page = 1) {
                currentPage = page;
                const perPage = perPageSelect ? perPageSelect.value : '25';
                const params = new URLSearchParams();
                if (perPage !== 'all') params.set('per_page', perPage);
                params.set('page', page);
                const url = '{{ route('incharge.agents-json') }}' + (params.toString() ? ('?' + params.toString()) : '');

                fetch(url, { headers: { 'Accept': 'application/json' } })
                    .then(res => { if (!res.ok) throw res; return res.json(); })
                    .then(json => {
                        const data = json.data || [];
                        const pagination = json.pagination || {};
                        tbody.innerHTML = '';
                        if (!data.length) {
                            const tr = document.createElement('tr');
                            tr.innerHTML = '<td colspan="11">No records found.</td>';
                            tbody.appendChild(tr);
                            paginationContainer.innerHTML = '';
                            paginationInfo.innerHTML = '';
                            return;
                        }

                        data.forEach((a, idx) => {
                            const tr = document.createElement('tr');
                            const imgHtml = a.image ? `<img src="${a.image}" width="50" height="50" alt="img">` : '';
                            const statusText = (a.status == 1) ? 'Active' : 'Inactive';
                            const approvedText = (a.approved == 1) ? 'Approved' : 'Not Approved';
                            const rowNumber = ((pagination.current_page - 1) * pagination.per_page) + idx + 1;
                            const editUrl = `{{ url('incharge/agents') }}/${a.id}/edit`;
                            tr.innerHTML = `
                                <td class="text-end">${rowNumber}</td>
                                <td class="text-center">${imgHtml}</td>
                                <td class="text-start">${escapeHtml(a.name)}</td>
                                <td class="text-start">${escapeHtml(a.phone)}</td>
                                <td class="text-start">${escapeHtml(a.address || '')}</td>
                                <td class="text-start">${escapeHtml(a.nid_number || '')}</td>
                                <td class="text-center">${escapeHtml(statusText)}</td>
                                <td class="text-center">${escapeHtml(approvedText)}</td>
                                <td class="text-center">${escapeHtml(a.balance || '0')}</td>
                                <td class="text-center">${escapeHtml(a.created_at || '')}</td>
                                <td class="text-end">
                                    <a href="${editUrl}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteConfirm(${a.id})"><i class="fa fa-trash"></i></button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });

                        // Update pagination info
                        const start = (pagination.current_page - 1) * pagination.per_page + 1;
                        const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
                        paginationInfo.innerHTML = `Showing ${start} to ${end} of ${pagination.total} entries`;

                        // Build pagination links
                        buildPagination(pagination);
                    })
                    .catch(err => { console.error('Failed to load agents', err); });
            }

            function buildPagination(pagination) {
                paginationContainer.innerHTML = '';
                const perPage = perPageSelect.value;
                if (perPage === 'all' || pagination.last_page <= 1) return;

                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${pagination.current_page <= 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `<a class="page-link" href="#" onclick="window.fetchAgents(${pagination.current_page - 1}); return false;">Previous</a>`;
                paginationContainer.appendChild(prevLi);

                for (let i = 1; i <= pagination.last_page; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#" onclick="window.fetchAgents(${i}); return false;">${i}</a>`;
                    paginationContainer.appendChild(li);
                }

                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}`;
                nextLi.innerHTML = `<a class="page-link" href="#" onclick="window.fetchAgents(${pagination.current_page + 1}); return false;">Next</a>`;
                paginationContainer.appendChild(nextLi);
            }

            window.fetchAgents = fetchAgents;

            if (perPageSelect) perPageSelect.addEventListener('change', function(){ currentPage = 1; fetchAgents(1); });

            // Initial load
            fetchAgents(1);

            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }
        })();
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
                url: "{{ route('incharge.agents.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    NProgress.done();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message || 'Agent deleted successfully.',
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

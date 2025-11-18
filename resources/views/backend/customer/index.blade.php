@extends('backend.layouts.master')
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
                        <a href="#">Customers</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between client">
                            <div class="card-title">Customer List</div>
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
                                <div class="input-group input-group-sm" style="width:320px;">
                                    <input type="text" id="customers-search" class="form-control" placeholder="Search name, phone, nid, address, school...">
                                    <button type="button" id="customers-search-clear" class="btn btn-outline-secondary" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
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
                                            <th>Nid Number</th>
                                            <th>School</th>
                                            <th>Teacher</th>
                                            <th>Vehicle</th>
                                            <th>License</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Approved</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody id="customers-table-body" class="text-center">
                                        <!-- Filled by AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Page navigation" class="mt-3">
                                <ul class="pagination justify-content-center" id="pagination-container">
                                    <!-- Filled by AJAX -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    (function(){
        const tbody = document.getElementById('customers-table-body');
        const searchInput = document.getElementById('customers-search');
        const clearBtn = document.getElementById('customers-search-clear');
        const perPageSelect = document.getElementById('per-page-select');
        const paginationContainer = document.getElementById('pagination-container');
        const paginationInfo = document.getElementById('pagination-info');
        if (!tbody) return;

        let currentPage = 1;
        let debounceTimer = null;
        const debounce = (fn, delay=300) => {
            return function(...args){
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(()=> fn.apply(this,args), delay);
            };
        };

        function fetchCustomers(page = 1) {
            currentPage = page;
            const search = searchInput ? searchInput.value.trim() : '';
            const perPage = perPageSelect ? perPageSelect.value : '25';
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (perPage !== 'all') params.set('per_page', perPage);
            params.set('page', page);
            const url = '{{ route('dashboard.customers-json') }}' + (params.toString() ? ('?' + params.toString()) : '');

            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(res => { if (!res.ok) throw res; return res.json(); })
                .then(json => {
                    const data = json.data || [];
                    const pagination = json.pagination || {};
                    tbody.innerHTML = '';
                    if (!data.length) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = '<td colspan="13">No records found.</td>';
                        tbody.appendChild(tr);
                        paginationContainer.innerHTML = '';
                        paginationInfo.innerHTML = '';
                        return;
                    }

                    data.forEach((c, idx) => {
                        const tr = document.createElement('tr');
                        const imgHtml = c.image ? `<img src="${c.image}" width="50" height="50" alt="img">` : '';
                        const statusText = (c.status == 1) ? 'Active' : 'Inactive';
                        const approvedText = (c.approved == 1) ? 'Approved' : 'Not Approved';
                        const rowNumber = ((pagination.current_page - 1) * pagination.per_page) + idx + 1;
                        tr.innerHTML = `
                            <td>${rowNumber}</td>
                            <td>${imgHtml}</td>
                            <td>${escapeHtml(c.name)}</td>
                            <td>${escapeHtml(c.phone)}</td>
                            <td>${escapeHtml(c.nid_number || '')}</td>
                            <td>${escapeHtml(c.school_name || '')}</td>
                            <td>${escapeHtml(c.teacher_name || '')}</td>
                            <td>${escapeHtml(c.vehicle_type || '')}</td>
                            <td>${escapeHtml(c.license_number || '')}</td>
                            <td>${escapeHtml(c.type || '')}</td>
                            <td>${escapeHtml(statusText)}</td>
                            <td>${escapeHtml(approvedText)}</td>
                            <td>${escapeHtml(c.created_by || '')}</td>
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
                .catch(err => { console.error('Failed to load customers', err); });
        }

        function buildPagination(pagination) {
            paginationContainer.innerHTML = '';
            const perPage = perPageSelect.value;
            if (perPage === 'all' || pagination.last_page <= 1) return;

            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${pagination.current_page <= 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" onclick="window.fetchCustomers(${pagination.current_page - 1}); return false;">Previous</a>`;
            paginationContainer.appendChild(prevLi);

            for (let i = 1; i <= pagination.last_page; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#" onclick="window.fetchCustomers(${i}); return false;">${i}</a>`;
                paginationContainer.appendChild(li);
            }

            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" onclick="window.fetchCustomers(${pagination.current_page + 1}); return false;">Next</a>`;
            paginationContainer.appendChild(nextLi);
        }

        window.fetchCustomers = fetchCustomers;

        const debouncedFetch = debounce(() => { currentPage = 1; fetchCustomers(1); }, 350);

        if (searchInput) searchInput.addEventListener('input', debouncedFetch);
        if (clearBtn) clearBtn.addEventListener('click', function(){ searchInput.value = ''; currentPage = 1; fetchCustomers(1); });
        if (perPageSelect) perPageSelect.addEventListener('change', function(){ currentPage = 1; fetchCustomers(1); });

        // Initial load
        fetchCustomers(1);

        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    })();
</script>
@endpush

@extends('backend.layouts.master')
@section('title', 'Sourav apps')

@section('content')
    <div class="container">
        <div class="page-inner">

             {{-- Dashboard Header --}}
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Admin Dashboard</h3>
                    {{-- <h6 class="op-7 mb-2">Free Bootstrap 5 Incharge Dashboard</h6> --}}
                </div>

            </div>
            <div class="row">
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
                                        <p class="card-category">Agent</p>
                                        <h4 class="card-title">{{ $agentCount ?? '' }} /
                                            <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysAgentCount }} Today's Agent
                                            </span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">InCharge</p>
                                        <h4 class="card-title">{{ $inChargeCount ?? '' }} /
                                             <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysInChargeCount }} Today's InCharge
                                            </span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-luggage-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Active Customer</p>
                                        <h4 class="card-title">{{ $customerActiveCount ?? '' }} /
                                             <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysCustomerActiveCount }} Today's Active Customer
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Inactive Customer</p>
                                        <h4 class="card-title">{{ $customerInactiveCount ?? '' }} /
                                             <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysCustomerInactiveCount }} Today's Inactive Customer
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Requested Customer</p>
                                        <h4 class="card-title">{{ $customerRequestCount ?? '' }} /
                                             <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysCustomerRequestCount }} Today's Requested Customer
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Wallet -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Pending Wallet Request</p>
                                        <h4 class="card-title">{{ $totalPendingWalletCount ?? '' }} /
                                             <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysPendingWalletCount }} Today's Pending Wallet
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Processing Wallet -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats icon-primary card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-spinner"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Processing Wallet</p>
                                        <h4 class="card-title">{{ $totalProcessingWalletCount ?? '' }} /
                                             <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysProcessingWalletCount }} Today's Processing Wallet
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Wallet -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats icon-success card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Completed Wallet</p>
                                        <h4 class="card-title">{{ $totalCompletedWalletCount ?? '' }} /
                                             <span style="font-size: 0.8rem; color: #007bff;">
                                                {{ $todaysCompletedWalletCount }} Today's Completed Wallet
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title" id="recent-title">Recent Agents</div>
                            <div class="card-tools d-flex align-items-center" style="gap:8px;">
                                <select id="role-filter" class="form-control form-control-sm">
                                    <option value="3" selected>Agent</option>
                                    <option value="2">Incharge</option>
                                    <option value="customer">Customers</option>
                                </select>
                                <div class="input-group input-group-sm" style="width:400px;">
                                    <input type="text" id="agents-search" class="form-control"
                                        placeholder="Search name, phone, nid, address...">
                                    <button type="button" id="agents-search-clear" class="btn btn-outline-secondary"
                                        title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label for="per-page-select" class="me-2">Per Page:</label>
                                    <select id="per-page-select" class="form-select form-select-sm"
                                        style="width:auto; display:inline-block;">
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                        <option value="all">All</option>
                                    </select>
                                </div>
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
                                            <th>Address</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Approved</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody id="agents-table-body" class="text-center">
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
        (function() {
            const tbody = document.getElementById('agents-table-body');
            const roleSelect = document.getElementById('role-filter');
            const searchInput = document.getElementById('agents-search');
            const clearBtn = document.getElementById('agents-search-clear');
            const perPageSelect = document.getElementById('per-page-select');
            const paginationContainer = document.getElementById('pagination-container');
            const paginationInfo = document.getElementById('pagination-info');
            const titleEl = document.getElementById('recent-title');
            if (!tbody) return;

            let currentPage = 1;
            let debounceTimer = null;
            const debounce = (fn, delay = 300) => {
                return function(...args) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => fn.apply(this, args), delay);
                };
            };

            function updateTitleByRole(role) {
                if (role == '2') titleEl.textContent = 'Recent Incharge';
                else if (role == '3') titleEl.textContent = 'Recent Agents';
                else if (role == 'customer') titleEl.textContent = 'Recent Customers';
                else titleEl.textContent = 'Recent Users';
            }

            function updateTableHeaders(role) {
                const thead = document.querySelector('table thead');
                if (!thead) return;
                if (role === 'customer') {
                    thead.innerHTML = `
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
                            <th>Address</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Approved</th>
                            <th>Created By</th>
                        </tr>
                    `;
                } else {
                    thead.innerHTML = `
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Nid Number</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Approved</th>
                            <th>Created By</th>
                        </tr>
                    `;
                }
            }

            function fetchAgents(page = 1) {
                currentPage = page;
                const role = roleSelect ? roleSelect.value : '';
                const search = searchInput ? searchInput.value.trim() : '';
                const perPage = perPageSelect ? perPageSelect.value : '25';
                const params = new URLSearchParams();
                if (role) params.set('role', role);
                if (search) params.set('search', search);
                if (perPage !== 'all') params.set('per_page', perPage);
                params.set('page', page);
                // choose endpoint depending on selection
                let url;
                if (role === 'customer') {
                    // customers are on a separate endpoint
                    url = '{{ route('dashboard.customers-json') }}' + (params.toString() ? ('?' + params.toString()) : '');
                } else {
                    url = '{{ route('dashboard.agents-json') }}' + (params.toString() ? ('?' + params.toString()) : '');
                }

                // update headers for the current selection
                updateTableHeaders(role);

                fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw res;
                        return res.json();
                    })
                    .then(json => {
                        const data = json.data || [];
                        const pagination = json.pagination || {};
                        tbody.innerHTML = '';
                        if (!data.length) {
                            const tr = document.createElement('tr');
                            tr.innerHTML = '<td colspan="10">No records found.</td>';
                            tbody.appendChild(tr);
                            paginationContainer.innerHTML = '';
                            paginationInfo.innerHTML = '';
                            return;
                        }
                        data.forEach((u, idx) => {
                            const tr = document.createElement('tr');
                            const imgHtml = u.image ? `<img src="${u.image}" width="50" height="50" alt="img">` : '';
                            const statusText = (u.status == 1) ? 'Active' : 'Inactive';
                            const approvedText = (u.approved == 1) ? 'Approved' : 'Not Approved';
                            const rowNumber = ((pagination.current_page - 1) * pagination.per_page) + idx + 1;

                            if (role === 'customer') {
                                tr.innerHTML = `
                                    <td>${rowNumber}</td>
                                    <td>${imgHtml}</td>
                                    <td>${escapeHtml(u.name)}</td>
                                    <td>${escapeHtml(u.phone)}</td>
                                    <td>${escapeHtml(u.nid_number || 'N/A')}</td>
                                    <td>${escapeHtml(u.school_name || 'N/A')}</td>
                                    <td>${escapeHtml(u.teacher_name || 'N/A')}</td>
                                    <td>${escapeHtml(u.vehicle_type || 'N/A')}</td>
                                    <td>${escapeHtml(u.license_number || 'N/A')}</td>
                                    <td>${escapeHtml(u.address || 'N/A')}</td>
                                    <td>${escapeHtml(u.type || 'N/A')}</td>
                                    <td>${escapeHtml(statusText)}</td>
                                    <td>${escapeHtml(approvedText)}</td>
                                    <td>${escapeHtml(u.created_by || 'N/A')}</td>
                                `;
                            } else {
                                tr.innerHTML = `
                                    <td>${rowNumber}</td>
                                    <td>${imgHtml}</td>
                                    <td>${escapeHtml(u.name)}</td>
                                    <td>${escapeHtml(u.phone)}</td>
                                    <td>${escapeHtml(u.nid_number || '')}</td>
                                    <td>${escapeHtml(u.address || '')}</td>
                                    <td>${escapeHtml(u.balance ?? '')}</td>
                                    <td>${escapeHtml(statusText)}</td>
                                    <td>${escapeHtml(approvedText)}</td>
                                    <td>${escapeHtml(u.created_by || '')}</td>
                                `;
                            }

                            tbody.appendChild(tr);
                        });

                        // Update pagination info
                        const start = (pagination.current_page - 1) * pagination.per_page + 1;
                        const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
                        paginationInfo.innerHTML = `Showing ${start} to ${end} of ${pagination.total} entries`;

                        // Build pagination links
                        buildPagination(pagination);
                    })
                    .catch(err => {
                        console.error('Failed to load agents:', err);
                    });
            }

            function buildPagination(pagination) {
                paginationContainer.innerHTML = '';
                const perPage = perPageSelect.value;
                if (perPage === 'all' || pagination.last_page <= 1) {
                    return;
                }

                // Previous button
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${pagination.current_page <= 1 ? 'disabled' : ''}`;
                prevLi.innerHTML =
                    `<a class="page-link" href="#" onclick="window.fetchAgents(${pagination.current_page - 1}); return false;">Previous</a>`;
                paginationContainer.appendChild(prevLi);


                for (let i = 1; i <= pagination.last_page; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
                    li.innerHTML =
                        `<a class="page-link" href="#" onclick="window.fetchAgents(${i}); return false;">${i}</a>`;
                    paginationContainer.appendChild(li);
                }


                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${pagination.current_page >= pagination.last_page ? 'disabled' : ''}`;
                nextLi.innerHTML =
                    `<a class="page-link" href="#" onclick="window.fetchAgents(${pagination.current_page + 1}); return false;">Next</a>`;
                paginationContainer.appendChild(nextLi);
            }

            window.fetchAgents = fetchAgents;

            const debouncedFetch = debounce(() => {
                currentPage = 1;
                fetchAgents(1);
            }, 350);

            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    updateTitleByRole(this.value);
                    currentPage = 1;
                    fetchAgents(1);
                });
                updateTitleByRole(roleSelect.value);
            }

            if (searchInput) {
                searchInput.addEventListener('input', debouncedFetch);
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    currentPage = 1;
                    fetchAgents(1);
                });
            }

            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    currentPage = 1;
                    fetchAgents(1);
                });
            }

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
@endpush

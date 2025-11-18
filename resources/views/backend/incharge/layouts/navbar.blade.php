<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <nav
            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1" data-bs-toggle="modal" data-bs-target="#testModal">
                        <i class="fa fa-search search-icon"></i>
                    </button>
                </div>
                <input type="text" placeholder="Search ..." class="form-control" data-bs-toggle="modal" data-bs-target="#testModal" />
            </div>
        </nav>

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"
                   role="button" aria-expanded="false" aria-haspopup="true">
                    <i class="fa fa-search"></i>
                </a>
                <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                        <div class="input-group">
                            <input type="text" placeholder="Search ..." class="form-control"/>
                        </div>
                    </form>
                </ul>
            </li>

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                   aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="{{ asset('backend/images/user/'.Auth::user()->image ?? '') }}" alt="..."
                             class="avatar-img rounded-circle"/>
                    </div>
                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold">{{ auth()->user()->name ?? ''}}</span>
                                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="{{ asset('backend/images/user/'.Auth::user()->image ?? '') }}"
                                         alt="image"
                                         class="avatar-img rounded"/>
                                </div>
                                <div class="u-text">
                                    <h4>{{auth()->user()->name ?? ''}}</h4>
                                    <p class="text-muted">{{auth()->user()->email ?? ''}}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('profile.edit')  }}">My Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                            <form action="{{ route('logout') }}" id="logout-form" method="post"
                                  class="d-none">@csrf</form>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="modal fade " id="testModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #f2f2f7;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search here</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="searchForm" action="" method="post">
                    @csrf
                    <div class="row" style="margin-bottom: -20px">

                        <div class="col-md-6 mx-auto">
                            <select id="search" name="search_type" class="c-select form-control" style="margin-bottom: 30px;" required>
                                <option class="text-start" selected disabled>------</option>
                                <option class="text-start" value="1">Client</option>
                                <option class="text-start" value="2">Director</option>
                                <option class="text-start" value="3">Scheme</option>
                                <option class="text-start" value="4">Member</option>
                                <option class="text-start" value="5">Provident</option>
                                <option class="text-start" value="6">Monthly Collection</option>
                                <option class="text-start" value="7">Withdraw</option>
                                <option class="text-start" value="8">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div id="clientSearch" style="display: none;">
                              <input type="text" name="client_search" class="form-control text-start" style="margin-left: 127px" placeholder="Client Name / NID / Client ID.">
                                <br>
                            </div>
                            <div id="directorSearch" style="display: none;">
                                <input name="director_search" class="form-control text-start" style="margin-left: 127px" placeholder="Director Name / NID.">
                                <br>
                            </div>
                            <div id="schemeSearch" style="display: none;">
                                <input name="scheme_search" class="form-control text-start" style="margin-left: 127px" placeholder="Scheme Name">
                                <br>
                            </div>
                            <div id="memberSearch" style="display: none;">
                                <input name="member_search" class="form-control text-start" style="margin-left: 127px" placeholder="Account Name / Account No.">
                                <br>
                            </div>
                            <div id="providentSearch" style="display: none;">
                                <input name="provident_search" class="form-control text-start" style="margin-left: 127px" placeholder="Member Name">
                                <br>
                            </div>
                            <div id="collectionSearch" style="display: none;">
                                <input name="collection_search" class="form-control text-start" style="margin-left: 127px" placeholder="Account Name.">
                                <br />
                            </div>
                            <div id="withdrawSearch" style="display: none;">
                                <input name="withdraw_search" class="form-control text-start" style="margin-left: 127px" placeholder="Account Name / Number.">
                                <br />
                            </div>
                            <div id="userSearch" style="display: none;">
                                <input name="user_search" class="form-control text-start" style="margin-left: 127px" placeholder="User Name / Email / Role.">
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal"
                                class="btn btn-sm btn_small btn-dark mr-2 float-right mt-3">
                            Close
                        </button>
                        <button type="submit"
                                class="btn btn-sm btn_small btn-primary mr-2 float-right mt-3">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#search').change(function() {
                $('#clientSearch').hide();
                $('#directorSearch').hide();
                $('#schemeSearch').hide();
                $('#memberSearch').hide();
                $('#providentSearch').hide();
                $('#collectionSearch').hide();
                $('#withdrawSearch').hide();
                $('#userSearch').hide();


                var selectedValue = $(this).val();
                if (selectedValue === '1') {
                    $('#clientSearch').show();
                }
                else if (selectedValue === '2') {
                    $('#directorSearch').show();
                }
                else if (selectedValue === '3') {
                    $('#schemeSearch').show();
                }
                else if (selectedValue === '4') {
                    $('#memberSearch').show();
                }
                else if (selectedValue === '5') {
                    $('#providentSearch').show();
                }
                else if (selectedValue === '6') {
                    $('#collectionSearch').show();
                }
                else if (selectedValue === '7') {
                    $('#withdrawSearch').show();
                }
                else if (selectedValue === '8') {
                    $('#userSearch').show();
                }
            });
        });
    </script>
@endpush


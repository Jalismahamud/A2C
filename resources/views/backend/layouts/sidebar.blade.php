<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">

            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('backend/images/banner/' . $company_logo) }}" alt="navbar brand" class="navbar-brand"
                    height="50" />
                <span style="margin-left:5px; color:white;">{{ $company_name }}</span>
            </a>

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item active">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#base"
                        aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}">
                        <i class="fas fa-user"></i>
                        <p>Users</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('users.*') ? 'show' : '' }}" id="base">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <a href="{{ route('users.index') }}">
                                    <span class="sub-item">Admin List</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('users.agent-list') ? 'active' : '' }}">
                                <a href="{{ route('users.agent-list') }}">
                                    <span class="sub-item">Agent List</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('users.customer-list') ? 'active' : '' }}">
                                <a href="{{ route('users.customer-list') }}">
                                    <span class="sub-item">Customer List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->routeIs('withdraw.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#withdraw"
                        aria-expanded="{{ request()->routeIs('withdraw.*') ? 'true' : 'false' }}">
                        <i class="fas fa-user"></i>
                        <p>Withdraw Requests</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('withdraw.*') ? 'show' : '' }}" id="withdraw">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('withdraw.pendinglist') ? 'active' : '' }}">
                                <a href="{{ route('withdraw.pendinglist') }}">
                                    <span class="sub-item">Pending Request</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('withdraw.approvelist') ? 'active' : '' }}">
                                <a href="{{ route('withdraw.approvelist') }}">
                                    <span class="sub-item">Approved Request</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->is('banner*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#banner"
                        aria-expanded="{{ request()->is('banner*') ? 'true' : 'false' }}">
                        <i class="fas fa-sliders-h"></i>
                        <p>Banner</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('banner*') ? 'show' : '' }}" id="banner">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('banner.index') ? 'active' : '' }}">
                                <a href="{{ route('banner.index') }}">
                                    <span class="sub-item">Banner List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('video-url*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#url"
                        aria-expanded="{{ request()->is('video-url*') ? 'true' : 'false' }}">
                        <i class="fas fa-link"></i>
                        <p>Video Url</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('video-url*') ? 'show' : '' }}" id="url">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('video-url.index') ? 'active' : '' }}">
                                <a href="{{ route('video-url.index') }}">
                                    <span class="sub-item">Url List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('mission*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#mission"
                        aria-expanded="{{ request()->is('mission*') ? 'true' : 'false' }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Mission </p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mission*') ? 'show' : '' }}" id="mission">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('mission.edit') ? 'active' : '' }}">
                                <a href="{{ url('mission/1/edit') }}">
                                    <span class="sub-item">Update</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('vision*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#vision"
                        aria-expanded="{{ request()->is('vision*') ? 'true' : 'false' }}">
                        <i class="fas fa-low-vision"></i>
                        <p> Vision</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('vision*') ? 'show' : '' }}" id="vision">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('vision.edit') ? 'active' : '' }}">
                                <a href="{{ url('vision/1/edit') }}">
                                    <span class="sub-item">Update</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('terms-and-condition*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#terms"
                        aria-expanded="{{ request()->is('terms-and-condition*') ? 'true' : 'false' }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Terms and Condition</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('terms-and-condition*') ? 'show' : '' }}" id="terms">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('terms-and-condition.edit') ? 'active' : '' }}">
                                <a href="{{ url('terms-and-condition/1/edit') }}">
                                    <span class="sub-item">Update</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('privacy-policy*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#terms2"
                        aria-expanded="{{ request()->is('privacy-policy*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-lock"></i>
                        <p>Privacy Policy Update</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('privacy-policy*') ? 'show' : '' }}" id="terms2">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('privacy-policy.edit') ? 'active' : '' }}">
                                <a href="{{ url('privacy-policy/1/edit') }}">
                                    <span class="sub-item">Privacy Policy Update</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->is('about-us*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#about"
                        aria-expanded="{{ request()->is('about-us*') ? 'true' : 'false' }}">
                        <i class="fas fa-address-card"></i>
                        <p>About Us</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('about-us*') ? 'show' : '' }}" id="about">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('about-us.edit') ? 'active' : '' }}">
                                <a href="{{ url('about-us/1/edit') }}">
                                    <span class="sub-item">Update</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->is('posts*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#posts"
                        aria-expanded="{{ request()->is('posts*') ? 'true' : 'false' }}">
                        <i class="fas fa-address-card"></i>
                        <p>Post Section</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('posts.index') || request()->routeIs('posts.request_list') ? 'show' : '' }}"
                        id="posts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('posts.index') ? 'active' : '' }}">
                                <a href="{{ route('posts.index') }}">
                                    <span class="sub-item">List</span>
                                </a>
                            </li>

                            <li class="{{ request()->routeIs('posts.request_list') ? 'active' : '' }}">
                                <a href="{{ route('posts.request_list') }}">
                                    <span class="sub-item">Request List</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->is('setting*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#setting"
                        aria-expanded="{{ request()->is('setting*') ? 'true' : 'false' }}">
                        <i class="fas fa-wrench"></i>
                        <p>Setting</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('setting.index') || request()->routeIs('setting.edit') ? 'show' : '' }}"
                        id="setting">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('setting.index') ? 'active' : '' }}">
                                <a href="{{ route('setting.index') }}">
                                    <span class="sub-item">Setting Item</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

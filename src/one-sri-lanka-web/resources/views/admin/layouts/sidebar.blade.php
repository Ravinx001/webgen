<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navigation</h5>

                <div>
                    <button type="button"
                        class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button"
                        class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->

        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" id="navbar-nav" data-nav-type="accordion">

                <!-- Section Header -->
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <!-- Simple Link -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="ph-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Link with Description -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="ph-chart-line"></i>
                        <span>
                            Analytics
                            <span class="d-block fw-normal opacity-50">View reports</span>
                        </span>
                    </a>
                </li>

                <!-- Link with Badge -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="ph-bell"></i>
                        <span>Notifications</span>
                        <span class="badge bg-primary align-self-center rounded-pill ms-auto">5</span>
                    </a>
                </li>

                <!-- Submenu -->
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-folder"></i>
                        <span>Projects</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="#" class="nav-link">All Projects</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Active</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Completed</a></li>
                    </ul>
                </li>

                <!-- Nested Submenu -->
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-users"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item"><a href="#" class="nav-link">All Users</a></li>
                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link">Roles</a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item"><a href="#" class="nav-link">Admin</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Editor</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Viewer</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="#" class="nav-link">Permissions</a></li>
                    </ul>
                </li>

                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-clipboard-text"></i> <!-- complaints icon -->
                        <span>Complaints</span>
                        <span class="badge bg-danger ms-auto">12</span> <!-- total complaints -->
                    </a>

                    <ul class="nav-group-sub collapse">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                All Complaints
                                <span class="badge bg-primary ms-auto">12</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Pending
                                <span class="badge bg-warning ms-auto">5</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                Resolved
                                <span class="badge bg-success ms-auto">7</span>
                            </a>
                        </li>

                        <li class="nav-item nav-item-submenu">
                            <a href="" class="nav-link">
                                Common Complaints
                                <span class="badge bg-info ms-auto">+</span>
                            </a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item">
                                    <a href="{{ route('admin.common-complaints.create') }}" class="nav-link">
                                        Create Complaint Structure
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.common-complaints.index') }}" class="nav-link">
                                        Manage Complaints Structures
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item nav-item-submenu">
                            <a href="#" class="nav-link">
                                Complaint Categories
                                <span class="badge bg-secondary ms-auto">+</span>
                            </a>
                            <ul class="nav-group-sub collapse">
                                <li class="nav-item">
                                    <a href="{{ route('admin.complaint-category.create') }}" class="nav-link">
                                        Add New Category
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.complaint-category.index') }}" class="nav-link">
                                        Manage Categories
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>

                <!-- Disabled Link -->
                <li class="nav-item">
                    <a href="#" class="nav-link disabled">
                        <i class="ph-lock"></i>
                        <span>Restricted Area</span>
                        <span class="badge bg-transparent align-self-center ms-auto">Coming soon</span>
                    </a>
                </li>

                <!-- Divider -->
                <li class="nav-item-divider"></li>

                <!-- Another Section -->
                <li class="nav-item-header">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Tools</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="ph-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->
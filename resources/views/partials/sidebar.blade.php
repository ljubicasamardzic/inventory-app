<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">InventoryApp</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
 
        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @can('viewAny', \App\Models\User::class)
                    <li class="nav-item">
                        <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Employees
                            </p>
                        </a>
                    </li>
                @endcan
               
                @can('viewAny', \App\Models\Equipment::class)
                    <li class="nav-item">
                        <a href="/equipment" class="nav-link {{ request()->is('equipment*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-laptop-code"></i>
                            <p>
                                Equipment
                            </p>
                        </a>
                    </li>
                @endcan

                @can('viewAny', \App\Models\Document::class)
                    <li class="nav-item">
                        <a href="/documents" class="nav-link {{ request()->is('documents*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-paperclip"></i>
                            <p>
                                Documents
                            </p>
                        </a>
                    </li>
                @endcan
                @can('reports_index', App\Models\Equipment::class)
                    <li class="nav-item">
                        <a href="/reports" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Reports
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

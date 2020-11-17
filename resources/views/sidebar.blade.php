<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Kategori
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('koleksi') }}" class="nav-link {{ Route::currentRouteName() == 'koleksi' ? 'active' : '' }}">
                                <i class="fas fa-book nav-icon"></i>
                                <p>Koleksi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pinjaman') }}" class="nav-link {{ Route::currentRouteName() == 'pinjaman' ? 'active' : '' }}">
                                <i class="fas fa-truck-loading nav-icon"></i>
                                <p>Pinjaman</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kunjungan') }}" class="nav-link {{ Route::currentRouteName() == 'kunjungan' ? 'active' : '' }}">
                                <i class="fas fa-chart-line nav-icon"></i>
                                <p>Kunjungan</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

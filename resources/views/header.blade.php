<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img src="{{ asset('user.png') }}" class="img-circle" width="30" alt="User Image"> User
            </a>
            <div class="dropdown-menu dropdown-menu-lg">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{ asset('user.png') }}" class="img-circle" width="100" alt="User Image">
                        <div class="media-body">
                            <h3 class="dropdown-item-title justify-content-center">
                                User
                                {{-- Auth::user()->name --}}
                            </h3>
                        </div>
                    </div>
                </a>
                <a href="#" class="dropdown-item">
                    Logout
                </a>
                <div class="dropdown-divider"></div>
                {{-- <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form> --}}
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

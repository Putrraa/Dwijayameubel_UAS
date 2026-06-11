<nav class="navbar navbar-expand-md navbar-dark py-3" style="background-color: #3b5d50;" aria-label="Furni navigation bar">
    <div class="container">

        <a class="navbar-brand fw-bold fs-3" href="#">
            Dwijaya Mebel<span class="text-warning">.</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link px-3 {{ Request::is('barang*') ? 'active fw-bold' : '' }}" href="{{ route('barang.index') }}">
                        Barang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 {{ Request::is('laporan*') ? 'active fw-bold' : '' }}" href="{{ route('laporan.index') }}">
                        Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 {{ Request::is('datpen*') ? 'active fw-bold' : '' }}" href="{{ route('datpen.index') }}">
                        Data Pengguna
                    </a>
                </li>

                @auth
                <li class="nav-item dropdown ms-md-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center bg-white bg-opacity-10 rounded-pill ps-2 pe-3 py-1" 
                       href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <span class="text-dark fw-bold" style="font-size: 0.8rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <span class="text-white small fw-medium">{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item py-2 text-danger" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item ms-md-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm rounded-pill px-4">Login</a>
                </li>
                @endauth
            </ul>

        </div>
    </div>
</nav>
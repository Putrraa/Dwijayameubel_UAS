<nav class="custom-navbar navbar navbar-expand-md navbar-dark" aria-label="Furni navigation bar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('customer.index') }}">Dwijaya Meubel<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                <li class="nav-item {{ Request::is('customer') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('customer.index') }}">Home</a>
                </li>
                <li class="nav-item {{ Request::is('shop') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('customer.shop') }}">Shop</a>
                </li>
                <li class="nav-item {{ Request::is('custom') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('customer.custom') }}">Custom</a>
                </li>
                <li class="nav-item {{ Request::is('about') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('customer.about') }}">About us</a>
                </li>
                <li class="nav-item {{ Request::is('contact') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('customer.contact') }}">Contact us</a>
                </li>
            </ul>

            <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-lg-5 align-items-center">
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
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('riwayat.pembelian') }}">
                                <i class="bi bi-clock-history me-2"></i> Riwayat Pembelian
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
                    <li class="nav-item">
                        <a class="nav-link btn btn-white-outline py-1 px-3 me-2" href="{{ route('login') }}" style="border-radius: 20px;">Login</a>
                    </li>
                @endauth

                <li class="ms-3">
                    <a class="nav-link" href="{{ route('customer.checkout') }}">
                        <img src="{{ asset('template_customer/images/cart.svg') }}" width="24">
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
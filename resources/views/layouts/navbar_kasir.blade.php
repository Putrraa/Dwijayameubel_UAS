<nav class="navbar navbar-expand-md navbar-dark py-3"
     style="background-color: #3b5d50;">

    <div class="container">

        {{-- LOGO --}}
        <a class="navbar-brand fw-bold fs-3"
           href="{{ route('kasir.index') }}">

            Dwijaya Mebel<span class="text-warning">.</span>

        </a>

        {{-- TOGGLE --}}
        <button class="navbar-toggler border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarKasir">

            <span class="navbar-toggler-icon"></span>

        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse"
             id="navbarKasir">

            <ul class="navbar-nav ms-auto align-items-center gap-2">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a class="nav-link px-3 {{ Request::is('custom-order*') ? 'active fw-bold' : '' }}"
                       href="{{ route('kasir.index') }}">
                        Custom Order
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 {{ Request::is('data-pesanan*') ? 'active fw-bold' : '' }}" href="{{ route('kasir.pesanan') }}">
                        Data Pesanan
                    </a>
                </li>

                {{-- PROFILE --}}
                @auth

                <li class="nav-item dropdown ms-md-3">

                    <a class="nav-link dropdown-toggle d-flex align-items-center
                              bg-white bg-opacity-10 rounded-pill
                              ps-2 pe-3 py-1"
                       href="#"
                       id="profileDropdown"
                       role="button"
                       data-bs-toggle="dropdown">

                        {{-- ICON --}}
                        <div class="bg-warning rounded-circle
                                    d-flex align-items-center
                                    justify-content-center me-2"
                             style="width: 32px; height: 32px;">

                            <span class="text-dark fw-bold"
                                  style="font-size: 0.8rem;">

                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                            </span>

                        </div>

                        {{-- NAME --}}
                        <span class="text-white small fw-medium">

                            {{ Auth::user()->name }}

                        </span>

                    </a>

                    {{-- DROPDOWN --}}
                    <ul class="dropdown-menu dropdown-menu-end
                               shadow border-0 mt-2">

                        <li>

                            <a class="dropdown-item py-2"
                               href="{{ route('profile') }}">

                                Profile

                            </a>

                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>

                            <form method="POST"
                                  action="{{ route('logout') }}">

                                @csrf

                                <button class="dropdown-item py-2 text-danger"
                                        type="submit">

                                    Logout

                                </button>

                            </form>

                        </li>

                    </ul>

                </li>

                @endauth

            </ul>

        </div>

    </div>

</nav>
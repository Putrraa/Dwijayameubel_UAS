@extends('layouts.auth')
@section('title', 'Login')
@section('content')
<section class="py-3 py-md-5 py-xl-8" style="background-color: #3d5a4a; min-height: 100vh; display: flex; align-items: center;">
  <div class="container">
    <div class="row gy-4 align-items-center">
      {{-- KIRI: Branding --}}
      <div class="col-12 col-md-6 col-xl-7">
        <div class="d-flex justify-content-center text-white">
          <div class="col-12 col-xl-9">
            <h1 class="display-4 fw-bold mb-3">Dwijaya Mebel.</h1>
            <hr class="mb-4" style="width: 100px; border: 3px solid #ffc107; opacity: 1;">
            <h2 class="h1 mb-4">Welcome Back</h2>
            <p class="lead mb-5 opacity-75">Silakan login untuk mengelola data produk, kategori, dan laporan pesanan dengan mudah.</p>
            <div class="text-start">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ffc107" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
      {{-- KANAN: Form Login --}}
      <div class="col-12 col-md-6 col-xl-5">
        <div class="card border-0 rounded-4 shadow-lg">
          <div class="card-body p-4 p-md-5">

            {{-- Tombol Kembali ke Beranda --}}
            <div class="mb-4">
              <a href="{{ route('customer.index') }}"
                class="d-inline-flex align-items-center gap-2 text-decoration-none fw-semibold"
                style="
                  font-size: 0.85rem;
                  color: #3d5a4a;
                  background-color: #eaf3de;
                  border: 1.5px solid #b4d88a;
                  padding: 7px 16px;
                  border-radius: 8px;
                  transition: background-color 0.2s, border-color 0.2s;
                "
                onmouseover="this.style.backgroundColor='#d5eabb'; this.style.borderColor='#639922';"
                onmouseout="this.style.backgroundColor='#eaf3de'; this.style.borderColor='#b4d88a';">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Kembali ke Beranda
              </a>
            </div>

            <div class="text-center mb-4">
               <h3 class="fw-bold" style="color: #3d5a4a;">Masuk Ke Akun</h3>
               <p class="text-secondary">Masukkan email dan password Anda</p>
            </div>
            <form action="{{ route('login') }}" method="POST">
              @csrf
              @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                   <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                   </ul>
                </div>
              @endif
              <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required value="{{ old('email') }}">
                <label for="email" class="text-secondary">Alamat Email</label>
              </div>
              <div class="form-floating mb-4">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                <label for="password" class="text-secondary">Password</label>
              </div>
              <div class="d-grid mb-4">
                <button type="submit" class="btn btn-lg text-white fw-bold shadow-sm" style="background-color: #3d5a4a; border-radius: 10px;">
                  Login Sekarang
                </button>
              </div>
            </form>
            <div class="text-center">
              <p class="mb-0 text-secondary">Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #3d5a4a;">Daftar Disini</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
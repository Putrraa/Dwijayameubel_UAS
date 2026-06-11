@extends('layouts.auth')
@section('title', 'Registrasi Akun')

@section('content')
<!-- Background disamakan dengan Login (Hijau Gelap) -->
<section class="py-3 py-md-5 py-xl-8" style="background-color: #3d5a4a; min-height: 100vh; display: flex; align-items: center;">
  <div class="container">
    <div class="row gy-4 align-items-center">
      
      {{-- KIRI: Branding --}}
      <div class="col-12 col-md-6 col-xl-7">
        <div class="d-flex justify-content-center text-white">
          <div class="col-12 col-xl-9">
            <h1 class="display-4 fw-bold mb-3">Dwijaya Mebel.</h1>
            <hr class="mb-4" style="width: 100px; border: 3px solid #ffc107; opacity: 1;">
            <h2 class="h1 mb-4">Gabung bersama kami!</h2>
            <p class="lead mb-5 opacity-75">Daftarkan akun admin Anda untuk mulai mengelola katalog furnitur klasik dan modern secara profesional.</p>
            
            <div class="text-start">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#ffc107" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      {{-- KANAN: Form Register --}}
      <div class="col-12 col-md-6 col-xl-5">
        <div class="card border-0 rounded-4 shadow-lg">
          <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
              <h3 class="fw-bold" style="color: #3d5a4a;">Registrasi Akun</h3>
              <p class="text-secondary">Buat akun admin baru</p>
            </div>

            <!-- Menampilkan Error Validasi -->
            @if ($errors->any())
              <div class="alert alert-danger py-2 small">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
              @csrf
              <div class="row gy-3">
                <div class="col-12">
                  <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                    <label for="name" class="text-secondary">Nama Lengkap</label>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating mb-2">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                    <label for="email" class="text-secondary">Alamat Email</label>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating mb-2">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <label for="password" class="text-secondary">Password</label>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" required>
                    <label for="password_confirmation" class="text-secondary">Konfirmasi Password</label>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-grid mt-2">
                    <button type="submit" class="btn btn-lg text-white fw-bold shadow-sm" style="background-color: #3d5a4a; border-radius: 10px;">
                      Daftar Sekarang
                    </button>
                  </div>
                </div>
              </div>
            </form>

            <div class="row mt-4">
              <div class="col-12">
                <p class="mb-0 text-secondary text-center">Sudah punya akun? 
                  <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #3d5a4a;">Masuk</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
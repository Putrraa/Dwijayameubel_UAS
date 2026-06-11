@extends('layouts.master_kasir') 

@section('content')
<style>
    .profile-container {
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .profile-card-custom {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        border: none;
    }

    /* Sisi Kiri: Sekarang menggunakan warna Hijau Admin */
    .account-sidebar {
        background: linear-gradient(135deg, #3b5d50 0%, #2d463d 100%);
        padding: 40px 20px;
        color: white; /* Tulisan di sisi kiri jadi putih agar terbaca */
    }

    /* Logo Inisial: Background Kuning, Teks Hitam */
    .avatar-kasir {
        width: 110px;
        height: 110px;
        background: #f9bf29; /* Warna Kuning Dwijaya */
        color: #000000; /* Teks Hitam */
        font-size: 45px;
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 10px 15px rgba(0,0,0,0.2);
    }

    /* Penyesuaian teks nama dan role di background hijau */
    .account-sidebar h4 {
        color: #ffffff !important;
    }
    
    .account-sidebar .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    /* Tombol Dashboard di sisi kiri agar lebih kontras */
    .btn-side-dashboard {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        transition: all 0.3s;
    }

    .btn-side-dashboard:hover {
        background-color: #506f4f;
        color: #2d463d;
        border-color: #2f5b34;
    }

    /* Bagian Kanan tetap bersih */
    .form-section {
        padding: 40px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .form-control-custom {
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 10px;
        padding: 12px 15px;
    }

    .btn-update-pw {
        background-color: #f9bf29;
        color: #2d463d;
        border: none;
        font-weight: 700;
        border-radius: 10px;
        padding: 15px;
    }
</style>

<div class="container profile-container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            
            <div class="card profile-card-custom shadow-lg">
                <div class="row g-0">
                    
                    <!-- Sidebar Profil (Sisi Kiri) -->
                    <div class="col-md-4 account-sidebar text-center">
                        <div class="avatar-kasir">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-muted small mb-4">Kasir Dwijaya Mebel</p>
                        
                        <hr class="mx-4 opacity-25" style="border-color: white;">
                        
                        <div class="mt-4 px-3">
                            <a href="{{ route('barang.index') }}" class="btn btn-side-dashboard w-100 py-2 fw-bold">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Area Form -->
                    <div class="col-md-8 form-section">
                        <!-- Alert Success/Error (Opsional jika ada session) -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Informasi Akun -->
                        <div class="section-title">
                            <i class="bi bi-person-circle"></i> Informasi Akun
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 custom-input-group">
                                <label>Username</label>
                                <input type="text" class="form-control-custom w-100" value="{{ Auth::user()->name }}" readonly disabled>
                            </div>
                            <div class="col-md-6 custom-input-group">
                                <label>Email Address</label>
                                <input type="text" class="form-control-custom w-100" value="{{ Auth::user()->email }}" readonly disabled>
                            </div>
                        </div>

                        <hr class="my-4 opacity-25">

                        <!-- Form Ubah Password -->
                        <div class="section-title">
                            <i class="bi bi-shield-lock"></i> Keamanan Akun
                        </div>

                        <form action="#" method="POST"> <!-- Sesuaikan route update password kamu -->
                            @csrf
                            <div class="custom-input-group">
                                <label>Password Lama</label>
                                <input type="password" name="current_password" class="form-control-custom w-100" placeholder="••••••••" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 custom-input-group">
                                    <label>Password Baru</label>
                                    <input type="password" name="new_password" class="form-control-custom w-100" placeholder="••••••••" required>
                                </div>
                                <div class="col-md-6 custom-input-group">
                                    <label>Konfirmasi Password Baru</label>
                                    <input type="password" name="new_password_confirmation" class="form-control-custom w-100" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-update-pw w-100 shadow-sm">
                                    <i class="bi bi-check2-circle me-2"></i> Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <p class="text-center mt-4 text-muted small">
                Terakhir login: <span class="fw-bold">{{ date('d M Y, H:i') }}</span>
            </p>
            
        </div>
    </div>
</div>
@endsection
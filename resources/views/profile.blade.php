@extends('layouts.master_admin') 

@section('content')

<div class="profile-page-wrapper">
    <!-- Dekorasi Latar Belakang -->
    <div class="bg-circle" style="width: 400px; height: 400px; top: -100px; right: -100px;"></div>
    <div class="bg-circle" style="width: 300px; height: 300px; bottom: -50px; left: -50px;"></div>

    <div class="card profile-card shadow-lg border-0">
        <div class="row g-0 w-100">
            <!-- Sisi Kiri (Visual Identity) -->
            <div class="col-md-5 profile-side-visual">
                <div class="avatar-massive text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h3 class="fw-bold mb-1">{{ Auth::user()->name }}</h3>
                <p class="text-white-50 mb-4">Administrator System</p>
                
                <div class="w-100 border-top border-white-50 pt-4 opacity-75">
                    <div class="d-flex justify-content-between align-items-center small mb-2">
                        <span>Status Akun</span>
                        <span class="badge bg-success" style="padding: 5px 10px;">Aktif</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Role</span>
                        <span>Administrator</span>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan (Data Detail) -->
            <div class="col-md-7 profile-side-content">
                <h4 class="fw-bold mb-4" style="color: #3b5d50;">Detail Informasi</h4>
                
                <div class="info-group">
                    <label class="info-label">Nama Lengkap Admin</label>
                    <div class="info-value">
                        <i class="bi bi-person-circle me-3 text-muted"></i>
                        {{ Auth::user()->name }}
                    </div>
                </div>

                <div class="info-group">
                    <label class="info-label">Alamat Email Resmi</label>
                    <div class="info-value">
                        <i class="bi bi-envelope-fill me-3 text-muted"></i>
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{ route('barang.index') }}" class="btn-action">
                        <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Dashboard
                    </a>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted" style="font-size: 10px;">Dwijaya Mebel &bull; Security Verified</small>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset('template_admin/css/style.css') }}">
@endpush
@endsection
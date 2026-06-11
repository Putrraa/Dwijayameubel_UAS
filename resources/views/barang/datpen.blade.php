@extends('layouts.master_admin')

@section('title', 'Data Pengguna')

@section('content')

<div class="container py-4">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- VALIDATION ERROR --}}
    @if($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Data Pengguna</h3>
                    <p class="text-muted mb-0">Daftar seluruh pengguna aplikasi</p>
                </div>

                <button class="btn btn-dark rounded-pill px-4"
                        data-bs-toggle="modal"
                        style="background-color: #3d5a4a;"
                        data-bs-target="#tambahUserModal">
                    + Tambah Pengguna
                </button>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal</th>
                            <th width="170px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td class="fw-semibold">{{ $user->name }}</td>

                                <td>{{ $user->email }}</td>

                                <td>
                                    @if($user->role == 'admin')
                                        <span>Admin</span>
                                    @elseif($user->role == 'kasir')
                                        <span>Kasir</span>
                                    @else
                                        <span>Customer</span>
                                    @endif
                                </td>

                                <td>{{ $user->created_at->format('d M Y') }}</td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <button type="button"
                                                class="btn btn-aksi-edit btn-sm rounded-pill px-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal{{ $user->id }}">
                                            Edit
                                        </button>

                                        @if(auth()->id() != $user->id)
                                            <form action="{{ route('datpen.destroy', $user->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-aksi-hapus btn-sm rounded-pill px-3">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            
                                        @endif

                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT USER --}}
                            <div class="modal fade"
                                 id="editUserModal{{ $user->id }}"
                                 tabindex="-1"
                                 aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">

                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Edit Pengguna</h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                            </button>
                                        </div>

                                        <div class="modal-body p-4">
                                            <form action="{{ route('datpen.update', $user->id) }}"
                                                  method="POST">

                                                @csrf
                                                @method('PUT')

                                                {{-- NAMA --}}
                                                <div class="mb-3">
                                                    <label class="fw-semibold mb-2">Nama</label>
                                                    <input type="text"
                                                           name="name"
                                                           class="form-control rounded-3"
                                                           value="{{ old('name', $user->name) }}"
                                                           required>
                                                </div>

                                                {{-- EMAIL --}}
                                                <div class="mb-3">
                                                    <label class="fw-semibold mb-2">Email</label>
                                                    <input type="email"
                                                           name="email"
                                                           class="form-control rounded-3"
                                                           value="{{ old('email', $user->email) }}"
                                                           required>
                                                </div>

                                                {{-- PASSWORD --}}
                                                <div class="mb-3">
                                                    <label class="fw-semibold mb-2">Password Baru</label>
                                                    <input type="password"
                                                           name="password"
                                                           class="form-control rounded-3"
                                                           placeholder="Kosongkan jika tidak ingin mengganti password">
                                                </div>

                                                {{-- ROLE --}}
                                                <div class="mb-4">
                                                    <label class="fw-semibold mb-2">Role</label>
                                                    <select name="role"
                                                            class="form-select rounded-3"
                                                            required>
                                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                            Admin
                                                        </option>
                                                        <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>
                                                            Kasir
                                                        </option>
                                                        <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>
                                                            Customer
                                                        </option>
                                                    </select>
                                                </div>

                                                <button type="submit"
                                                        class="btn btn-dark rounded-pill w-100 py-2">
                                                    Simpan Perubahan
                                                </button>

                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data pengguna
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>


{{-- ================= MODAL TAMBAH USER ================= --}}
<div class="modal fade"
     id="tambahUserModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Pengguna</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body p-4">
                <form action="{{ route('datpen.store') }}"
                      method="POST">

                    @csrf

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label class="fw-semibold mb-2">Nama</label>
                        <input type="text"
                               name="name"
                               class="form-control rounded-3"
                               value="{{ old('name') }}"
                               required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="fw-semibold mb-2">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control rounded-3"
                               value="{{ old('email') }}"
                               required>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label class="fw-semibold mb-2">Password</label>
                        <input type="password"
                               name="password"
                               class="form-control rounded-3"
                               required>
                    </div>

                    {{-- ROLE --}}
                    <div class="mb-4">
                        <label class="fw-semibold mb-2">Role</label>
                        <select name="role"
                                class="form-select rounded-3"
                                required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="btn btn-dark rounded-pill w-100 py-2">
                        Simpan Pengguna
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="{{ asset('template_admin/css/style.css') }}">
@endpush
@endsection
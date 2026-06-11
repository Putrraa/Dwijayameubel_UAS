@extends('layouts.master_kasir')

@section('title', 'Custom Order')

@section('content')

<div class="container py-5">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-5">

        <div>
            <h2 class="fw-bold mb-1">
                Custom Order
            </h2>

            <p class="text-muted mb-0">
                Kelola custom order customer
            </p>
        </div>

    </div>

    {{-- ALERT --}}
    @if(session('success'))

    <div class="alert alert-success rounded-4 border-0 shadow-sm">

        {{ session('success') }}

    </div>

    @endif

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Customer</th>
                            <th>Furniture</th>
                            <th>Kayu</th>
                            <th>Ukuran</th>
                            <th>Catatan</th>
                            <th>Gambar</th>
                            <th>Harga</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->user->name }}
                            </td>

                            <td>
                                {{ $item->jenis_furniture }}
                            </td>

                            <td>
                                {{ $item->jenis_kayu }}
                            </td>

                            <td>
                                {{ $item->ukuran }}
                            </td>

                            <td style="max-width: 200px">
                                {{ $item->catatan }}
                            </td>

                            {{-- GAMBAR --}}
                            <td>

                                @if($item->gambar)

                                    <img src="{{ asset('storage/' . $item->gambar) }}"
                                         width="90"
                                         class="rounded shadow-sm">

                                @else

                                    <span class="text-muted">
                                        Tidak ada
                                    </span>

                                @endif

                            </td>

                            {{-- FORM HARGA + STATUS --}}
                            <td colspan="2">

                                <form action="{{ route('custom.status', $item->id) }}"
                                      method="POST"
                                      class="d-flex gap-2 align-items-center">

                                    @csrf

                                    {{-- INPUT HARGA --}}
                                    <input type="text"
                                        name="estimasi_harga"
                                        class="form-control"
                                        placeholder="Input harga"
                                        value="{{ $item->estimasi_harga }}"
                                        inputmode="numeric"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        required>

                                    {{-- STATUS --}}
                                    <select name="status"
                                            class="form-select">

                                        <option value="pending"
                                            {{ $item->status == 'pending' ? 'selected' : '' }}>
                                            Menunggu
                                        </option>

                                        <option value="diproses"
                                            {{ $item->status == 'diproses' ? 'selected' : '' }}>
                                            Diproses
                                        </option>

                                        <option value="selesai"
                                            {{ $item->status == 'selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>

                                    </select>

                                    {{-- BUTTON --}}
                                    <button type="submit"
                                            class="btn btn-success">

                                        Simpan

                                    </button>

                                </form>

                                {{-- TAMPIL HARGA --}}
                                @if($item->estimasi_harga)

                                    <small class="text-success fw-bold d-block mt-2">

                                        Rp {{ number_format($item->estimasi_harga,0,',','.') }}

                                    </small>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="9" class="text-center text-muted py-4">

                                Belum ada custom order

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
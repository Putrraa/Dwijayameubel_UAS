@extends('layouts.master')

@section('title','Contact')

@section('hero')
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Contact</h1>
                    <p class="mb-4">
                        Kami menyediakan berbagai macam jenis meubel untuk kebutuhan rumah anda.
                    </p>
                    <p>
                        <a href="{{ route('customer.shop') }}" class="btn btn-secondary me-2">
                            Shop Now
                        </a>
                        <a href="{{ route('customer.index') }}" class="btn btn-white-outline">
                            Explore
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="untree_co-section">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="block">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8 pb-4">

                    <div class="row mb-5">

                        <div class="col-lg-4 mb-3">
                            <div class="service no-shadow align-items-center link horizontal d-flex active">
                                <div class="service-icon color-1 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    </svg>
                                </div>
                                <div class="service-contents">
                                    <p>Combong, Wanengpaten, Kec. Gampengrejo, Kabupaten Kediri</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="service no-shadow align-items-center link horizontal d-flex active">
                                <div class="service-icon color-1 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                                    </svg>
                                </div>
                                <div class="service-contents">
                                    <p>dwijayameubel@gmail.com</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="service no-shadow align-items-center link horizontal d-flex active">
                                <div class="service-icon color-1 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                    </svg>
                                </div>
                                <div class="service-contents">
                                    <p>+62 812 390 756 37</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ===== PETA LEAFLET ===== --}}
                    <div id="map" style="width: 100%; height: 320px; border-radius: 12px; overflow: hidden; margin-bottom: 16px; z-index: 0;"></div>

                    {{-- ===== TOMBOL BUKA DI GOOGLE MAPS ===== --}}
                    <div class="mb-4">
                        <a href="https://maps.app.goo.gl/4oz86WC9s4QgjCJy9?g_st=aw"
                           target="_blank"
                           class="btn btn-dark w-100 rounded-pill py-2"
                           style="font-size: 15px; font-weight: 600;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-map-fill me-2" viewBox="0 0 16 16" style="vertical-align: -2px;">
                                <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.598-.49L10.5 1.91 5.598.01a.5.5 0 0 0-.196 0l-5 1A.5.5 0 0 0 0 1.5v14a.5.5 0 0 0 .598.49l4.902-.98 4.902.98a.502.502 0 0 0 .196 0l5-1A.5.5 0 0 0 16 14.5V.5zM5 14.09V1.11l.5.1.5-.1V14.09l-.5-.09-.5.09zm5 .8V1.91l.5-.1.5.1v12.98l-.5.09-.5-.09z"/>
                            </svg>
                            Buka di Google Maps
                        </a>
                    </div>

                    {{-- ===== TOMBOL WHATSAPP ===== --}}
                    @php
                        $waText = urlencode('Halo Dwijaya Meubel, saya ingin bertanya tentang produk meubel.');
                    @endphp

                    <div class="mb-4">
                        <a href="https://wa.me/6281239075637?text={{ $waText }}"
                           target="_blank"
                           class="btn btn-success rounded-pill px-4">
                            Chat via WhatsApp
                        </a>
                    </div>

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="text-black" for="name">Nama</label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           id="name"
                                           value="{{ old('name') }}"
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-black" for="email">Email address</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   id="email"
                                   value="{{ old('email') }}"
                                   required>
                        </div>

                        <div class="form-group mb-5">
                            <label class="text-black" for="message">Message</label>
                            <textarea name="message"
                                      class="form-control"
                                      id="message"
                                      cols="30"
                                      rows="5"
                                      required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-hover-outline">
                            Send Message
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-7.745643610925027, 112.024023257671], 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    var marker = L.marker([-7.745643610925027, 112.024023257671]).addTo(map);
    marker.bindPopup('<strong>Dwijaya Meubel</strong><br>Combong, Wanengpaten,<br>Kec. Gampengrejo, Kab. Kediri').openPopup();
</script>

@endsection
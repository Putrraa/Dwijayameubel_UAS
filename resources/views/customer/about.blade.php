@extends('layouts.master')

@section('title','About Us')

@section('hero')
<div class="hero">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>About Us</h1>
                    <p class="mb-4">
                        Dwijaya Meubel adalah toko meubel yang menyediakan berbagai produk furniture
                        untuk kebutuhan rumah, kantor, dan ruangan impian Anda.
                    </p>
                    <p>
                        <a href="{{ route('customer.shop') }}" class="btn btn-secondary me-2">
                            Shop Now
                        </a>
                        <a href="{{ route('customer.contact') }}" class="btn btn-white-outline">
                            Contact Us
                        </a>
                    </p>
                </div>
            </div>

            <div class="col-lg-7">
                {{-- Bisa dikosongkan atau ditambahkan gambar hero jika mau --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

{{-- START ABOUT SECTION --}}
<div class="untree_co-section">
    <div class="container">
        <div class="row align-items-center justify-content-between">

            <div class="col-lg-6">
                <div class="img-wrap">
                    <img src="{{ asset('template_customer/images/why-choose-us-img.jpg') }}"
                         alt="Dwijaya Meubel"
                         class="img-fluid rounded-4">
                </div>
            </div>

            <div class="col-lg-5">
                <h2 class="section-title mb-4">
                    Tentang Dwijaya Meubel
                </h2>

                <p>
                    Dwijaya Meubel hadir sebagai solusi untuk memenuhi kebutuhan furniture rumah
                    dengan produk yang berkualitas, desain menarik, dan harga yang tetap terjangkau.
                </p>

                <p>
                    Kami menyediakan berbagai macam meubel seperti kursi, meja, lemari, rak,
                    hingga kebutuhan furniture custom yang dapat disesuaikan dengan keinginan pelanggan.
                </p>

                <p>
                    Dengan pelayanan yang ramah dan proses pemesanan yang mudah, Dwijaya Meubel
                    berkomitmen untuk memberikan pengalaman belanja meubel yang nyaman dan terpercaya.
                </p>

                <a href="{{ route('customer.shop') }}" class="btn btn-secondary mt-3">
                    Lihat Produk
                </a>
            </div>

        </div>
    </div>
</div>
{{-- END ABOUT SECTION --}}


{{-- START WHY CHOOSE US SECTION --}}
<div class="why-choose-section">
    <div class="container">
        <div class="row justify-content-between align-items-center">

            <div class="col-lg-6">
                <h2 class="section-title">
                    Kenapa Memilih Dwijaya Meubel?
                </h2>

                <p>
                    Kami selalu mengutamakan kualitas produk, kenyamanan pelanggan,
                    dan kemudahan dalam proses pemesanan.
                </p>

                <div class="row my-5">

                    <div class="col-6 col-md-6 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('template_customer/images/truck.svg') }}"
                                     alt="Pengiriman"
                                     class="img-fluid">
                            </div>
                            <h3>Pengiriman Mudah</h3>
                            <p>
                                Produk dapat dikirim dengan proses yang mudah dan cepat
                                sesuai pesanan pelanggan.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('template_customer/images/bag.svg') }}"
                                     alt="Belanja Mudah"
                                     class="img-fluid">
                            </div>
                            <h3>Mudah Berbelanja</h3>
                            <p>
                                Pelanggan dapat melihat produk, memilih barang, dan melakukan
                                pemesanan dengan lebih praktis.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('template_customer/images/support.svg') }}"
                                     alt="Support"
                                     class="img-fluid">
                            </div>
                            <h3>Konsultasi Produk</h3>
                            <p>
                                Kami siap membantu pelanggan dalam memilih produk meubel
                                yang sesuai dengan kebutuhan.
                            </p>
                        </div>
                    </div>

                    <div class="col-6 col-md-6 mb-4">
                        <div class="feature">
                            <div class="icon">
                                <img src="{{ asset('template_customer/images/return.svg') }}"
                                     alt="Custom Order"
                                     class="img-fluid">
                            </div>
                            <h3>Custom Order</h3>
                            <p>
                                Pelanggan dapat melakukan pemesanan furniture custom
                                sesuai ukuran, bahan, dan desain yang diinginkan.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-5">
                <div class="img-wrap">
                    <img src="{{ asset('template_customer/images/couch.png') }}"
                         alt="Produk Dwijaya Meubel"
                         class="img-fluid">
                </div>
            </div>

        </div>
    </div>
</div>
{{-- END WHY CHOOSE US SECTION --}}


{{-- START SERVICE SECTION --}}
<div class="untree_co-section">
    <div class="container">

        <div class="row mb-5">
            <div class="col-lg-6 mx-auto text-center">
                <h2 class="section-title">
                    Layanan Kami
                </h2>
                <p>
                    Dwijaya Meubel menyediakan layanan yang membantu pelanggan
                    mendapatkan furniture sesuai kebutuhan.
                </p>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-md-4 mb-4">
                <div class="feature text-center p-4 rounded-4 shadow-sm h-100">
                    <h3>Produk Meubel</h3>
                    <p>
                        Menyediakan berbagai produk meubel siap pakai untuk kebutuhan rumah
                        seperti meja, kursi, lemari, dan rak.
                    </p>
                    <a href="{{ route('customer.shop') }}" class="btn btn-secondary mt-2">
                        Lihat Produk
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-4">
                <div class="feature text-center p-4 rounded-4 shadow-sm h-100">
                    <h3>Custom Furniture</h3>
                    <p>
                        Pelanggan dapat memesan furniture sesuai desain, bahan, ukuran,
                        dan catatan khusus yang diinginkan.
                    </p>
                    <a href="{{ route('customer.custom') }}" class="btn btn-secondary mt-2">
                        Custom Order
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-4">
                <div class="feature text-center p-4 rounded-4 shadow-sm h-100">
                    <h3>Konsultasi</h3>
                    <p>
                        Kami siap membantu pelanggan memilih produk yang sesuai dengan
                        kebutuhan ruangan dan anggaran.
                    </p>
                    <a href="{{ route('customer.contact') }}" class="btn btn-secondary mt-2">
                        Hubungi Kami
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
{{-- END SERVICE SECTION --}}

@endsection
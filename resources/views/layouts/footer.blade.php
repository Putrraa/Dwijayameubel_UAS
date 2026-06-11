@php
    $waText = urlencode('Halo Dwijaya Meubel, saya ingin bertanya tentang produk meubel.');
@endphp

<footer class="footer-section" style="background: #f8f9f6; padding-top: 0;">

    {{-- WHATSAPP FULL WIDTH --}}
    <div style="width: 100%; background: linear-gradient(135deg, #3d5a4a, #2f4639); padding: 45px 0; margin-bottom: 60px;">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">

                <div class="mb-3 mb-md-0">
                    <h3 class="text-white fw-bold mb-2">
                        Butuh bantuan memilih meubel?
                    </h3>
                    <p class="text-white-50 mb-0">
                        Hubungi kami lewat WhatsApp untuk konsultasi produk, custom order, atau informasi pemesanan.
                    </p>
                </div>

                <a href="https://wa.me/6285707950850?text={{ $waText }}"
                   target="_blank"
                   rel="noopener"
                   class="btn rounded-pill px-4 py-2 fw-semibold"
                   style="background-color: #25D366; color: white;">
                    Chat WhatsApp
                </a>

            </div>
        </div>
    </div>

    <div class="container relative">

        <div class="row g-5 mb-5">

            <div class="col-lg-5">
                <div class="mb-4 footer-logo-wrap">
                    <a href="{{ route('customer.index') }}" class="footer-logo">
                        Dwijaya Meubel<span>.</span>
                    </a>
                </div>

                <p class="mb-4" style="max-width: 430px;">
                    Dwijaya Meubel menyediakan berbagai macam jenis meubel untuk kebutuhan rumah Anda,
                    mulai dari produk siap pakai hingga pesanan custom sesuai keinginan.
                </p>

                <div class="d-flex gap-2">
                    <a href="https://wa.me/6285707950850?text={{ $waText }}"
                       target="_blank"
                       rel="noopener"
                       class="btn btn-sm rounded-pill px-3"
                       style="background-color: #3d5a4a; color: white;">
                        WhatsApp
                    </a>

                    <a href="{{ route('customer.shop') }}"
                       class="btn btn-sm rounded-pill px-3"
                       style="border: 1px solid #3d5a4a; color: #3d5a4a; background: transparent;">
                        Lihat Produk
                    </a>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="row links-wrap">

                    <div class="col-6 col-sm-6 col-md-4">
                        <h6 class="fw-bold mb-3">Menu</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('customer.index') }}">Home</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('customer.shop') }}">Shop</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('customer.about') }}">About Us</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('customer.contact') }}">Contact Us</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-6 col-sm-6 col-md-4">
                        <h6 class="fw-bold mb-3">Layanan</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="{{ route('customer.shop') }}">Produk Meubel</a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('customer.custom') }}">Custom Order</a>
                            </li>
                            <li class="mb-2">
                                <a href="https://wa.me/6285707950850?text={{ $waText }}"
                                   target="_blank"
                                   rel="noopener">
                                    Konsultasi WhatsApp
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-12 col-sm-12 col-md-4">
                        <h6 class="fw-bold mb-3">Kontak</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                Combong, Wanengpaten, Kec. Gampengrejo, Kabupaten Kediri
                            </li>
                            <li class="mb-2">
                                <a href="mailto:dwijayameubel@gmail.com">
                                    dwijayameubel@gmail.com
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="https://wa.me/6285707950850?text={{ $waText }}"
                                   target="_blank"
                                   rel="noopener">
                                    +62 857 079 508 50
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>

        <div class="border-top pt-4 pb-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} Dwijaya Meubel. All Rights Reserved.
                    </p>
                </div>

                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        Furniture lokal berkualitas untuk rumah Anda.
                    </p>
                </div>
            </div>
        </div>

    </div>
</footer>
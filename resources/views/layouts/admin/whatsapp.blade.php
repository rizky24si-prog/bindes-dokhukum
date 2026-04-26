<!-- resources/views/partials/whatsapp-float.blade.php (Versi dengan gambar) -->
@php
    $no_telp = '6281378339986';
    $pesam = 'Halo, saya ingin bertanya tentang dokumen di desa Anda.';
@endphp

<div class="whatsapp-float">
    <a
        href="https://wa.me/{{ $no_telp }}?text={{ urlencode($pesam) }}"
        class="whatsapp-link"
        target="_blank"
        aria-label="Chat on WhatsApp"
        title="Chat WhatsApp"
    >
        <img
            src="{{ asset('assets-admin/img/WhatsAppButtonGreenMedium.png') }}"
            alt="Chat on WhatsApp"
            style="width: 150px; height: 50%;"
        >
    </a>
</div>

@include('layouts.admin.wacss')

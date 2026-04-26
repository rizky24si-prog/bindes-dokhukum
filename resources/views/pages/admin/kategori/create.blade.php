@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori Dokumen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Kategori Dokumen</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Tambah Kategori Dokumen</h1>
                <p class="mb-0">Form untuk menambahkan data kategori dokumen baru.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <label for="nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       value="{{ old('nama') }}" 
                                       placeholder="Masukkan Nama Kategori" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: Peraturan Daerah, Peraturan Walikota, Surat Edaran, dll.</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4"
                                       class="form-control @error('deskripsi') is-invalid @enderror"
                                       placeholder="Masukkan deskripsi kategori">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Deskripsi tentang kategori ini (opsional).</small>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Kategori
                            </button>
                            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
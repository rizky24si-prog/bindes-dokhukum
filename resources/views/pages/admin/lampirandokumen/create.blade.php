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
                <li class="breadcrumb-item"><a href="{{ route('lampiran-dokumen.index') }}">Lampiran Dokumen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Lampiran Dokumen</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Tambah Lampiran Dokumen</h1>
                <p class="mb-0">Form untuk menambahkan lampiran dokumen.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form action="{{ route('lampiran-dokumen.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="dokumen_id" class="form-label">Dokumen <span class="text-danger">*</span></label>
                                <select name="dokumen_id" id="dokumen_id" 
                                        class="form-select @error('dokumen_id') is-invalid @enderror" required>
                                    <option value="">Pilih Dokumen</option>
                                    @foreach($dokumenList as $dokumen)
                                        <option value="{{ $dokumen->dokumen_id }}" 
                                            {{ old('dokumen_id', $selectedDokumen) == $dokumen->dokumen_id ? 'selected' : '' }}>
                                            {{ $dokumen->judul }} ({{ $dokumen->nomor }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('dokumen_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" id="keterangan" 
                                       class="form-control @error('keterangan') is-invalid @enderror" 
                                       value="{{ old('keterangan') }}" 
                                       placeholder="Keterangan tentang lampiran">
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Deskripsi singkat tentang lampiran ini (opsional)</small>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="file_lampiran" class="form-label">File Lampiran <span class="text-danger">*</span></label>
                                <input type="file" name="file_lampiran" id="file_lampiran" 
                                       class="form-control @error('file_lampiran') is-invalid @enderror" 
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.txt,.zip,.rar" required>
                                @error('file_lampiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, TXT, ZIP, RAR
                                    <br>Maksimal ukuran: 10MB
                                </small>
                            </div>
                            
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Lampiran yang diupload akan tersimpan dalam sistem dan dapat diakses melalui halaman ini.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Lampiran
                            </button>
                            <a href="{{ route('lampiran-dokumen.index') }}" class="btn btn-secondary">
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
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
                <li class="breadcrumb-item active" aria-current="page">Edit Lampiran Dokumen</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Edit Lampiran Dokumen</h1>
                <p class="mb-0">Form untuk mengubah data lampiran dokumen.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form action="{{ route('lampiran-dokumen.update', $lampiran->lampiran_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="dokumen_id" class="form-label">Dokumen <span class="text-danger">*</span></label>
                                <select name="dokumen_id" id="dokumen_id" 
                                        class="form-select @error('dokumen_id') is-invalid @enderror" required>
                                    <option value="">Pilih Dokumen</option>
                                    @foreach($dokumenList as $dokumen)
                                        <option value="{{ $dokumen->dokumen_id }}" 
                                            {{ old('dokumen_id', $lampiran->dokumen_id) == $dokumen->dokumen_id ? 'selected' : '' }}>
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
                                       value="{{ old('keterangan', $lampiran->keterangan) }}" 
                                       placeholder="Keterangan tentang lampiran">
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="file_lampiran" class="form-label">File Lampiran Baru (Opsional)</label>
                                <input type="file" name="file_lampiran" id="file_lampiran" 
                                       class="form-control @error('file_lampiran') is-invalid @enderror" 
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.txt,.zip,.rar">
                                @error('file_lampiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Kosongkan jika tidak ingin mengganti file. 
                                    Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, TXT, ZIP, RAR
                                    <br>Maksimal ukuran: 10MB
                                </small>
                            </div>
                            
                            @if($lampiran->nama_file)
                            <div class="col-12 mb-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-file me-2"></i>
                                    <strong>File Saat Ini:</strong> {{ $lampiran->nama_file_display }}
                                    <br>
                                    <small>Ukuran: {{ $lampiran->ukuran_file_formatted }} | Tipe: {{ $lampiran->tipe_file }}</small>
                                    <br>
                                    @if($lampiran->file_url)
                                    <a href="{{ $lampiran->file_url }}" target="_blank" class="btn btn-sm btn-info mt-2">
                                        <i class="fas fa-eye me-1"></i> Lihat File
                                    </a>
                                    <a href="{{ route('lampiran-dokumen.download', $lampiran->lampiran_id) }}" class="btn btn-sm btn-success mt-2">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Lampiran
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
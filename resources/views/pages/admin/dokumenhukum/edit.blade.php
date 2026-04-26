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
                <li class="breadcrumb-item"><a href="#">Dokumen Hukum</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Dokumen Hukum</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Edit Dokumen Hukum</h1>
                <p class="mb-0">Form untuk mengubah data Dokumen Hukum.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form action="{{ route('dokumen.update', $dokumen->dokumen_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_id" class="form-label">Jenis Dokumen</label>
                                <select name="jenis_id" id="jenis_id" class="form-select" required>
                                    <option value="">Pilih Jenis Dokumen</option>
                                    @foreach($jenisDokumen as $jenis)
                                        <option value="{{ $jenis->jenis_id }}" 
                                            {{ old('jenis_id', $dokumen->jenis_id) == $jenis->jenis_id ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kategori_id" class="form-label">Kategori Dokumen</label>
                                <select name="kategori_id" id="kategori_id" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriDokumen as $kategori)
                                        <option value="{{ $kategori->kategori_id }}"
                                            {{ old('kategori_id', $dokumen->kategori_id) == $kategori->kategori_id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nomor" class="form-label">Nomor Dokumen</label>
                                <input type="text" name="nomor" id="nomor"
                                    value="{{ old('nomor', $dokumen->nomor) }}"
                                    class="form-control" placeholder="Masukkan Nomor Dokumen" required>
                                @error('nomor')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    value="{{ old('tanggal', $dokumen->tanggal->format('Y-m-d')) }}"
                                    class="form-control" required>
                                @error('tanggal')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="judul" class="form-label">Judul Dokumen</label>
                                <input type="text" name="judul" id="judul"
                                    value="{{ old('judul', $dokumen->judul) }}"
                                    class="form-control" placeholder="Masukkan Judul Dokumen" required>
                                @error('judul')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="ringkasan" class="form-label">Ringkasan</label>
                                <textarea name="ringkasan" id="ringkasan" rows="4"
                                    class="form-control"
                                    placeholder="Masukkan Ringkasan Dokumen">{{ old('ringkasan', $dokumen->ringkasan) }}</textarea>
                                @error('ringkasan')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="draft" {{ old('status', $dokumen->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $dokumen->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $dokumen->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="file" class="form-label">File Dokumen (Opsional)</label>
                                <input type="file" name="file" id="file" class="form-control">
                                @error('file')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                
                                @if($dokumen->media)
                                    <div class="mt-2">
                                        <small class="text-muted">File saat ini: </small>
                                        <a href="{{ $dokumen->media->full_url ?? '#' }}" target="_blank" class="badge bg-info">
                                            {{ $dokumen->media->file_name }}
                                        </a>
                                        <small class="text-muted">({{ number_format($dokumen->media->file_size / 1024, 2) }} KB)</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Dokumen</button>
                            <a href="{{ route('dokumen.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
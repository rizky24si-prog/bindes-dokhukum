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
                <li class="breadcrumb-item"><a href="{{ route('riwayat-perubahan.index') }}">Riwayat Perubahan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Riwayat Perubahan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Tambah Riwayat Perubahan</h1>
                <p class="mb-0">Form untuk menambahkan riwayat perubahan dokumen.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <form action="{{ route('riwayat-perubahan.store') }}" method="POST">
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
                                <label for="versi" class="form-label">Versi <span class="text-danger">*</span></label>
                                <input type="text" name="versi" id="versi" 
                                       class="form-control @error('versi') is-invalid @enderror" 
                                       value="{{ old('versi', $nextVersion) }}" 
                                       placeholder="Contoh: 1.0, 2.1" required>
                                @error('versi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: major.minor (contoh: 1.0, 2.3)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal Perubahan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" 
                                       class="form-control @error('tanggal') is-invalid @enderror" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tipe_perubahan" class="form-label">Tipe Perubahan</label>
                                <select name="tipe_perubahan" id="tipe_perubahan" 
                                        class="form-select @error('tipe_perubahan') is-invalid @enderror">
                                    <option value="">Pilih Tipe Perubahan</option>
                                    @foreach($tipePerubahanOptions as $value => $label)
                                        <option value="{{ $value }}" 
                                            {{ old('tipe_perubahan') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipe_perubahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pembuat" class="form-label">Pembuat Perubahan</label>
                                <input type="text" name="pembuat" id="pembuat" 
                                       class="form-control @error('pembuat') is-invalid @enderror" 
                                       value="{{ old('pembuat') }}" 
                                       placeholder="Nama pembuat perubahan">
                                @error('pembuat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="uraian_perubahan" class="form-label">Uraian Perubahan <span class="text-danger">*</span></label>
                                <textarea name="uraian_perubahan" id="uraian_perubahan" rows="5"
                                       class="form-control @error('uraian_perubahan') is-invalid @enderror"
                                       placeholder="Deskripsi lengkap tentang perubahan yang dilakukan" required>{{ old('uraian_perubahan') }}</textarea>
                                @error('uraian_perubahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Jelaskan secara detail perubahan yang dilakukan pada dokumen.</small>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Riwayat
                            </button>
                            <a href="{{ route('riwayat-perubahan.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dokumenSelect = document.getElementById('dokumen_id');
        const versiInput = document.getElementById('versi');
        
        // Auto generate versi ketika dokumen dipilih
        dokumenSelect.addEventListener('change', function() {
            if (this.value) {
                fetch(`/api/generate-version?dokumen_id=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.version) {
                            versiInput.value = data.version;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endpush
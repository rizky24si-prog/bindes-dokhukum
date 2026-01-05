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
                <li class="breadcrumb-item"><a href="#">Dokumen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lampiran Dokumen</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Lampiran Dokumen</h1>
                <p class="mb-0">Tabel untuk mengelola lampiran dokumen.</p>
            </div>
            <div>
                <a href="{{ route('lampiran-dokumen.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Lampiran
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('lampiran-dokumen.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <label for="dokumen_id" class="form-label">Filter Berdasarkan Dokumen</label>
                            <select name="dokumen_id" id="dokumen_id" class="form-select">
                                <option value="">Semua Dokumen</option>
                                @foreach($allDokumen as $dok)
                                    <option value="{{ $dok->dokumen_id }}" {{ request('dokumen_id') == $dok->dokumen_id ? 'selected' : '' }}>
                                        {{ $dok->judul }} ({{ $dok->nomor }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('lampiran-dokumen.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if($dokumen)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-file-alt me-2"></i>
                Menampilkan lampiran untuk dokumen: 
                <strong>{{ $dokumen->judul }}</strong> ({{ $dokumen->nomor }})
                <a href="{{ route('lampiran-dokumen.index') }}" class="float-end">
                    <i class="fas fa-times"></i> Hapus Filter
                </a>
            </div>
        </div>
    </div>
    @endif
    
    <div class="row">
        <div class="col-12 mb-4">
            @include('layouts.admin.error')
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0 rounded">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Dokumen</th>
                                    <th class="border-0">File Lampiran</th>
                                    <th class="border-0">Keterangan</th>
                                    <th class="border-0">Ukuran</th>
                                    <th class="border-0">Tanggal Upload</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataLampiran as $lampiran)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $lampiran->dokumen->judul ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $lampiran->dokumen->nomor ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas {{ $lampiran->file_icon }} fa-lg text-{{ $lampiran->file_badge_color }}"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $lampiran->nama_file_display }}</strong>
                                                <small class="text-muted">
                                                    {{ strtoupper(pathinfo($lampiran->nama_file_display, PATHINFO_EXTENSION)) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $lampiran->keterangan ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $lampiran->ukuran_file_formatted }}</span>
                                    </td>
                                    <td>{{ $lampiran->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailLampiranModal{{ $lampiran->lampiran_id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($lampiran->file_url)
                                            <a href="{{ route('lampiran-dokumen.download', $lampiran->lampiran_id) }}" 
                                               class="btn btn-sm btn-success" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('lampiran-dokumen.preview', $lampiran->lampiran_id) }}" 
                                               class="btn btn-sm btn-primary" title="Preview" target="_blank">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            @endif
                                            <a href="{{ route('lampiran-dokumen.edit', $lampiran->lampiran_id) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('lampiran-dokumen.destroy', $lampiran->lampiran_id) }}" 
                                                  method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Hapus lampiran ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailLampiranModal{{ $lampiran->lampiran_id }}" tabindex="-1" 
                                     aria-labelledby="detailLampiranModalLabel{{ $lampiran->lampiran_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="detailLampiranModalLabel{{ $lampiran->lampiran_id }}">
                                                    <i class="fas fa-paperclip me-2"></i>Detail Lampiran
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" 
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4 text-center">
                                                        <div class="mb-3">
                                                            <i class="fas {{ $lampiran->file_icon }} fa-4x text-{{ $lampiran->file_badge_color }}"></i>
                                                        </div>
                                                        <h5>{{ $lampiran->nama_file_display }}</h5>
                                                        <p class="text-muted">Lampiran Dokumen</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <strong><i class="fas fa-hashtag text-primary me-2"></i>ID Lampiran:</strong>
                                                                <span class="float-end badge bg-secondary">#{{ $lampiran->lampiran_id }}</span>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong><i class="fas fa-file text-primary me-2"></i>Tipe File:</strong>
                                                                <span class="float-end badge bg-{{ $lampiran->file_badge_color }}">
                                                                    {{ $lampiran->tipe_file }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-file-alt text-primary me-2"></i>Dokumen:</strong>
                                                            <div class="mt-1">
                                                                <strong>{{ $lampiran->dokumen->judul ?? '-' }}</strong>
                                                                <br>
                                                                <small class="text-muted">{{ $lampiran->dokumen->nomor ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-align-left text-primary me-2"></i>Keterangan:</strong>
                                                            <div class="mt-2 p-3 bg-light rounded">
                                                                {{ $lampiran->keterangan ?? 'Tidak ada keterangan' }}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <strong><i class="fas fa-weight text-primary me-2"></i>Ukuran File:</strong>
                                                                <span class="float-end">{{ $lampiran->ukuran_file_formatted }}</span>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong><i class="fas fa-calendar text-primary me-2"></i>Tanggal Upload:</strong>
                                                                <span class="float-end">{{ $lampiran->created_at->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        @if($lampiran->file_url)
                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-link text-primary me-2"></i>URL File:</strong>
                                                            <div class="mt-1">
                                                                <a href="{{ $lampiran->file_url }}" target="_blank" class="text-truncate d-block">
                                                                    {{ $lampiran->file_url }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <strong><i class="fas fa-calendar-plus text-primary me-2"></i>Dibuat Pada:</strong>
                                                                <span class="float-end">{{ $lampiran->created_at->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong><i class="fas fa-calendar-check text-primary me-2"></i>Diupdate Pada:</strong>
                                                                <span class="float-end">{{ $lampiran->updated_at->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i> Tutup
                                                </button>
                                                @if($lampiran->file_url)
                                                <a href="{{ route('lampiran-dokumen.download', $lampiran->lampiran_id) }}" 
                                                   class="btn btn-success">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                                @endif
                                                <a href="{{ route('lampiran-dokumen.edit', $lampiran->lampiran_id) }}" 
                                                   class="btn btn-warning">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-paperclip fa-2x mb-3"></i>
                                            <p>Belum ada data lampiran</p>
                                            <a href="{{ route('lampiran-dokumen.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus me-1"></i>Tambah Lampiran Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($dataLampiran->hasPages())
                    <div class="mt-4">
                        {{ $dataLampiran->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
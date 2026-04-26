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
                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat Perubahan</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Riwayat Perubahan Dokumen</h1>
                <p class="mb-0">Tabel untuk melihat riwayat perubahan dokumen.</p>
            </div>
            <div>
                <a href="{{ route('riwayat-perubahan.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Riwayat
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('riwayat-perubahan.index') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">Pencarian</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Cari uraian/versi/pembuat..." 
                                   value="{{ request('search') }}">
                        </div>
                        
                        <!-- Filter Dokumen -->
                        <div class="col-md-3">
                            <label for="dokumen_id" class="form-label">Dokumen</label>
                            <select name="dokumen_id" id="dokumen_id" class="form-select">
                                <option value="">Semua Dokumen</option>
                                @foreach($allDokumen as $dok)
                                    <option value="{{ $dok->dokumen_id }}" 
                                            {{ request('dokumen_id') == $dok->dokumen_id ? 'selected' : '' }}>
                                        {{ $dok->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Filter Tipe Perubahan -->
                        <div class="col-md-3">
                            <label for="tipe_perubahan" class="form-label">Tipe Perubahan</label>
                            <select name="tipe_perubahan" id="tipe_perubahan" class="form-select">
                                <option value="">Semua Tipe</option>
                                @foreach($tipePerubahanOptions as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ request('tipe_perubahan') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Tombol Action -->
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('riwayat-perubahan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Filter Tanggal -->
                    <form method="GET" action="{{ route('riwayat-perubahan.index') }}" class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="form-control" value="{{ request('start_date') }}">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control" value="{{ request('end_date') }}">
                        </div>
                        
                        <!-- Sort -->
                        <div class="col-md-2">
                            <label for="sort" class="form-label">Urutkan</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="tanggal" {{ request('sort', 'tanggal') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                                <option value="versi" {{ request('sort') == 'versi' ? 'selected' : '' }}>Versi</option>
                                <option value="pembuat" {{ request('sort') == 'pembuat' ? 'selected' : '' }}>Pembuat</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="order" class="form-label">Arah</label>
                            <select name="order" id="order" class="form-select">
                                <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>Desc</option>
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Asc</option>
                            </select>
                        </div>
                        
                        <!-- Hidden inputs untuk mempertahankan filter lainnya -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('dokumen_id'))
                            <input type="hidden" name="dokumen_id" value="{{ request('dokumen_id') }}">
                        @endif
                        @if(request('tipe_perubahan'))
                            <input type="hidden" name="tipe_perubahan" value="{{ request('tipe_perubahan') }}">
                        @endif
                        
                        <div class="col-md-12 mt-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i> Filter Tanggal & Sort
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Filter Aktif -->
    @if(request()->hasAny(['search', 'dokumen_id', 'tipe_perubahan', 'start_date', 'end_date']))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info py-2">
                <i class="fas fa-info-circle me-2"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge bg-primary ms-1">Pencarian: "{{ request('search') }}"</span>
                @endif
                @if(request('dokumen_id'))
                    <span class="badge bg-info ms-1">Dokumen: {{ $allDokumen->firstWhere('dokumen_id', request('dokumen_id'))->judul ?? '' }}</span>
                @endif
                @if(request('tipe_perubahan'))
                    <span class="badge bg-warning ms-1">Tipe: {{ $tipePerubahanOptions[request('tipe_perubahan')] ?? request('tipe_perubahan') }}</span>
                @endif
                @if(request('start_date') || request('end_date'))
                    <span class="badge bg-success ms-1">
                        Tanggal: {{ request('start_date') ?: 'Semua' }} - {{ request('end_date') ?: 'Semua' }}
                    </span>
                @endif
                <a href="{{ route('riwayat-perubahan.index') }}" class="float-end text-danger">
                    <i class="fas fa-times me-1"></i> Hapus Semua Filter
                </a>
            </div>
        </div>
    </div>
    @endif
    
    @if($dokumen)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="fas fa-file-alt me-2"></i>
                Menampilkan riwayat untuk dokumen: 
                <strong>{{ $dokumen->judul }}</strong> ({{ $dokumen->nomor }})
                <a href="{{ route('riwayat-perubahan.index') }}" class="float-end">
                    <i class="fas fa-times"></i> Tampilkan Semua
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
                    <!-- Info Pagination Atas -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted">
                                Menampilkan {{ $dataRiwayat->firstItem() ?? 0 }} - {{ $dataRiwayat->lastItem() ?? 0 }} 
                                dari {{ $dataRiwayat->total() }} data
                            </span>
                        </div>
                        <div class="text-end">
                            <span class="text-muted">Halaman {{ $dataRiwayat->currentPage() }} dari {{ $dataRiwayat->lastPage() }}</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0 rounded">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Dokumen</th>
                                    <th class="border-0">Tanggal</th>
                                    <th class="border-0">Versi</th>
                                    <th class="border-0">Perubahan</th>
                                    <th class="border-0">Pembuat</th>
                                    <th class="border-0">Tipe</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataRiwayat as $riwayat)
                                <tr>
                                    <td>{{ ($dataRiwayat->currentPage() - 1) * $dataRiwayat->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $riwayat->dokumen->judul ?? '-' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $riwayat->dokumen->nomor ?? '' }}</small>
                                    </td>
                                    <td>{{ $riwayat->tanggal_formatted }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $riwayat->versi_lengkap }}</span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" 
                                             title="{{ $riwayat->uraian_perubahan }}">
                                            {{ $riwayat->uraian_perubahan }}
                                        </div>
                                    </td>
                                    <td>{{ $riwayat->pembuat ?? '-' }}</td>
                                    <td>
                                        @if($riwayat->tipe_perubahan)
                                            <span class="badge bg-{{ 
                                                $riwayat->tipe_perubahan == 'revisi' ? 'primary' : 
                                                ($riwayat->tipe_perubahan == 'penambahan' ? 'success' : 
                                                ($riwayat->tipe_perubahan == 'pengurangan' ? 'danger' : 
                                                ($riwayat->tipe_perubahan == 'koreksi' ? 'warning' : 'info')))
                                            }}">
                                                {{ $tipePerubahanOptions[$riwayat->tipe_perubahan] ?? $riwayat->tipe_perubahan }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailRiwayatModal{{ $riwayat->riwayat_id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('riwayat-perubahan.edit', $riwayat->riwayat_id) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('riwayat-perubahan.destroy', $riwayat->riwayat_id) }}" 
                                              method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Hapus riwayat ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailRiwayatModal{{ $riwayat->riwayat_id }}" tabindex="-1" 
                                     aria-labelledby="detailRiwayatModalLabel{{ $riwayat->riwayat_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="detailRiwayatModalLabel{{ $riwayat->riwayat_id }}">
                                                    <i class="fas fa-history me-2"></i>Detail Riwayat Perubahan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" 
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <strong><i class="fas fa-hashtag text-primary me-2"></i>ID Riwayat:</strong>
                                                        <span class="float-end badge bg-secondary">#{{ $riwayat->riwayat_id }}</span>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong><i class="fas fa-code-branch text-primary me-2"></i>Versi:</strong>
                                                        <span class="float-end badge bg-info">{{ $riwayat->versi_lengkap }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-file-alt text-primary me-2"></i>Dokumen:</strong>
                                                    <div class="mt-1">
                                                        <strong>{{ $riwayat->dokumen->judul ?? '-' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $riwayat->dokumen->nomor ?? '' }}</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <strong><i class="fas fa-calendar text-primary me-2"></i>Tanggal Perubahan:</strong>
                                                        <span class="float-end">{{ $riwayat->tanggal_formatted }}</span>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong><i class="fas fa-user-edit text-primary me-2"></i>Pembuat:</strong>
                                                        <span class="float-end">{{ $riwayat->pembuat ?? 'Tidak diketahui' }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-tag text-primary me-2"></i>Tipe Perubahan:</strong>
                                                    <span class="float-end">
                                                        @if($riwayat->tipe_perubahan)
                                                            <span class="badge bg-{{ 
                                                                $riwayat->tipe_perubahan == 'revisi' ? 'primary' : 
                                                                ($riwayat->tipe_perubahan == 'penambahan' ? 'success' : 
                                                                ($riwayat->tipe_perubahan == 'pengurangan' ? 'danger' : 
                                                                ($riwayat->tipe_perubahan == 'koreksi' ? 'warning' : 'info')))
                                                            }}">
                                                                {{ $tipePerubahanOptions[$riwayat->tipe_perubahan] ?? $riwayat->tipe_perubahan }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak ada</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <strong><i class="fas fa-edit text-primary me-2"></i>Uraian Perubahan:</strong>
                                                    <div class="mt-2 p-3 bg-light rounded">
                                                        {!! nl2br(e($riwayat->uraian_perubahan)) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i> Tutup
                                                </button>
                                                <a href="{{ route('riwayat-perubahan.edit', $riwayat->riwayat_id) }}" 
                                                   class="btn btn-warning">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-history fa-2x mb-3"></i>
                                            <p>Belum ada data riwayat perubahan</p>
                                            @if(request()->hasAny(['search', 'dokumen_id', 'tipe_perubahan', 'start_date', 'end_date']))
                                                <p class="small">Coba hapus filter untuk menampilkan semua data</p>
                                            @endif
                                            <a href="{{ route('riwayat-perubahan.create') }}" class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i>Tambah Riwayat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($dataRiwayat->hasPages())
                    <div class="mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                {{-- Previous Page Link --}}
                                @if ($dataRiwayat->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataRiwayat->previousPageUrl() }}" aria-label="Previous">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $dataRiwayat->lastPage(); $i++)
                                    @if ($i == $dataRiwayat->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $dataRiwayat->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($dataRiwayat->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataRiwayat->nextPageUrl() }}" aria-label="Next">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        
                        <!-- Info Pagination Bawah -->
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Data per halaman: {{ $dataRiwayat->perPage() }}
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
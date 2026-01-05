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
                        <div class="col-md-4">
                            <label for="dokumen_id" class="form-label">Filter Dokumen</label>
                            <select name="dokumen_id" id="dokumen_id" class="form-select">
                                <option value="">Semua Dokumen</option>
                                @foreach($allDokumen as $dok)
                                    <option value="{{ $dok->dokumen_id }}" {{ request('dokumen_id') == $dok->dokumen_id ? 'selected' : '' }}>
                                        {{ $dok->judul }} ({{ $dok->nomor }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tipe_perubahan" class="form-label">Tipe Perubahan</label>
                            <select name="tipe_perubahan" id="tipe_perubahan" class="form-select">
                                <option value="">Semua Tipe</option>
                                @foreach($tipePerubahanOptions as $value => $label)
                                    <option value="{{ $value }}" {{ request('tipe_perubahan') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('riwayat-perubahan.index') }}" class="btn btn-secondary">
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
                Menampilkan riwayat untuk dokumen: 
                <strong>{{ $dokumen->judul }}</strong> ({{ $dokumen->nomor }})
                <a href="{{ route('riwayat-perubahan.index') }}" class="float-end">
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
                                    <td>{{ $loop->iteration }}</td>
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
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <strong><i class="fas fa-calendar-plus text-primary me-2"></i>Dibuat Pada:</strong>
                                                        <span class="float-end">{{ $riwayat->created_at->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong><i class="fas fa-calendar-check text-primary me-2"></i>Diupdate Pada:</strong>
                                                        <span class="float-end">{{ $riwayat->updated_at->format('d/m/Y H:i') }}</span>
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
                                            <a href="{{ route('riwayat-perubahan.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus me-1"></i>Tambah Riwayat Pertama
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
                        {{ $dataRiwayat->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
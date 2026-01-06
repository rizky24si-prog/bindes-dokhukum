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
                <li class="breadcrumb-item"><a href="#">Jenis Dokumen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tabel Jenis Dokumen</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Tabel Jenis Dokumen</h1>
                <p class="mb-0">Tabel untuk menampilkan data Jenis Dokumen.</p>
            </div>
            <div>
                <a href="{{ route('jenis-dokumen.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Data
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('jenis-dokumen.index') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-6">
                            <label for="search" class="form-label">Pencarian</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Cari nama jenis/deskripsi..." 
                                   value="{{ request('search') }}">
                        </div>
                        
                        <!-- Sort -->
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Urutkan</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="nama_jenis" {{ request('sort', 'nama_jenis') == 'nama_jenis' ? 'selected' : '' }}>Nama</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="order" class="form-label">Arah</label>
                            <select name="order" id="order" class="form-select">
                                <option value="asc" {{ request('order', 'asc') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </div>
                        
                        <!-- Tombol Action -->
                        <div class="col-md-12 d-flex align-items-end">
                            <div class="d-flex gap-2 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('jenis-dokumen.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Filter Aktif -->
    @if(request()->hasAny(['search']))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info py-2">
                <i class="fas fa-info-circle me-2"></i>
                Filter aktif: 
                @if(request('search'))
                    <span class="badge bg-primary ms-1">Pencarian: "{{ request('search') }}"</span>
                @endif
                <a href="{{ route('jenis-dokumen.index') }}" class="float-end text-danger">
                    <i class="fas fa-times me-1"></i> Hapus Filter
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
                                Menampilkan {{ $dataJenisDokumen->firstItem() ?? 0 }} - {{ $dataJenisDokumen->lastItem() ?? 0 }} 
                                dari {{ $dataJenisDokumen->total() }} data
                            </span>
                        </div>
                        <div class="text-end">
                            <span class="text-muted">Halaman {{ $dataJenisDokumen->currentPage() }} dari {{ $dataJenisDokumen->lastPage() }}</span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0 rounded">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Jenis Dokumen</th>
                                    <th class="border-0">Deskripsi</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataJenisDokumen as $data)
                                <tr>
                                    <td>{{ ($dataJenisDokumen->currentPage() - 1) * $dataJenisDokumen->perPage() + $loop->iteration }}</td>
                                    <td>{{ $data->nama_jenis }}</td>
                                    <td>{{ Str::limit($data->deskripsi, 60, '...') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailJenisModal{{ $data->jenis_id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('jenis-dokumen.edit', $data->jenis_id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('jenis-dokumen.destroy', $data->jenis_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-file-alt fa-2x mb-3"></i>
                                            <p>Belum ada data jenis dokumen</p>
                                            @if(request()->hasAny(['search']))
                                                <p class="small">Coba hapus filter untuk menampilkan semua data</p>
                                            @endif
                                            <a href="{{ route('jenis-dokumen.create') }}" class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-plus me-1"></i>Tambah Data
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($dataJenisDokumen->hasPages())
                    <div class="mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                {{-- Previous Page Link --}}
                                @if ($dataJenisDokumen->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataJenisDokumen->previousPageUrl() }}" aria-label="Previous">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @for ($i = 1; $i <= $dataJenisDokumen->lastPage(); $i++)
                                    @if ($i == $dataJenisDokumen->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $dataJenisDokumen->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                {{-- Next Page Link --}}
                                @if ($dataJenisDokumen->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataJenisDokumen->nextPageUrl() }}" aria-label="Next">
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
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@foreach($dataJenisDokumen as $data)
<div class="modal fade" id="detailJenisModal{{ $data->jenis_id }}" tabindex="-1" aria-labelledby="detailJenisModalLabel{{ $data->jenis_id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailJenisModalLabel{{ $data->jenis_id }}">
                    <i class="fas fa-file-alt me-2"></i>Detail Jenis Dokumen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-file-alt text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mt-3 mb-1">{{ $data->nama_jenis }}</h4>
                    <p class="text-muted">Jenis Dokumen</p>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <strong><i class="fas fa-hashtag text-info me-2"></i>ID Jenis:</strong>
                            <span class="float-end badge bg-secondary">#{{ $data->jenis_id }}</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-tag text-info me-2"></i>Nama Jenis:</strong>
                            <span class="float-end">{{ $data->nama_jenis }}</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-align-left text-info me-2"></i>Deskripsi:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                {{ $data->deskripsi ?? 'Tidak ada deskripsi' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-calendar-plus text-info me-2"></i>Dibuat Pada:</strong>
                            <span class="float-end">{{ $data->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div class="mb-3">
                            <strong><i class="fas fa-calendar-check text-info me-2"></i>Diupdate Pada:</strong>
                            <span class="float-end">{{ $data->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <a href="{{ route('jenis-dokumen.edit', $data->jenis_id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
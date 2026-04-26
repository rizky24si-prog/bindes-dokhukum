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
                <li class="breadcrumb-item"><a href="#">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tabel User</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Tabel User</h1>
                <p class="mb-0">Tabel untuk menampilkan data User.</p>
            </div>
            <div>
                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i>Tambah Data</a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Cari</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nama / Email">
                        </div>
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                                <option value="viewer" {{ request('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Cari
                                </button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(request()->hasAny(['search', 'role']))
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Menampilkan {{ $dataUser->total() }} hasil
                @if(request('search'))
                    untuk pencarian: <strong>"{{ request('search') }}"</strong>
                @endif
                <a href="{{ route('user.index') }}" class="float-end">
                    <i class="fas fa-times"></i> Hapus Filter
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-4">
        <div class="col-12 mb-4">
            @include('layouts.admin.error')
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0 rounded">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">Nama</th>
                                    <th class="border-0">Email</th>
                                    <th class="border-0">Role</th>
                                    <th class="border-0">Tanggal Dibuat</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataUser as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($data->role) }}</span></td>
                                    <td>{{ $data->created_at->format('d/m/Y') }}</td>
                                   <td>
                                     <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailUserModal{{ $data->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                    <a href="{{ route('user.edit', $data->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('user.destroy', $data->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-user fa-2x mb-3"></i>
                                            <p>Belum ada data user</p>
                                            @if(request()->hasAny(['search', 'role']))
                                                <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-redo me-1"></i> Hapus Filter
                                                </a>
                                            @else
                                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i>Tambah Data Pertama
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($dataUser->hasPages())
                    <div class="mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                {{-- Previous Page Link --}}
                                @if ($dataUser->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataUser->previousPageUrl() }}" rel="prev">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($dataUser->getUrlRange(1, $dataUser->lastPage()) as $page => $url)
                                    @if ($page == $dataUser->currentPage())
                                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($dataUser->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dataUser->nextPageUrl() }}" rel="next">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                    </li>
                                @endif
                                
                                {{-- Info --}}
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        {{ $dataUser->firstItem() ?? 0 }} - {{ $dataUser->lastItem() ?? 0 }} dari {{ $dataUser->total() }}
                                    </span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach($dataUser as $data)
    <div class="modal fade" id="detailUserModal{{ $data->id }}" tabindex="-1" aria-labelledby="detailUserModalLabel{{ $data->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detailUserModalLabel{{ $data->id }}">
                        <i class="fas fa-user me-2"></i>Detail User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="mt-3 mb-1">{{ $data->name }}</h4>
                        <p class="text-muted">User Account</p>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <strong><i class="fas fa-id-card text-primary me-2"></i>ID User:</strong>
                                <span class="float-end badge bg-secondary">#{{ $data->id }}</span>
                            </div>

                            <div class="mb-3">
                                <strong><i class="fas fa-user-tag text-primary me-2"></i>Nama Lengkap:</strong>
                                <span class="float-end">{{ $data->name }}</span>
                            </div>

                            <div class="mb-3">
                                <strong><i class="fas fa-envelope text-primary me-2"></i>Email:</strong>
                                <span class="float-end">{{ $data->email }}</span>
                            </div>

                            <div class="mb-3">
                                <strong><i class="fas fa-user-shield text-primary me-2"></i>Role:</strong>
                                <span class="float-end badge bg-info">{{ ucfirst($data->role) }}</span>
                            </div>

                            <div class="mb-3">
                                <strong><i class="fas fa-calendar-alt text-primary me-2"></i>Dibuat Pada:</strong>
                                <span class="float-end">{{ $data->created_at->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="mb-3">
                                <strong><i class="fas fa-sync-alt text-primary me-2"></i>Diupdate Pada:</strong>
                                <span class="float-end">{{ $data->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                    <a href="{{ route('user.edit', $data->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
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
    <div class="row">
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
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataUser as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->email }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

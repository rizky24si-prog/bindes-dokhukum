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
                    <li class="breadcrumb-item"><a href="#">Warga</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tabel Warga</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap">
                <div class="mb-3 mb-lg-0">
                    <h1 class="h4">Tabel Warga</h1>
                    <p class="mb-0">Tabel untuk menampilkan data Warga .</p>
                </div>
<div>
<a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary">
    <i class="fas fa-plus"></i>Tambah Data
</a>
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
                                    <th class="border-0 ">Nama</th>
                                    <th class="border-0">No KTP</th>
                                    <th class="border-0">Jenis Kelamin</th>
                                    <th class="border-0">Agama</th>
                                    <th class="border-0">Pekerjaan</th>
                                    <th class="border-0">Telp</th>
                                    <th class="border-0">Email</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
<tbody>
    @foreach($dataWarga as $data)
        <tr>
            <td>{{ $data->nama }}</td>
            <td>{{ $data->no_ktp }}</td>
            <td>{{ $data->jenis_kelamin }}</td>
            <td>{{ $data->agama }}</td>
            <td>{{ $data->pekerjaan }}</td>
            <td>{{ $data->telp }}</td>
            <td>{{ $data->email }}</td>

<td>
    <!-- Button Detail -->
    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailWargaModal{{ $data->warga_id }}">
        <i class="fas fa-eye"></i> Detail
    </button>

    <!-- Button Edit -->
    <a href="{{ route('warga.edit', $data->warga_id) }}" class="btn btn-sm btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>

    <!-- Button Hapus -->
    <form action="{{ route('warga.destroy', $data->warga_id) }}" method="POST" style="display:inline-block;">
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
<!-- Modal Detail Warga -->
<div class="modal fade" id="detailWargaModal{{ $data->warga_id }}" tabindex="-1" aria-labelledby="detailWargaModalLabel{{ $data->warga_id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailWargaModalLabel{{ $data->warga_id }}">
                    <i class="fas fa-user me-2"></i>Detail Data Warga
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">No. KTP</th>
                                <td width="60%">: {{ $data->no_ktp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>: {{ $data->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>:
                                    @if($data->jenis_kelamin == 'L')
                                        Laki-laki
                                    @elseif($data->jenis_kelamin == 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Agama</th>
                                <td width="60%">: {{ $data->agama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pekerjaan</th>
                                <td>: {{ $data->pekerjaan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>: {{ $data->telp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: {{ $data->email ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Jika ada field tambahan atau keterangan -->
                @if($data->keterangan)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted">Keterangan:</h6>
                        <p class="mb-0">{{ $data->keterangan }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
                <a href="{{ route('warga.edit', $data->warga_id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

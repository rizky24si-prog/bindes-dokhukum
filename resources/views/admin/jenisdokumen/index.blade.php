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
                    <p class="mb-0">Tabel untuk menampilkan data Jenis Dokumen .</p>
                </div>
                <div>
                <a href="{{ route('jenis-dokumen.create') }}" class="btn btn-sm btn-primary">Tambah Data</a>
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
                                    <th class="border-0 ">Jenis Dokumen</th>
                                    <th class="border-0">Deskripsi</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
<tbody>
    @foreach($dataJenisDokumen as $data)
        <tr>
            <td>{{ $data->nama_jenis }}</td>
            <td>{{ Str::limit($data->deskripsi, 60, '...') }}</td>
            <td>
                <a href="{{ route('jenis-dokumen.edit', $data->jenis_id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('jenis-dokumen.destroy', $data->jenis_id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

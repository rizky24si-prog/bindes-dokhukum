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
                <li class="breadcrumb-item active" aria-current="page">Kategori Dokumen</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Kategori Dokumen</h1>
                <p class="mb-0">Tabel untuk mengelola data kategori dokumen.</p>
            </div>
            <div>
                <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Kategori
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
                                    <th class="border-0">No</th>
                                    <th class="border-0">Nama Kategori</th>
                                    <th class="border-0">Deskripsi</th>
                                    <th class="border-0">Jumlah Dokumen</th>
                                    <th class="border-0 rounded-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataKategori as $index => $kategori)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $kategori->nama }}</strong>
                                    </td>
                                    <td>
                                        {{ Str::limit($kategori->deskripsi, 50, '...') ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $kategori->dokumen_hukum_count }} Dokumen</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailKategoriModal{{ $kategori->kategori_id }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <a href="{{ route('kategori.edit', $kategori->kategori_id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('kategori.destroy', $kategori->kategori_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailKategoriModal{{ $kategori->kategori_id }}" tabindex="-1" aria-labelledby="detailKategoriModalLabel{{ $kategori->kategori_id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="detailKategoriModalLabel{{ $kategori->kategori_id }}">
                                                    <i class="fas fa-folder me-2"></i>Detail Kategori: {{ $kategori->nama }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="text-center mb-4">
                                                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                                                <i class="fas fa-folder text-white" style="font-size: 3rem;"></i>
                                                            </div>
                                                            <h4 class="mt-3 mb-1">{{ $kategori->nama }}</h4>
                                                            <p class="text-muted">Kategori Dokumen</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-hashtag text-primary me-2"></i>ID Kategori:</strong>
                                                            <span class="float-end badge bg-secondary">#{{ $kategori->kategori_id }}</span>
                                                        </div>

                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-tag text-primary me-2"></i>Nama Kategori:</strong>
                                                            <span class="float-end">{{ $kategori->nama }}</span>
                                                        </div>

                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-align-left text-primary me-2"></i>Deskripsi:</strong>
                                                            <div class="mt-2 p-3 bg-light rounded">
                                                                {{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-file-alt text-primary me-2"></i>Jumlah Dokumen:</strong>
                                                            <span class="float-end badge bg-info" style="font-size: 1rem;">
                                                                {{ $kategori->dokumen_hukum_count }} Dokumen
                                                            </span>
                                                        </div>

                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-calendar-plus text-primary me-2"></i>Dibuat Pada:</strong>
                                                            <span class="float-end">{{ $kategori->created_at->format('d/m/Y H:i') }}</span>
                                                        </div>

                                                        <div class="mb-3">
                                                            <strong><i class="fas fa-calendar-check text-primary me-2"></i>Diupdate Pada:</strong>
                                                            <span class="float-end">{{ $kategori->updated_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($kategori->dokumen_hukum_count > 0)
                                                <div class="mt-4">
                                                    <h5 class="mb-3"><i class="fas fa-file-contract me-2"></i>Dokumen dalam Kategori ini:</h5>
                                                    <div class="list-group">
                                                        @foreach($kategori->dokumenHukum->take(5) as $dokumen)
                                                        <a href="#" class="list-group-item list-group-item-action">
                                                            <div class="d-flex w-100 justify-content-between">
                                                                <h6 class="mb-1">{{ $dokumen->judul }}</h6>
                                                                <small>{{ $dokumen->tanggal->format('d/m/Y') }}</small>
                                                            </div>
                                                            <p class="mb-1">{{ Str::limit($dokumen->ringkasan, 100) }}</p>
                                                            <small>
                                                                <span class="badge bg-{{ $dokumen->status == 'published' ? 'success' : ($dokumen->status == 'draft' ? 'warning' : 'secondary') }}">
                                                                    {{ $dokumen->status }}
                                                                </span>
                                                            </small>
                                                        </a>
                                                        @endforeach
                                                        @if($kategori->dokumen_hukum_count > 5)
                                                        <div class="list-group-item text-center">
                                                            <small class="text-muted">
                                                                Dan {{ $kategori->dokumen_hukum_count - 5 }} dokumen lainnya...
                                                            </small>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i> Tutup
                                                </button>
                                                <a href="{{ route('kategori.edit', $kategori->kategori_id) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-2x mb-3"></i>
                                            <p>Belum ada data kategori</p>
                                            <a href="{{ route('kategori.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus me-1"></i>Tambah Kategori Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
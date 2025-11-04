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
                    <li class="breadcrumb-item active" aria-current="page">Edit Warga</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap">
                <div class="mb-3 mb-lg-0">
                    <h1 class="h4">Edit Warga</h1>
                    <p class="mb-0">Form untuk mengedit data Warga baru.</p>
                </div>

            </div>
    </div>
 <div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow components-section">
            <div class="card-body">
                <form action="{{ route('warga.update', $dataWarga->warga_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-4">
                        <!-- No KTP -->
                        <div class="mb-3">
                            <label for="no_ktp" class="form-label">Nomor KTP</label>
                            <input type="text" name="no_ktp" id="no_ktp" class="form-control"
                                   value="{{ old('no_ktp', $dataWarga->no_ktp) }}"
                                   placeholder="Masukkan nomor KTP" required>
                        </div>

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                   value="{{ old('nama', $dataWarga->nama) }}"
                                   placeholder="Masukkan nama lengkap" required>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label class="my-1 me-2" for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-select" name="jenis_kelamin" id="jenis_kelamin" required>
                                <option disabled>Pilih jenis kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $dataWarga->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $dataWarga->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Agama -->
                        <div class="mb-3">
                            <label for="agama" class="form-label">Agama</label>
                            <input type="text" name="agama" id="agama" class="form-control"
                                   value="{{ old('agama', $dataWarga->agama) }}"
                                   placeholder="Contoh: Islam, Kristen, Hindu, dll">
                        </div>

                        <!-- Pekerjaan -->
                        <div class="mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan" id="pekerjaan" class="form-control"
                                   value="{{ old('pekerjaan', $dataWarga->pekerjaan) }}"
                                   placeholder="Masukkan pekerjaan">
                        </div>

                        <!-- Telepon -->
                        <div class="mb-3">
                            <label for="telp" class="form-label">Nomor Telepon</label>
                            <input type="text" name="telp" id="telp" class="form-control"
                                   value="{{ old('telp', $dataWarga->telp) }}"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email', $dataWarga->email) }}"
                                   placeholder="contoh@email.com" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('warga.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@extends('layouts.admin.app')

@section('content')
<!-- BEGIN: Content-->
<div class="container-fluid mt-4">
    <!-- HEADER SECTION -->
    <div class="py-4">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard Sistem Dokumen Hukum</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div class="mb-3 mb-lg-0">
                <h1 class="h4">Dashboard Sistem Dokumen Hukum</h1>
                <p class="mb-0">Ringkasan lengkap seluruh aktivitas sistem pengelolaan dokumen.</p>
            </div>
            <div>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ \Carbon\Carbon::now()->format('d M Y') }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="periodDropdown">
                        <li><a class="dropdown-item" href="?period=today">Hari Ini</a></li>
                        <li><a class="dropdown-item" href="?period=week">Minggu Ini</a></li>
                        <li><a class="dropdown-item" href="?period=month">Bulan Ini</a></li>
                        <li><a class="dropdown-item" href="?period=year">Tahun Ini</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK STATS ROW -->
    <div class="row mb-4">
        <!-- Total Dokumen Card -->
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Total Dokumen</h6>
                        <span class="badge bg-primary rounded-pill">Hukum</span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h2 class="fw-bold mb-0">{{ $totalDokumen ?? 0 }}</h2>
                            <div class="small text-muted">Dokumen Terdaftar</div>
                            @if(isset($dokumenGrowth))
                            <div class="mt-2">
                                @if($dokumenGrowth >= 0)
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        {{ $dokumenGrowth }}%
                                    </span>
                                @else
                                    <span class="text-danger">
                                        <i class="fas fa-arrow-down me-1"></i>
                                        {{ abs($dokumenGrowth) }}%
                                    </span>
                                @endif
                                <span class="text-muted ms-2">dari bulan lalu</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon-shape icon-shape-primary rounded-circle p-3">
                                <i class="fas fa-file-contract fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ ($todayDokumen/($totalDokumen ?: 1))*100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $todayDokumen ?? 0 }} baru hari ini</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Total Users</h6>
                        <span class="badge bg-info rounded-pill">{{ $usersByRole['admin'] ?? 0 }} Admin</span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h2 class="fw-bold mb-0">{{ $totalUsers ?? 0 }}</h2>
                            <div class="small text-muted">Pengguna Terdaftar</div>
                            @if(isset($userGrowth))
                            <div class="mt-2">
                                @if($userGrowth >= 0)
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        {{ $userGrowth }}%
                                    </span>
                                @else
                                    <span class="text-danger">
                                        <i class="fas fa-arrow-down me-1"></i>
                                        {{ abs($userGrowth) }}%
                                    </span>
                                @endif
                                <span class="text-muted ms-2">dari bulan lalu</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon-shape icon-shape-info rounded-circle p-3">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-thin">
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ ($todayUsers/($totalUsers ?: 1))*100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $todayUsers ?? 0 }} baru hari ini</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jenis & Kategori Card -->
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Jenis & Kategori</h6>
                        <span class="badge bg-success rounded-pill">Klasifikasi</span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h4 class="fw-bold mb-0">{{ $totalJenis ?? 0 }}</h4>
                            <div class="small text-muted">Jenis</div>
                        </div>
                        <div class="col-6">
                            <h4 class="fw-bold mb-0">{{ $totalKategori ?? 0 }}</h4>
                            <div class="small text-muted">Kategori</div>
                        </div>
                    </div>
                    @if(isset($jenisWithMostDokumen) && $jenisWithMostDokumen)
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-star text-warning me-1"></i>
                            Terbanyak: {{ $jenisWithMostDokumen->nama_jenis ?? 'N/A' }} ({{ $jenisWithMostDokumen->dokumen_count ?? 0 }})
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Riwayat & Lampiran Card -->
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-0 shadow stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title mb-0">Riwayat & Lampiran</h6>
                        <span class="badge bg-warning rounded-pill">Aktivitas</span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h4 class="fw-bold mb-0">{{ $totalRiwayat ?? 0 }}</h4>
                            <div class="small text-muted">Riwayat</div>
                        </div>
                        <div class="col-6">
                            <h4 class="fw-bold mb-0">{{ $totalLampiran ?? 0 }}</h4>
                            <div class="small text-muted">Lampiran</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-history me-1"></i>
                            {{ $quickSummary['riwayat_today'] ?? 0 }} riwayat hari ini
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS AND ANALYTICS ROW -->
    <div class="row mb-4">
        <!-- Dokumen Registration Chart -->
        <div class="col-12 col-lg-8 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h6 class="mb-0">Pertumbuhan Dokumen {{ date('Y') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dokumenChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h6 class="mb-0">Distribusi Status Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                    @if(isset($dokumenByStatus) && count($dokumenByStatus) > 0)
                    <div class="mt-3">
                        @foreach($dokumenByStatus as $status => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'review' => 'warning', 
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'archived' => 'info'
                                    ];
                                    $color = $statusColors[$status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} me-2">{{ ucfirst($status) }}</span>
                            </span>
                            <span class="fw-bold">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">Data status tidak tersedia</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PERFORMANCE METRICS ROW -->
    <div class="row mb-4">
        <!-- App Performance -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h6 class="mb-0">Performance Sistem</h6>
                </div>
                <div class="card-body">
                    @if(isset($appPerformance))
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $appPerformance['database']['color'] ?? 'success' }} rounded-circle p-2 me-3">
                                    <i class="fas fa-database text-white"></i>
                                </div>
                                <div>
                                    <div class="h6 mb-0">Database</div>
                                    <small class="text-muted">{{ $appPerformance['database']['status'] ?? 'Unknown' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $appPerformance['cache']['color'] ?? 'success' }} rounded-circle p-2 me-3">
                                    <i class="fas fa-bolt text-white"></i>
                                </div>
                                <div>
                                    <div class="h6 mb-0">Cache</div>
                                    <small class="text-muted">{{ $appPerformance['cache']['status'] ?? 'Unknown' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $appPerformance['storage']['color'] ?? 'success' }} rounded-circle p-2 me-3">
                                    <i class="fas fa-hdd text-white"></i>
                                </div>
                                <div>
                                    <div class="h6 mb-0">Storage</div>
                                    <small class="text-muted">{{ $appPerformance['storage']['used'] ?? '0 B' }} used</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div>
                                    <div class="h6 mb-0">Uptime</div>
                                    <small class="text-muted">{{ $appPerformance['uptime']['uptime'] ?? '0 days' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(isset($processingMetrics))
                    <div class="mt-4">
                        <h6 class="mb-3">Processing Metrics</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h4 mb-0">{{ $processingMetrics['avg_processing_time'] ?? 0 }}</div>
                                <small class="text-muted">Avg Hours</small>
                            </div>
                            <div class="col-4">
                                <div class="h4 mb-0">{{ $processingMetrics['completion_rate'] ?? 0 }}%</div>
                                <small class="text-muted">Completion</small>
                            </div>
                            <div class="col-4">
                                <div class="h4 mb-0">{{ $processingMetrics['revision_count'] ?? 0 }}</div>
                                <small class="text-muted">Revisions</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Yearly Comparison & Activity -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h6 class="mb-0">Perbandingan Tahunan & Aktivitas</h6>
                </div>
                <div class="card-body">
                    @if(isset($yearlyComparison))
                    <div class="mb-4">
                        <h6 class="mb-3">Perbandingan {{ date('Y') }} vs {{ date('Y')-1 }}</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-0">{{ $yearlyComparison['current_year'] ?? 0 }}</div>
                                    <small class="text-muted">{{ date('Y') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-0">{{ $yearlyComparison['last_year'] ?? 0 }}</div>
                                    <small class="text-muted">{{ date('Y')-1 }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            @if($yearlyComparison['trend'] == 'up')
                            <span class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                {{ $yearlyComparison['growth'] ?? 0 }}% Growth
                            </span>
                            @else
                            <span class="text-danger">
                                <i class="fas fa-arrow-down me-1"></i>
                                {{ abs($yearlyComparison['growth'] ?? 0) }}% Decline
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(isset($userActivity))
                    <div class="mt-4">
                        <h6 class="mb-3">Aktivitas User</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h4 mb-0">{{ $userActivity['active_users'] ?? 0 }}</div>
                                <small class="text-muted">Active Users</small>
                            </div>
                            <div class="col-4">
                                <div class="h4 mb-0">{{ $userActivity['total_logins'] ?? 0 }}</div>
                                <small class="text-muted">Total Logins</small>
                            </div>
                            <div class="col-4">
                                <div class="h4 mb-0">{{ $userActivity['avg_session'] ?? '0m' }}</div>
                                <small class="text-muted">Avg Session</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT ACTIVITIES ROW -->
    <div class="row mb-4">
        <!-- Recent Dokumen -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Dokumen Terbaru</h6>
                    <a href="{{ route('dokumen.index') ?? '#' }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Dokumen</th>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDokumen as $dokumen)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $dokumen->nomor ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($dokumen->judul, 30) }}</div>
                                        <small class="text-muted">{{ $dokumen->jenis->nama_jenis ?? 'Unknown' }}</small>
                                    </td>
                                    <td>{{ $dokumen->tanggal ? \Carbon\Carbon::parse($dokumen->tanggal)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'review' => 'warning', 
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'archived' => 'info'
                                            ];
                                            $color = $statusColors[$dokumen->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($dokumen->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-file-alt fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada dokumen</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Pengguna Terbaru</h6>
                    <a href="{{ route('user.index') ?? '#' }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'info' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada pengguna</p>
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

    <!-- LATEST CHANGES & ACTIVITY -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Perubahan Terbaru</h6>
                    <small class="text-muted">{{ $totalRiwayat ?? 0 }} total riwayat</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Dokumen</th>
                                    <th>Perubahan</th>
                                    <th>Versi</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRiwayat as $riwayat)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $riwayat->dokumen->judul ?? 'Unknown' }}</div>
                                        <small class="text-muted">{{ $riwayat->dokumen->nomor ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ $riwayat->uraian_perubahan }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">v{{ $riwayat->versi }}</span>
                                    </td>
                                    <td>
                                        @if($riwayat->tanggal)
                                            {{ \Carbon\Carbon::parse($riwayat->tanggal)->format('d/m/Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada riwayat perubahan</p>
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

    <!-- QUICK SUMMARY FOOTER -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="h4 mb-0 text-primary">{{ $quickSummary['users_today'] ?? 0 }}</div>
                            <small class="text-muted">Users Today</small>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="h4 mb-0 text-success">{{ $quickSummary['dokumen_today'] ?? 0 }}</div>
                            <small class="text-muted">Dokumen Today</small>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="h4 mb-0 text-info">{{ $quickSummary['riwayat_today'] ?? 0 }}</div>
                            <small class="text-muted">Riwayat Today</small>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="h4 mb-0 text-warning">{{ $quickSummary['lampiran_today'] ?? 0 }}</div>
                            <small class="text-muted">Lampiran Today</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-server me-1"></i>
                                Last Updated: {{ now()->format('d M Y H:i:s') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fas fa-chart-line me-1"></i>
                                Total Dokumen Tahun Ini: {{ $dokumenTahunIni ?? 0 }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- END: Content-->

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dokumen Chart
    const dokumenCtx = document.getElementById('dokumenChart');
    if (dokumenCtx) {
        const dokumenData = @json($dokumenMonthly ?? []);
        
        new Chart(dokumenCtx, {
            type: 'line',
            data: {
                labels: dokumenData.map(d => d.month),
                datasets: [{
                    label: 'Dokumen Per Bulan',
                    data: dokumenData.map(d => d.count),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const statusData = @json($statusDistribution ?? []);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.map(s => s.status),
                datasets: [{
                    data: statusData.map(s => s.count),
                    backgroundColor: [
                        '#4e73df', // draft
                        '#1cc88a', // approved
                        '#f6c23e', // review
                        '#e74a3b', // rejected
                        '#858796'  // archived
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }

    // Auto refresh dashboard every 60 seconds (optional)
    // setTimeout(function() {
    //     window.location.reload();
    // }, 60000);
</script>
@endpush

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }
    .icon-shape {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .icon-shape-primary { background-color: #4e73df; }
    .icon-shape-info { background-color: #36b9cc; }
    .icon-shape-success { background-color: #1cc88a; }
    .icon-shape-warning { background-color: #f6c23e; }
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .progress-thin {
        height: 6px;
        border-radius: 3px;
    }
    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }
    .table thead th {
        border-top: none;
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
</style>
@endpush

@endsection
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DokumenHukum;
use App\Models\JenisDokumen;
use App\Models\KategoriDokumen;
use App\Models\Media;
use App\Models\RiwayatPerubahan;
use App\Models\LampiranDokumen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =================== STATISTIK UTAMA ===================
        
        // User Statistics
        $totalUsers = User::count();
        $userGrowth = $this->getUserGrowth();
        $usersByRole = $this->getUsersByRole();
        $todayUsers = User::whereDate('created_at', Carbon::today())->count();
        
        // Dokumen Hukum Statistics
        $totalDokumen = DokumenHukum::count();
        $dokumenGrowth = $this->getDokumenGrowth();
        $dokumenByStatus = $this->getDokumenByStatus();
        $dokumenByJenis = $this->getDokumenByJenis();
        $dokumenByKategori = $this->getDokumenByKategori();
        $todayDokumen = DokumenHukum::whereDate('created_at', Carbon::today())->count();
        $dokumenTahunIni = DokumenHukum::whereYear('created_at', Carbon::now()->year)->count();
        
        // Jenis & Kategori Statistics
        $totalJenis = JenisDokumen::count();
        $totalKategori = KategoriDokumen::count();
        $jenisWithMostDokumen = $this->getJenisWithMostDokumen();
        $kategoriWithMostDokumen = $this->getKategoriWithMostDokumen();
        
        // Media Statistics
        $totalMedia = Media::count();
        $mediaByType = $this->getMediaByType();
        
        // Riwayat Statistics
        $totalRiwayat = RiwayatPerubahan::count();
        $latestChanges = $this->getLatestChanges();
        $mostActiveDocuments = $this->getMostActiveDocuments();
        
        // Lampiran Statistics
        $totalLampiran = LampiranDokumen::count();
        
        // =================== DATA UNTUK CHARTS ===================
        
        // Registration Trends
        $userRegistrations = $this->getUserRegistrations();
        $dokumenRegistrations = $this->getDokumenRegistrations();
        
        // Dokumen Trends by Month
        $dokumenMonthly = $this->getDokumenMonthly();
        
        // Status Distribution for Chart
        $statusDistribution = $this->getStatusDistribution();
        
        // =================== DATA TERBARU ===================
        
        // Recent Data
        $recentUsers = User::latest()->take(5)->get();
        $recentDokumen = DokumenHukum::with(['jenis', 'kategori'])
            ->latest()
            ->take(5)
            ->get();
        $recentRiwayat = RiwayatPerubahan::with('dokumen')
            ->latest()
            ->take(5)
            ->get();
        
        
        // =================== PERFORMANCE METRICS ===================
        
        // Aplikasi Performance
        $appPerformance = [
            'database' => $this->checkDatabaseConnection(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'uptime' => $this->getSystemUptime()
        ];
        
        // Document Processing Metrics
        $processingMetrics = [
            'avg_processing_time' => $this->getAverageProcessingTime(),
            'completion_rate' => $this->getCompletionRate(),
            'revision_count' => $this->getRevisionCount()
        ];
        
        // =================== ANALYTICS ===================
        
        // Document Analytics
        
        // User Activity
        
        // Yearly Comparison
        $yearlyComparison = $this->getYearlyComparison();
        
        // =================== SUMMARIES ===================
        
        // Quick Summary
        $quickSummary = [
            'users_today' => $todayUsers,
            'dokumen_today' => $todayDokumen,
            'riwayat_today' => RiwayatPerubahan::whereDate('created_at', Carbon::today())->count(),
            'lampiran_today' => LampiranDokumen::whereDate('created_at', Carbon::today())->count()
        ];
        
        // Top Statistics
        $topStats = [
            'most_productive_day' => $this->getMostProductiveDay(),
            'busiest_hour' => $this->getBusiestHour(),
            'most_used_jenis' => $jenisWithMostDokumen,
            'most_used_kategori' => $kategoriWithMostDokumen
        ];

        return view('pages.admin.dashboard', compact(
            // Main Statistics
            'totalUsers', 'userGrowth', 'usersByRole', 'todayUsers',
            'totalDokumen', 'dokumenGrowth', 'dokumenByStatus', 'todayDokumen',
            'totalJenis', 'totalKategori', 'totalMedia', 'totalRiwayat', 'totalLampiran',
            
            // Charts Data
            'userRegistrations', 'dokumenRegistrations', 'dokumenMonthly',
            'statusDistribution', 'dokumenByJenis', 'dokumenByKategori',
            
            // Recent Data
            'recentUsers', 'recentDokumen', 'recentRiwayat',
         
            
            // Analytics
           'yearlyComparison',
            
            // Performance
            'appPerformance', 'processingMetrics',
            
            // Summaries
            'quickSummary', 'topStats',
            'jenisWithMostDokumen', 'kategoriWithMostDokumen',
            'latestChanges', 'mostActiveDocuments',
            'mediaByType', 'dokumenTahunIni'
        ));
    }
    
    // =================== HELPER METHODS ===================
    
    /**
     * Calculate user growth percentage
     */
    private function getUserGrowth()
    {
        $currentMonth = User::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $lastMonth = User::whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
    }
    
    /**
     * Calculate dokumen growth percentage
     */
    private function getDokumenGrowth()
    {
        $currentMonth = DokumenHukum::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $lastMonth = DokumenHukum::whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->count();

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 2);
    }
    
    /**
     * Get users by role
     */
    private function getUsersByRole()
    {
        return User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->role => $item->count];
            });
    }
    
    /**
     * Get dokumen by status
     */
    private function getDokumenByStatus()
    {
        return DokumenHukum::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });
    }
    
    /**
     * Get dokumen by jenis
     */
    private function getDokumenByJenis()
    {
        return DokumenHukum::select('jenis_id', DB::raw('COUNT(*) as count'))
            ->groupBy('jenis_id')
            ->with('jenis')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->jenis->nama_jenis ?? 'Unknown' => $item->count];
            });
    }
    
    /**
     * Get dokumen by kategori
     */
    private function getDokumenByKategori()
    {
        return DokumenHukum::select('kategori_id', DB::raw('COUNT(*) as count'))
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->kategori->nama ?? 'Unknown' => $item->count];
            });
    }
    
    /**
     * Get jenis with most dokumen
     */
    private function getJenisWithMostDokumen()
    {
        return JenisDokumen::withCount('dokumen')
            ->orderBy('dokumen_count', 'desc')
            ->first();
    }
    
    /**
     * Get kategori with most dokumen
     */
    private function getKategoriWithMostDokumen()
    {
        return KategoriDokumen::withCount('dokumen')
            ->orderBy('dokumen_count', 'desc')
            ->first();
    }
    
    /**
     * Get media by type
     */
    private function getMediaByType()
    {
        return Media::select(DB::raw('SUBSTRING_INDEX(mime_type, "/", 1) as type'), DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->count];
            });
    }
    
    /**
     * Get latest changes
     */
    private function getLatestChanges()
    {
        return RiwayatPerubahan::with(['dokumen', 'dokumen.jenis'])
            ->latest()
            ->take(10)
            ->get();
    }
    
    /**
     * Get most active documents (most revisions)
     */
    private function getMostActiveDocuments()
    {
        return DokumenHukum::withCount('riwayat')
            ->orderBy('riwayat_count', 'desc')
            ->take(5)
            ->get();
    }
    
    /**
     * Get user registrations for chart
     */
    private function getUserRegistrations()
    {
        return User::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('DATE_FORMAT(created_at, "%b") as month_name')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month', 'month_name')
            ->orderBy('month')
            ->get();
    }
    
    /**
     * Get dokumen registrations for chart
     */
    private function getDokumenRegistrations()
    {
        return DokumenHukum::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('DATE_FORMAT(created_at, "%b") as month_name')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month', 'month_name')
            ->orderBy('month')
            ->get();
    }
    
    /**
     * Get dokumen monthly statistics
     */
    private function getDokumenMonthly()
    {
        $months = [];
        $currentYear = Carbon::now()->year;
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('M');
            $count = DokumenHukum::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
            
            $months[] = [
                'month' => $monthName,
                'count' => $count
            ];
        }
        
        return $months;
    }
    
    /**
     * Get status distribution
     */
    private function getStatusDistribution()
    {
        return DokumenHukum::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'count' => $item->count
                ];
            })
            ->values();
    }
    
    /**
     * Get expiring documents
     */
   
    
    /**
     * Check database connection
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'connected',
                'message' => 'Database connection successful',
                'icon' => 'check-circle',
                'color' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'disconnected',
                'message' => $e->getMessage(),
                'icon' => 'x-circle',
                'color' => 'danger'
            ];
        }
    }
    
    /**
     * Check storage status
     */
    private function checkStorage()
    {
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;
        $percentage = ($totalSpace > 0) ? round(($usedSpace / $totalSpace) * 100, 2) : 0;
        
        $status = 'good';
        $color = 'success';
        
        if ($percentage > 90) {
            $status = 'critical';
            $color = 'danger';
        } elseif ($percentage > 75) {
            $status = 'warning';
            $color = 'warning';
        }
        
        return [
            'total' => $this->formatBytes($totalSpace),
            'used' => $this->formatBytes($usedSpace),
            'free' => $this->formatBytes($freeSpace),
            'percentage' => $percentage,
            'status' => $status,
            'color' => $color
        ];
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    /**
     * Check cache status
     */
    private function checkCache()
    {
        try {
            cache()->put('health_check', 'ok', 10);
            $status = cache()->get('health_check') === 'ok' ? 'active' : 'inactive';
            
            return [
                'status' => $status,
                'driver' => config('cache.default'),
                'icon' => $status === 'active' ? 'check-circle' : 'x-circle',
                'color' => $status === 'active' ? 'success' : 'warning'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'icon' => 'x-circle',
                'color' => 'danger'
            ];
        }
    }
    
    /**
     * Get system uptime (simulated)
     */
    private function getSystemUptime()
    {
        // Simulasi uptime - di production bisa pakai command
        $startTime = cache()->remember('system_start_time', 3600, function () {
            return Carbon::now();
        });
        
        $uptime = $startTime->diffForHumans(null, true);
        
        return [
            'started' => $startTime->format('Y-m-d H:i:s'),
            'uptime' => $uptime,
            'days' => $startTime->diffInDays()
        ];
    }
    
    /**
     * Get average processing time
     */
    private function getAverageProcessingTime()
    {
        // Asumsi ada created_at dan updated_at
        $avgTime = DokumenHukum::selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->where('status', 'approved')
            ->first();
        
        return round($avgTime->avg_hours ?? 0, 2);
    }
    
    /**
     * Get completion rate
     */
    private function getCompletionRate()
    {
        $total = DokumenHukum::count();
        $completed = DokumenHukum::where('status', 'approved')->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }
    
    /**
     * Get revision count
     */
    private function getRevisionCount()
    {
        return DokumenHukum::has('riwayat', '>', 0)->count();
    }
    
    /**
     * Estimate total pages
     */
    private function estimateTotalPages()
    {
        // Simulasi estimasi halaman
        $totalDokumen = DokumenHukum::count();
        return $totalDokumen * 5; // Asumsi rata-rata 5 halaman per dokumen
    }
    
    /**
     * Get most viewed documents
     */

    
    /**
     * Get user activity
     */
    
    
    /**
     * Get total logins (simulated)
     */
    private function getTotalLogins()
    {
        // Simulasi - di production bisa pakai activity log
        return rand(100, 500);
    }
    
    /**
     * Get yearly comparison
     */
    private function getYearlyComparison()
    {
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;
        
        $currentYearCount = DokumenHukum::whereYear('created_at', $currentYear)->count();
        $lastYearCount = DokumenHukum::whereYear('created_at', $lastYear)->count();
        
        $growth = $lastYearCount > 0 
            ? round((($currentYearCount - $lastYearCount) / $lastYearCount) * 100, 2)
            : ($currentYearCount > 0 ? 100 : 0);
        
        return [
            'current_year' => $currentYearCount,
            'last_year' => $lastYearCount,
            'growth' => $growth,
            'trend' => $growth >= 0 ? 'up' : 'down'
        ];
    }
    
    /**
     * Get most productive day
     */
    private function getMostProductiveDay()
    {
        $mostProductive = DokumenHukum::select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day')
            ->orderBy('count', 'desc')
            ->first();
        
        return $mostProductive ? [
            'day' => $mostProductive->day,
            'count' => $mostProductive->count
        ] : null;
    }
    
    /**
     * Get busiest hour
     */
    private function getBusiestHour()
    {
        $busiestHour = DokumenHukum::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->first();
        
        return $busiestHour ? [
            'hour' => $busiestHour->hour,
            'count' => $busiestHour->count
        ] : null;
    }
}
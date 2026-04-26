<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPerubahan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_perubahan';
    protected $primaryKey = 'riwayat_id';
    
    protected $fillable = [
        'dokumen_id',
        'tanggal',
        'uraian_perubahan',
        'versi',
        'pembuat',
        'tipe_perubahan'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relationship dengan DokumenHukum
     */
    public function dokumen()
    {
        return $this->belongsTo(DokumenHukum::class, 'dokumen_id', 'dokumen_id');
    }

    /**
     * Scope untuk filter berdasarkan dokumen
     */
    public function scopeByDokumen($query, $dokumenId)
    {
        return $query->where('dokumen_id', $dokumenId);
    }

    /**
     * Scope untuk urutkan berdasarkan tanggal terbaru
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('tanggal', 'desc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk filter berdasarkan tipe perubahan
     */
    public function scopeByTipePerubahan($query, $tipe)
    {
        return $query->where('tipe_perubahan', $tipe);
    }

    /**
     * Accessor untuk format tanggal
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal->format('d/m/Y');
    }

    /**
     * Accessor untuk versi lengkap
     */
    public function getVersiLengkapAttribute()
    {
        return "v{$this->versi}";
    }

    /**
     * Accessor untuk preview uraian
     */
    public function getUraianSingkatAttribute()
    {
        return Str::limit($this->uraian_perubahan, 100);
    }

    /**
     * Generate versi berikutnya
     */
    public static function generateNextVersion($dokumenId)
    {
        $latest = self::where('dokumen_id', $dokumenId)
                     ->orderBy('created_at', 'desc')
                     ->first();
        
        if (!$latest) {
            return '1.0';
        }

        // Split versi menjadi major.minor
        $parts = explode('.', $latest->versi);
        if (count($parts) == 2) {
            $major = (int)$parts[0];
            $minor = (int)$parts[1] + 1;
            return "{$major}.{$minor}";
        }
        
        return $latest->versi . '.1';
    }
}
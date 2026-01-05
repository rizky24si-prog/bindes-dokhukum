<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampiranDokumen extends Model
{
    use HasFactory;

    protected $table = 'lampiran_dokumen';
    protected $primaryKey = 'lampiran_id';
    
    protected $fillable = [
        'dokumen_id',
        'keterangan',
        'media_id',
        'nama_file',
        'ukuran_file',
        'tipe_file'
    ];

    /**
     * Relationship dengan DokumenHukum
     */
    public function dokumen()
    {
        return $this->belongsTo(DokumenHukum::class, 'dokumen_id', 'dokumen_id');
    }

    /**
     * Relationship dengan Media
     */
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'media_id');
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
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessor untuk ukuran file format
     */
    public function getUkuranFileFormattedAttribute()
    {
        if (!$this->ukuran_file) return '-';
        
        $bytes = $this->ukuran_file;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Accessor untuk icon berdasarkan tipe file
     */
    public function getFileIconAttribute()
    {
        if (!$this->tipe_file) return 'fa-file';
        
        $mime = $this->tipe_file;
        
        if (str_contains($mime, 'pdf')) return 'fa-file-pdf';
        if (str_contains($mime, 'word') || str_contains($mime, 'doc')) return 'fa-file-word';
        if (str_contains($mime, 'excel') || str_contains($mime, 'sheet') || str_contains($mime, 'xls')) return 'fa-file-excel';
        if (str_contains($mime, 'image')) return 'fa-file-image';
        if (str_contains($mime, 'powerpoint') || str_contains($mime, 'presentation') || str_contains($mime, 'ppt')) return 'fa-file-powerpoint';
        if (str_contains($mime, 'zip') || str_contains($mime, 'rar') || str_contains($mime, 'archive')) return 'fa-file-archive';
        if (str_contains($mime, 'text')) return 'fa-file-alt';
        
        return 'fa-file';
    }

    /**
     * Accessor untuk warna badge berdasarkan tipe file
     */
    public function getFileBadgeColorAttribute()
    {
        if (!$this->tipe_file) return 'secondary';
        
        $mime = $this->tipe_file;
        
        if (str_contains($mime, 'pdf')) return 'danger';
        if (str_contains($mime, 'word') || str_contains($mime, 'doc')) return 'primary';
        if (str_contains($mime, 'excel') || str_contains($mime, 'sheet') || str_contains($mime, 'xls')) return 'success';
        if (str_contains($mime, 'image')) return 'info';
        if (str_contains($mime, 'powerpoint') || str_contains($mime, 'presentation') || str_contains($mime, 'ppt')) return 'warning';
        
        return 'secondary';
    }

    /**
     * Accessor untuk URL file
     */
    public function getFileUrlAttribute()
    {
        if ($this->media && $this->media->full_url) {
            return $this->media->full_url;
        }
        return null;
    }

    /**
     * Accessor untuk nama file yang ditampilkan
     */
    public function getNamaFileDisplayAttribute()
    {
        return $this->nama_file ?? ($this->media ? $this->media->file_name : 'File');
    }
}
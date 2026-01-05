<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'media';
    protected $primaryKey = 'media_id';
    
    protected $fillable = [
        'ref_table',
        'ref_id',
        'file_name',
        'caption',
        'mime_type',
        'sort_order'
    ];
    
    protected $casts = [
        'sort_order' => 'integer',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get file path (generated based on ref_table and file_name)
     */
     public function getFilePathAttribute()
    {
        if (!$this->ref_table || !$this->file_name) {
            return null;
        }
        
        // Konversi underscore ke hyphen untuk folder name
        $tableFolder = str_replace('_', '-', $this->ref_table);
        return $tableFolder . '/' . $this->file_name;
    }

    /**
     * Get full URL - PERBAIKAN INI
     */
    public function getFullUrlAttribute()
    {
        $filePath = $this->file_path;
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->url($filePath);
        }
        return null;
    }

    /**
     * Get physical path for debugging
     */
    public function getPhysicalPathAttribute()
    {
        $filePath = $this->file_path;
        if ($filePath) {
            return storage_path('app/public/' . $filePath);
        }
        return null;
    }

    /**
     * Check if file exists physically
     */
    public function fileExists()
    {
        $filePath = $this->file_path;
        if ($filePath) {
            return Storage::disk('public')->exists($filePath);
        }
        return false;
    }
    /**
     * Get storage path
     */
    public function getStoragePathAttribute()
    {
        $filePath = $this->file_path;
        if ($filePath) {
            return storage_path('app/public/' . $filePath);
        }
        return null;
    }

    /**
     * Check if file is image
     */
    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if file is PDF
     */
    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Get file extension
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Scope untuk filter berdasarkan tabel referensi
     */
    public function scopeForTable($query, $tableName)
    {
        return $query->where('ref_table', $tableName);
    }

    /**
     * Scope untuk filter berdasarkan referensi
     */
    public function scopeForReference($query, $tableName, $refId)
    {
        return $query->where('ref_table', $tableName)
                     ->where('ref_id', $refId);
    }

    /**
     * Scope untuk urutkan berdasarkan sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get related model based on ref_table
     */
    public function getModelAttribute()
    {
        if (!$this->ref_table || !$this->ref_id) {
            return null;
        }

        // Mapping tabel ke model
        $modelMapping = [
            'users' => \App\Models\User::class,
            'warga' => \App\Models\Warga::class,
            'dokumen_hukum' => \App\Models\DokumenHukum::class,
            'lampiran_dokumen' => \App\Models\LampiranDokumen::class,
        ];

        $modelClass = $modelMapping[$this->ref_table] ?? null;
        
        if ($modelClass && class_exists($modelClass)) {
            return $modelClass::find($this->ref_id);
        }

        return null;
    }

    /**
     * Delete physical file when media is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($media) {
            // Delete physical file on hard delete
            if ($media->isForceDeleting()) {
                $filePath = $media->file_path;
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        });
    }
}
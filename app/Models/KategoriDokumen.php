<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
    protected $table = 'kategori'; // Nama tabel: kategori
    protected $primaryKey = 'kategori_id';
    public $timestamps = false;
    
    protected $fillable = ['nama', 'deskripsi'];
    
    // Relasi ke DokumenHukum
    public function dokumenHukum()
    {
        return $this->hasMany(DokumenHukum::class, 'kategori_id', 'kategori_id');
    }
    
    // Alias untuk kompatibilitas
    public function dokumen()
    {
        return $this->hasMany(DokumenHukum::class, 'kategori_id', 'kategori_id');
    }
}
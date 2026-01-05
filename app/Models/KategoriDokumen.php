<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    
    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function dokumenHukum()
    {
        return $this->hasMany(DokumenHukum::class, 'kategori_id', 'kategori_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function getJumlahDokumenAttribute()
    {
        return $this->dokumenHukum()->count();
    }
}
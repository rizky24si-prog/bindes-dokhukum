<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenHukum extends Model
{
    protected $table = 'dokumen_hukum';
    protected $primaryKey = 'dokumen_id';
    public $timestamps = true;
    
    protected $fillable = [
        'jenis_id', 
        'kategori_id', 
        'nomor', 
        'judul', 
        'tanggal', 
        'ringkasan', 
        'status'
    ];
    
    protected $dates = ['tanggal'];
    
    // Relasi ke JenisDokumen
    public function jenis()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_id', 'jenis_id');
    }
    
    // Relasi ke KategoriDokumen
    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_id', 'kategori_id');
    }
    
    // Relasi ke RiwayatPerubahan
    public function riwayat()
    {
        return $this->hasMany(RiwayatPerubahan::class, 'dokumen_id', 'dokumen_id');
    }
    
    // Relasi ke LampiranDokumen
    public function lampiran()
    {
        return $this->hasMany(LampiranDokumen::class, 'dokumen_id', 'dokumen_id');
    }

}
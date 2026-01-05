<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMedia;

class DokumenHukum extends Model
{
    use HasFactory, HasMedia;

    protected $table = 'dokumen_hukum';
    protected $primaryKey = 'dokumen_id';
    
    protected $fillable = [
        'jenis_id',
        'kategori_id',
        'nomor',
        'judul',
        'tanggal',
        'ringkasan',
        'status'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jenis()
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_id', 'jenis_id');
    }
    
    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Upload berkas dokumen
     */
    public function uploadBerkas($file, string $caption = null)
    {
        return $this->uploadFile($file, $caption, 0);
    }
}
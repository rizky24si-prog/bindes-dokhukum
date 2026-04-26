<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    protected $table = 'jenis_dokumen';
    protected $primaryKey = 'jenis_id';
    public $timestamps = true;
    
    protected $fillable = ['nama_jenis', 'deskripsi'];
    
    // Relasi ke DokumenHukum
    public function dokumenHukum()
    {
        return $this->hasMany(DokumenHukum::class, 'jenis_id', 'jenis_id');
    }
    
    // Alias untuk kompatibilitas
    public function dokumen()
    {
        return $this->hasMany(DokumenHukum::class, 'jenis_id', 'jenis_id');
    }

        protected $dates = ['created_at', 'updated_at'];


}
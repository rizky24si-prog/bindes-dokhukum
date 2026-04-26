<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMedia;

class Warga extends Model
{
    use HasFactory, HasMedia;

    protected $table = 'warga';
    protected $primaryKey = 'warga_id';
    
    protected $fillable = [
        'no_ktp',
        'nama',
        'jenis_kelamin',
        'agama',
        'pekerjaan',
        'telp',
        'email'
    ];
    
    protected $casts = [
        'no_ktp' => 'string'
    ];

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_ktp', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telp', 'like', "%{$search}%");
    }

    /**
     * Scope untuk filter jenis kelamin
     */
    public function scopeByJenisKelamin($query, $jenisKelamin)
    {
        if ($jenisKelamin) {
            return $query->where('jenis_kelamin', $jenisKelamin);
        }
        return $query;
    }

    /**
     * Upload foto profil
     */
    public function uploadFotoProfil($file, string $caption = null)
    {
        return $this->uploadFile($file, $caption, 0);
    }

    /**
     * Get foto profil URL
     */
    public function getFotoProfilUrlAttribute()
    {
        return $this->foto_thumbnail;
    }

    /**
     * Accessor untuk jenis kelamin lengkap
     */
    public function getJenisKelaminTextAttribute()
    {
        return $this->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Accessor untuk nama dengan gelar (jika ada)
     */
    public function getNamaLengkapAttribute()
    {
        return $this->nama;
    }

    /**
     * Mutator untuk no_ktp - format dengan strip
     */
    public function setNoKtpAttribute($value)
    {
        // Hapus semua karakter non-digit
        $cleanValue = preg_replace('/[^0-9]/', '', $value);
        $this->attributes['no_ktp'] = $cleanValue;
    }

    /**
     * Accessor untuk no_ktp - format dengan strip
     */
    public function getNoKtpFormattedAttribute()
    {
        $ktp = $this->no_ktp;
        if (strlen($ktp) == 16) {
            return substr($ktp, 0, 4) . '.' . 
                   substr($ktp, 4, 4) . '.' . 
                   substr($ktp, 8, 4) . '.' . 
                   substr($ktp, 12, 4);
        }
        return $ktp;
    }

    /**
     * Validation rules untuk create
     */
    public static function getCreateRules()
    {
        return [
            'no_ktp' => 'required|string|size:16|unique:warga,no_ktp',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:50',
            'telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:warga,email',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Validation rules untuk update
     */
    public static function getUpdateRules($wargaId)
    {
        return [
            'no_ktp' => 'required|string|size:16|unique:warga,no_ktp,' . $wargaId . ',warga_id',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:50',
            'telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:warga,email,' . $wargaId . ',warga_id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}
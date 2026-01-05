<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasMedia;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasMedia;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Upload foto profil (convenience method)
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
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// app/Models/User.php
class User extends Authenticatable
{
    // ...
    protected $primaryKey = 'id_akun';

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id_role');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_akun', 'id_akun');
    }
}

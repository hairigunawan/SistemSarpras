<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\RedirectResponse;

// app/Models/Role.php
class Role extends Model
{
    // ...
    protected $primaryKey = 'id_role';

    protected $fillable = [
        'nama_role'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id_role');
    }

    public static function cekRole($role)
    {
        $r = Role::where('nama_role', $role)->first();

        if (! $r) {
            return back()->withErrors(['role' => 'Role tidak ditemukan.'])->withInput();
        }

        return $r;
    }
}

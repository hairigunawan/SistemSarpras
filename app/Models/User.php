<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_akun';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role_id',
        'nomor_telepon',
        'provider',
        'provider_id',
        'token',
        'avatar',
        'refresh_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id_role');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_akun', 'id_akun');
    }

    public static function storeAkun(array $data)
    {
        return self::create([
            'nama'          => $data['nama'],
            'email'         => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
            'role_id'       => $data['role_id'],
            'password'      => $data['password'], 
        ]);
    }

    public function updateAkun(array $data)
    {
        $updateData = [
            'nama'          => $data['nama'],
            'email'         => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
            'role_id'       => $data['role_id'],
        ];

        // Cek apakah ada password baru yang diinput
        if (!empty($data['password'])) {
            $updateData['password'] = $data['password']; 
        }

        return $this->update($updateData);
    }

    public function deleteAkun()
    {
        return $this->delete();
    }
}
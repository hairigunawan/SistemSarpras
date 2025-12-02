<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public static function Login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah user dengan email ini ada
        $u = User::where('email', $request->email)->first();

        if (!$u) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        // Cek password
        if (!Hash::check($request->password, $u->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ]);
        }

        // Login user
        Auth::login($u);

        // Arahkan user berdasarkan peran
        if ($u->userRole->nama_role === 'Admin') {
            return redirect()->route('admin.dashboard.index');
        } else {
            return redirect()->route('public.beranda.index.auth');
        }
    }

    Public static function Register(Request $request){

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/|unique:users,nomor_telepon',
            'role' => 'required|in:Dosen,Mahasiswa',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $role = Role::where('nama_role', $validated['role'])->first();

        if (! $role) {
            return back()->withErrors(['role' => 'Role tidak ditemukan.'])->withInput();
        }

        $u = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id_role,
        ]);

        Auth::login($u);

        return $u;
    }

    Public static function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
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

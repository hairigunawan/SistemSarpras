<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role;

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

    public static function HalamanUtama(Request $request)
    {
        $u = User::query()
            ->filter($request->all())
            ->latest()
            ->paginate(9);

        return view('admin.akun.index', compact('u'));
    }

    public function scopeFilter($query, $filters)
    {
        // Filter berdasarkan nama
        if (!empty($filters['nama'])) {
            $query->where('nama', 'like', '%' . $filters['nama'] . '%');
        }

        // Filter berdasarkan email
        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        // Filter search (nama atau email)
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query;
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

    public static function Register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/|unique:users,nomor_telepon',
            'role' => 'required|in:Dosen,Mahasiswa',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Perbaikan: ambil role yang benar
        $r = Role::CekRole($request->role);

        // jika cekRole mengembalikan redirect, hentikan proses
        if (!($r instanceof Role)) {
            return $r;
        }

        $u = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'password' => Hash::make($validated['password']),
            'role_id' => $r->id_role,
        ]);

        Auth::login($u);

        return $u;
    }
    public static function EditAkun(Request $request, User $akun)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($akun->id_akun, 'id_akun')],
            'role_id' => 'required|exists:roles,id_role',
            'nomor_telepon' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $akun->nama = $request->nama;
        $akun->email = $request->email;
        $akun->role_id = $request->role_id;
        $akun->nomor_telepon = $request->nomor_telepon;

        if ($request->filled('password')) {
            $akun->password = Hash::make($request->password);
        }

        $akun->save();

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public static function Logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }


    public static function storeAkun(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'nomor_telepon' => 'nullable|string|max:20',
            'password'      => 'required|string|min:8|confirmed',
            'role_id'       => 'required|exists:roles,id_role',
        ]);

        // Buat user baru
        $u = User::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'nomor_telepon' => $validatedData['nomor_telepon'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => $validatedData['role_id'],
        ]);

        return $u;
    }


    public static function HapusAkun(User $akun)
    {
        if (Auth::check() && Auth::id() === $akun->getKey()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $akun->delete();

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil dihapus.');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id_akun';

    protected $fillable = [
        'nama',
        'email',
        'email_domain',
        'password',
        'role_id',
        'nomor_telepon',
        'verification_code',
        'is_verified',
        'provider',
        'provider_id',
        'token',
        'avatar',
        'refresh_token',
        'last_login_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'last_login_at' => 'datetime',
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
            $query->where('nama', 'ilike', '%' . $filters['nama'] . '%');
        }

        // Filter berdasarkan email
        if (!empty($filters['email'])) {
            $query->where('email', 'ilike', '%' . $filters['email'] . '%');
        }

        // Filter search (nama atau email)
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama', 'ilike', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'ilike', '%' . $filters['search'] . '%');
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

        $u = User::where('email', $request->email)->first();

        if (!$u) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        if (!Hash::check($request->password, $u->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ]);
        }

        Auth::login($u);

        if ($u->userRole->nama_role === 'Admin') {
            Auth::logoutOtherDevices($request->password);
            return redirect()->route('admin.dashboard.index');
        } else {
            return redirect()->route('public.beranda.index.auth');
        }
    }

    public static function Register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    $existingUser = User::where('email', $value)->first();
                    if ($existingUser) {
                        if (!$existingUser->is_verified) {
                            $fail('Email ini sudah terdaftar tapi belum diverifikasi. Silakan cek email Anda atau kirim ulang kode verifikasi.');
                        } else {
                            $fail('Email ini sudah terdaftar di sistem. Silakan login atau gunakan email lain.');
                        }
                    }
                }
            ],
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/|unique:users,nomor_telepon',
            'role' => 'required|in:Dosen,Mahasiswa',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validated['role'] === 'Mahasiswa') {
            $emailDomain = explode('@', $validated['email'])[1] ?? '';
            if ($emailDomain !== 'mhs.politala.ac.id') {
                return back()->withErrors([
                    'email' => 'Mahasiswa harus menggunakan email domain @mhs.politala.ac.id'
                ])->withInput();
            }
        }

        $r = Role::CekRole($request->role);

        if (!($r instanceof Role)) {
            return $r;
        }

        $verificationCode = mt_rand(100000, 999999);

        $u = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'email_domain' => $emailDomain,
            'nomor_telepon' => $validated['nomor_telepon'],
            'password' => Hash::make($validated['password']),
            'role_id' => $r->id_role,
            'verification_code' => $verificationCode,
            'is_verified' => false,
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($u->email)->send(new \App\Mail\VerificationEmail($u, $verificationCode));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage());
        }
        return $u;
    }

    public function sendVerificationEmail()
    {
        $verificationCode = mt_rand(100000, 999999);
        $this->verification_code = $verificationCode;
        $this->save();

        try {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\VerificationEmail($this, $verificationCode));
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage());
            return false;
        }
    }

    public function verifyEmail($code)
    {
        if ($this->verification_code === $code && !$this->is_verified) {
            $this->is_verified = true;
            $this->email_verified_at = now();
            $this->verification_code = null;
            $this->save();
            return true;
        }
        return false;
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
        $validatedData = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'nomor_telepon' => 'nullable|string|max:20',
            'password'      => 'required|string|min:8|confirmed',
            'role_id'       => 'required|exists:roles,id_role',
        ]);

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

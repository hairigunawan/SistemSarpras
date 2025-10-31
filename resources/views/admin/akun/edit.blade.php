<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Akun</title></head>
<body>
	<h1>Edit Akun</h1>
	<form method="POST" action="{{ route('akun.update', $akun) }}">
		@csrf
		@method('PUT')
		<label>Nama: <input name="nama" value="{{ old('nama', $akun->nama ?? '') }}"></label><br>
		<label>Email: <input name="email" value="{{ old('email', $akun->email ?? '') }}"></label><br>
		<label>Password (kosongkan bila tidak diubah): <input type="password" name="password"></label><br>
		<label>Confirm Password: <input type="password" name="password_confirmation"></label><br>
		<label>Role:
			<select name="role_id">
				@foreach($roles ?? [] as $r)
					<option value="{{ $r->id_role }}" {{ (old('role_id', $akun->role_id ?? '') == $r->id_role) ? 'selected' : '' }}>
						{{ $r->nama_role ?? $r->id_role }}
					</option>
				@endforeach
			</select>
		</label><br>
		<button type="submit">Simpan</button>
	</form>
	<a href="{{ route('akun.index') }}">Kembali</a>
</body>
</html>

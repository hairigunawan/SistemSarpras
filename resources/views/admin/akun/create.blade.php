<!doctype html>
<html>
<head><meta charset="utf-8"><title>Buat Akun</title></head>
<body>
	<h1>Buat Akun</h1>
	<form method="POST" action="{{ route('akun.store') }}">
		@csrf
		<label>Nama: <input name="nama" value="{{ old('nama') }}"></label><br>
		<label>Email: <input name="email" value="{{ old('email') }}"></label><br>
		<label>Password: <input type="password" name="password"></label><br>
		<label>Confirm Password: <input type="password" name="password_confirmation"></label><br>
		<label>Role:
			<select name="role_id">
				@foreach($roles ?? [] as $r)
					<option value="{{ $r->id_role }}">{{ $r->nama_role ?? $r->id_role }}</option>
				@endforeach
			</select>
		</label><br>
		<button type="submit">Simpan</button>
	</form>
	<a href="{{ route('akun.index') }}">Kembali</a>
</body>
</html>

<!doctype html>
<html>
<head><meta charset="utf-8"><title>Akun - Index</title></head>
<body>
	@if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
	@if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif

	<h1>Daftar Akun</h1>
	<!-- Minimal list output, replace with actual table markup -->
	<ul>
	@foreach($akuns ?? [] as $a)
		<li>{{ $a->nama ?? '—' }} ({{ $a->email ?? '—' }})</li>
	@endforeach
	</ul>

	<a href="{{ route('akun.create') }}">Buat Akun Baru</a>
</body>
</html>

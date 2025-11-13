<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang Kami - Sarpras</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .nav-scroll {
      background-color: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="font-sans antialiased bg-gray-50">

  <!-- Navbar -->
  <header id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300">
    <nav class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6 text-white">
      <div class="flex items-center space-x-2">
        <div class="bg-blue-600 text-white font-bold px-2 py-1 rounded">■</div>
        <h1 class="font-bold text-xl">Sarpras</h1>
      </div>
      <ul class="hidden md:flex space-x-8 font-medium">
        <li><a href="{{ url('/profile/beranda') }}" class="hover:text-blue-400">Beranda</a></li>
        <li><a href="#" class="text-blue-400 font-semibold">Tentang Kami</a></li>
        <li><a href="#visi" class="hover:text-blue-400">Visi & Misi</a></li>
        <li><a href="#tim" class="hover:text-blue-400">Tim</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero -->
  <section class="h-[60vh] bg-cover bg-center flex flex-col justify-center items-center text-white"
    style="background-image: linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)), url('{{ asset('images/bg-campus.jpg') }}');">
    <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang Kami</h1>
    <p class="max-w-2xl text-center">Kami adalah penyedia solusi manajemen fasilitas terintegrasi untuk mendukung efisiensi dan keberlanjutan aset infrastruktur.</p>
  </section>

  <!-- Profil -->
  <section class="py-20 max-w-6xl mx-auto px-6 text-gray-700">
    <h2 class="text-3xl font-bold mb-6 text-blue-600">Profil Perusahaan</h2>
    <p class="mb-6 leading-relaxed">
      <strong>Sarpras</strong> berdiri dengan komitmen untuk memberikan solusi modern di bidang pengelolaan sarana dan prasarana. Kami memadukan teknologi dengan pengalaman manajerial agar setiap aset dapat digunakan secara optimal dan efisien.
    </p>
    <p class="leading-relaxed">
      Dengan tim profesional dan sistem manajemen berbasis data, kami terus berinovasi untuk menghadirkan layanan yang mendukung kebutuhan lembaga pendidikan, instansi pemerintahan, dan sektor swasta.
    </p>
  </section>

  <!-- Visi & Misi -->
  <section id="visi" class="py-16 bg-white text-center">
    <h2 class="text-3xl font-bold mb-10 text-blue-600">Visi & Misi</h2>
    <div class="grid md:grid-cols-2 gap-10 max-w-5xl mx-auto px-6">
      <div class="p-6 bg-gray-50 shadow rounded-xl">
        <h3 class="text-2xl font-semibold mb-4 text-blue-500">Visi</h3>
        <p>Menjadi perusahaan penyedia solusi sarana dan prasarana terbaik di Indonesia dengan inovasi berkelanjutan.</p>
      </div>
      <div class="p-6 bg-gray-50 shadow rounded-xl">
        <h3 class="text-2xl font-semibold mb-4 text-blue-500">Misi</h3>
        <ul class="list-disc text-left ml-6 space-y-2">
          <li>Menyediakan layanan manajemen fasilitas yang terintegrasi dan efisien.</li>
          <li>Mendorong inovasi digital dalam pengelolaan aset dan infrastruktur.</li>
          <li>Membangun hubungan jangka panjang dengan klien berdasarkan kepercayaan dan kualitas.</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Tim -->
  <section id="tim" class="py-20 bg-gray-100 text-center">
    <h2 class="text-3xl font-bold mb-10 text-gray-800">Tim Kami</h2>
    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">
      <div class="bg-white rounded-xl shadow p-6">
        <img src="{{ asset('images/person1.jpg') }}" alt="CEO" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
        <h3 class="text-lg font-semibold">Andi Pratama</h3>
        <p class="text-blue-500">CEO</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6">
        <img src="{{ asset('images/person2.jpg') }}" alt="CTO" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
        <h3 class="text-lg font-semibold">Rina Dewi</h3>
        <p class="text-blue-500">CTO</p>
      </div>
      <div class="bg-white rounded-xl shadow p-6">
        <img src="{{ asset('images/person3.jpg') }}" alt="Manager" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
        <h3 class="text-lg font-semibold">Bagus Santoso</h3>
        <p class="text-blue-500">Manager Operasional</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-6 text-center text-gray-500 bg-gray-200">
    <p>© 2025 Sarpras. Semua Hak Dilindungi.</p>
  </footer>

  <script>
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar.classList.add('nav-scroll');
        navbar.classList.remove('text-white');
        navbar.querySelector('nav').classList.add('text-gray-800');
      } else {
        navbar.classList.remove('nav-scroll');
        navbar.querySelector('nav').classList.remove('text-gray-800');
        navbar.querySelector('nav').classList.add('text-white');
      }
    });
  </script>
</body>
</html>

# Konfigurasi Deployment Cloud (Laravel Cloud / Heroku / dll)

Agar gambar yang diunggah (Ruangan & Proyektor) tidak hilang saat redeploy, sistem telah dikonfigurasi untuk menggunakan **Object Storage (S3 / Cloudflare R2 / DigitalOcean Spaces)**.

## 1. Pastikan Environment Variable Terisi
Tambahkan konfigurasi berikut di dashboard environment variable server Anda:

```env
# Ganti 'local' atau 'public' menjadi 's3'
FILESYSTEM_DISK=s3

# Konfigurasi S3 (Contoh untuk AWS S3)
AWS_ACCESS_KEY_ID=your_access_key_id
AWS_SECRET_ACCESS_KEY=your_secret_access_key
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=nama-bucket-anda
AWS_URL=https://nama-bucket-anda.s3.ap-southeast-1.amazonaws.com

# Jika menggunakan Cloudflare R2 / MinIO / DigitalOcean Spaces:
# AWS_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
# AWS_USE_PATH_STYLE_ENDPOINT=false
```

## 2. Perubahan Kode yang Telah Dilakukan
1. **Model (`App\Models\Ruangan` & `Proyektor`):** 
   - Fungsi upload sekarang menggunakan disk `s3`.
2. **View (`Blade Templates`):**
   - Gambar sarpras sekarang dipanggil menggunakan `Storage::url()` bukan `asset('storage/...')`.
   - Gambar statis (logo, background) dipanggil menggunakan `asset('img/...')` dari folder `public/img`.

## 3. Catatan Tambahan
- Folder `storage/app/public` tidak lagi digunakan untuk menyimpan gambar upload baru.
- Pastikan folder `public/img` ikut ter-commit ke repository karena berisi aset statis website.

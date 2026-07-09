# Runbook & Prosedur Kerja — PMRJ Anggota

Dokumen ini berisi prosedur kerja, instruksi operasional, dan panduan pemeliharaan sistem untuk para pengembang dan Agen AI yang bekerja pada aplikasi PMRJ Anggota.

---

## 1. Persiapan Lingkungan Lokal (Setup)

Ikuti langkah-langkah berikut untuk menjalankan sistem secara lokal:

1. **Instal Dependensi**:
   Pastikan composer terinstall, lalu jalankan:
   ```bash
   composer install
   ```

2. **Konfigurasi Environment (`.env`)**:
   Salin berkas contoh `.env.example` menjadi `.env` dan sesuaikan koneksi database PostgreSQL Anda:
   ```text
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=pmrj
   DB_USERNAME=postgres
   DB_PASSWORD=your_password
   ```

3. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

4. **Inisialisasi Database (Migrasi & Seed)**:
   Jalankan perintah berikut untuk membuat struktur tabel dan mengisi data master penting (IKK, jenis kelamin, gol darah, status rumah, dll):
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Hubungkan Media Storage (Penting)**:
   Buat symbolic link dari folder storage lokal agar foto KTP dan profil anggota dapat diakses publik melalui browser:
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Aplikasi**:
   Jalankan development server bawaan Laravel:
   ```bash
   php artisan serve
   ```
   Aplikasi kini dapat diakses melalui browser pada alamat [http://localhost:8000](http://localhost:8000).

---

## 2. Prosedur Pengembangan (Development Workflow)

Setiap kali Anda ingin melakukan perubahan kode atau menambahkan fitur baru, ikuti prosedur berikut untuk meminimalkan risiko:

### Langkah 1: Pelajari Konteks & Aturan Bisnis
Sebelum mengubah kode, baca terlebih dahulu aturan bisnis pada [business-rules.md](file:///Users/munawir.fikri/playground/pmrj/pmrj-anggota/docs/project/business-rules.md). Ingat bahwa:
- Nomor anggota (`no_anggota`) harus selalu di-generate menggunakan format `PMRJ-{IKK_CODE}-{COUNTER}`.
- Perubahan IKK asal anggota wajib memicu regenerasi otomatis nomor anggota.
- Seluruh gambar yang diunggah harus dikompresi menjadi format JPG berkualitas 80% dengan lebar maksimum 800px menggunakan trait `CompressesImages`.

### Langkah 2: Buat Rencana Perubahan (CHG Record)
Untuk perubahan besar, buat berkas rencana perubahan di dalam direktori `docs/changes/active/` menggunakan nama berkas berformat `CHG-YYYY-NNN-deskripsi.md` dengan menyalin template dari [CHG-template.md](file:///Users/munawir.fikri/playground/pmrj/pmrj-anggota/docs/templates/CHG-template.md).

### Langkah 3: Menulis & Menjalankan Pengujian (Testing)
Pastikan setiap perubahan diuji. Untuk menguji alur autentikasi dan keanggotaan:
1. Tulis atau tambahkan kasus uji baru pada berkas [AnggotaTest.php](file:///Users/munawir.fikri/playground/pmrj/pmrj-anggota/tests/Feature/AnggotaTest.php).
2. Jalankan seluruh test suite menggunakan PHPUnit:
   ```bash
   vendor/bin/phpunit
   ```
3. Semua pengujian harus lulus 100% sebelum kode dapat digabungkan (*merge*).

### Langkah 4: Pembersihan Berkas di Storage
Pastikan kode pembaruan profil yang Anda buat selalu menghapus file foto profil/KTP lama di storage ketika pengguna mengunggah berkas baru. Hal ini penting untuk mencegah penumpukan berkas sampah. Gunakan:
```php
Storage::disk('public')->delete($oldPath);
```

### Langkah 5: Dokumentasikan Perubahan
- Pindahkan berkas rencana perubahan dari `docs/changes/active/` ke `docs/changes/completed/`.
- Perbarui daftar utang teknis pada [technical-debt.md](file:///Users/munawir.fikri/playground/pmrj/pmrj-anggota/docs/backlog/technical-debt.md) jika ada kompromi teknis yang dilakukan, atau tandai sebagai *Resolved* jika ada utang teknis yang diselesaikan.

---

## 3. Pemecahan Masalah (Troubleshooting)

### Gambar/Foto Anggota Tidak Muncul di Browser
- **Penyebab**: Symbolic link storage belum terbentuk atau terputus.
- **Solusi**: Hapus folder `public/storage` jika ada dalam bentuk folder biasa, lalu buat ulang symbolic link:
  ```bash
  rm -rf public/storage
  php artisan storage:link
  ```

### Perubahan Asal IKK Tidak Mengubah Nomor Anggota
- **Penyebab**: Nilai asal IKK diperbarui langsung ke database tanpa memicu *dirty checking* pada model Eloquent sebelum method `update()` dipanggil.
- **Solusi**: Pastikan Anda memeriksa perbedaan nilai asal IKK lama dengan asal IKK baru di controller sebelum method `$anggota->update($data)` dipanggil, seperti yang dicontohkan di [AnggotaController.php](file:///Users/munawir.fikri/playground/pmrj/pmrj-anggota/app/Http/Controllers/AnggotaController.php).

### Database Seeding Bentrok
- **Penyebab**: Terjadi bentrok data unik saat seeder dijalankan ulang karena tabel berisi data duplikat.
- **Solusi**: Selalu jalankan migrasi segar sebelum melakukan seeding:
  ```bash
  php artisan migrate:fresh --seed
  ```

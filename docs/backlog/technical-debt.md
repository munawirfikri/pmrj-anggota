# Technical Debt — Daftar Utang Teknis PMRJ Anggota

Dokumen ini mencatat hal-hal teknis yang belum optimal, penyimpangan dari praktik terbaik, atau fitur-fitur yang memerlukan peningkatan kualitas (*refactoring*) pada aplikasi PMRJ Anggota.

---

## Indeks Utang Teknis (Master Index)

| ID | Judul Utang Teknis | Kategori | Prioritas | Status | Pemilik |
|---|---|---|---|---|---|
| **TD-001** | Pengisian Data Master Langsung di Berkas Migrasi | Database | Medium | **Resolved** | Backend |
| **TD-002** | Kurangnya Test Coverage untuk Logika Utama | Test | High | **Resolved** | QA / Dev |
| **TD-003** | Tombol Download & Share Kartu Anggota Berupa Placeholder | Frontend | Low | **Resolved** | Frontend |
| **TD-004** | Belum Ada Mekanisme Log & Monitoring Terpusat | Operasional | Low | **Resolved** | DevOps |

---

## Rincian Analisis & Resolusi Utang Teknis

### TD-001: Pengisian Data Master Langsung di Berkas Migrasi
* **Deskripsi**: Pengisian data master (seperti data wilayah IKK, jenis kelamin, golongan darah, kota bagian, dan status rumah) dilakukan langsung melalui panggilan query `DB::table()->insert()` di dalam fungsi `up()` berkas migrasi database.
* **Resolusi**: Seluruh data master dipindahkan ke berkas seeder Laravel (`IkkSeeder.php` dan `MasterDataSeeder.php`) yang dipanggil melalui `DatabaseSeeder.php`. Berkas migrasi dibersihkan dari query insert agar hanya bertanggung jawab mengelola struktur skema tabel. Database dapat di-refresh dan di-seed bersih menggunakan `php artisan migrate:fresh --seed`.

### TD-002: Kurangnya Test Coverage untuk Logika Utama
* **Deskripsi**: Uji otomatis (*automated unit/feature tests*) saat ini hampir tidak ada. Logika krusial seperti pendaftaran anggota baru, penggantian foto profil beserta kompresi gambar, pengubahan nomor anggota otomatis saat asal IKK berubah, dan validasi keunikan NIK belum tercover oleh pengujian otomatis.
* **Resolusi**: Dibuat berkas pengujian fitur komprehensif pada [AnggotaTest.php](file:///Users/munawir.fikri/playground/pmrj/pmrj-anggota/tests/Feature/AnggotaTest.php) yang menguji alur registrasi, validasi NIK 16 digit, autentikasi login (sukses dan gagal), serta pembaruan profil yang mencakup unggah foto profil & regenerasi otomatis nomor anggota. Semua pengujian lulus 100%. *(Catatan: Penulisan pengujian ini juga berhasil mengungkap dan memperbaiki bug kritis pada AnggotaController di mana perbandingan asal IKK sebelumnya tidak terdeteksi).*

### TD-003: Tombol Download & Share Kartu Anggota Berupa Placeholder
* **Deskripsi**: Tombol untuk mengunduh (*download*) atau membagikan (*share*) kartu anggota masih berupa tautan kosong atau sekadar placeholder.
* **Resolusi**: Mengintegrasikan pustaka `html2canvas` dari CDN ke dalam berkas template [kartu.blade.php](file:///Users/munawir.fikri/playground/pmrj/pmrj-anggota/resources/views/dashboard/kartu.blade.php) sehingga kartu dapat diunduh langsung sebagai file gambar PNG beresolusi tinggi di sisi klien. Menyediakan implementasi Web Share API dan fallback salin ke clipboard untuk fitur pembagian tautan kartu anggota. Serta, mengganti placeholder gambar QR Code dengan QR Code dinamis riil menggunakan API `qrserver.com` yang berisi tautan verifikasi kartu anggota.

### TD-004: Belum Ada Mekanisme Log & Monitoring Terpusat
* **Deskripsi**: Aplikasi belum dilengkapi dengan logging khusus untuk aktivitas penting anggota.
* **Resolusi**: Menambahkan logging terstruktur menggunakan facade `Log` bawaan Laravel di `HomeController` dan `AnggotaController` untuk mencatat peristiwa penting:
  - Pendaftaran anggota sukses (disertai nama, ID, email, no_anggota).
  - Percobaan masuk (login) sukses dan gagal.
  - Sesi keluar (logout) anggota.
  - Pembaruan informasi profil anggota.
  - Perubahan/pembaruan kata sandi anggota.
  - Penghapusan berkas foto lama dari sistem penyimpanan ketika foto profil baru diunggah.
  - Rekalkulasi nomor anggota baru karena perpindahan kota IKK asal.

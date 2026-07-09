# Integration Map — Peta Integrasi Sistem PMRJ Anggota

Sistem Informasi Anggota PMRJ saat ini dirancang sebagai aplikasi monolitik yang beroperasi secara mandiri. Integrasi yang ada berpusat pada komponen-komponen downstream berikut:

---

## 1. Integrasi Database (Downstream Data Store)

Aplikasi terhubung langsung dengan **PostgreSQL Database** sebagai penyimpanan data persisten utama.

* **Driver**: `pgsql` (dikonfigurasi pada `.env` dengan variabel `DB_*`).
* **Fitur Database Terintegrasi**:
  * Pencarian data relasional anggota dan asal IKK.
  * Penyimpanan data transaksional status anggota (`pending`, `active`, `inactive`).
  * Penyimpanan nilai unik NIK (16 digit) dan email untuk menjaga integritas data keanggotaan.

---

## 2. Integrasi File Storage (Media Store)

Penyimpanan file foto KTP dan foto profil anggota dilakukan secara lokal di server.

* **Lokasi Fisik**: Direktori lokal `storage/app/public/photos/`.
* **Akses Publik**: Mengintegrasikan folder storage lokal tersebut ke folder publik web melalui pembuatan tautan simbolis (*symbolic link*) menggunakan perintah:
  ```bash
  php artisan storage:link
  ```
  Ini menghubungkan folder `public/storage` ke `storage/app/public`.
* **URL Akses**: Menggunakan helper `asset('storage/' . $path)` untuk menampilkan file foto secara dinamis pada halaman web.

---

## 3. Rencana Integrasi Mendatang (Future Integrations)

Dalam rencana pengembangan berikutnya, aplikasi akan diintegrasikan dengan beberapa modul eksternal:

### Pembuatan Kartu Anggota PDF
- Mengintegrasikan package eksternal (seperti `dompdf/dompdf` atau `barryvdh/laravel-dompdf`) untuk menghasilkan berkas PDF kartu anggota secara otomatis saat tombol cetak ditekan.

### QR Code Generator
- Mengintegrasikan library QR Code (seperti `simplesoftwareio/simple-qrcode`) untuk menghasilkan kode QR dinamis yang dicetak pada kartu anggota digital. Kode QR ini akan memuat URL profil publik anggota (e.g. `/verify/anggota/{uuid}`) untuk verifikasi keabsahan data anggota secara langsung di lapangan.

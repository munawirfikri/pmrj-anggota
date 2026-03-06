# Sistem Informasi Anggota PMRJ

Sistem informasi untuk mengelola keanggotaan Persatuan Masyarakat Riau Jakarta.

## Fitur Utama

### Landing Page
- Hero section dengan informasi PMRJ
- Form pendaftaran anggota dengan validasi lengkap
- Form login untuk anggota yang sudah terdaftar
- Desain modern dan responsif

### Sistem Pendaftaran
- Form registrasi dengan upload foto
- Validasi data anggota (KTP, email, dll)
- Generate nomor anggota otomatis (PMRJ-YY-XXXXXX)
- Auto login setelah registrasi berhasil

### Dashboard Anggota
- Welcome card dengan informasi anggota
- Profile card dengan foto anggota
- Quick actions menu
- Aktivitas terbaru

### Kartu Anggota Digital
- Kartu anggota dengan desain modern
- Menampilkan foto, data pribadi, dan nomor anggota
- QR Code untuk verifikasi
- Fitur download dan share (placeholder)

### Profile Management
- Edit data pribadi anggota
- Upload/ganti foto profile
- Ubah password
- Informasi keanggotaan

## Teknologi

- **Backend**: Laravel 8.x (PHP 7.4+)
- **Database**: PostgreSQL
- **Frontend**: Blade Templates + Tailwind CSS
- **Icons**: Font Awesome 6
- **Authentication**: Laravel Auth dengan custom guard

## Instalasi

1. Clone repository
2. Install dependencies:
   ```bash
   composer install
   ```

3. Setup database PostgreSQL dan konfigurasi `.env`:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=pmrj_jakarta
   DB_USERNAME=postgres
   DB_PASSWORD=password
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```

6. Buat symbolic link untuk storage:
   ```bash
   php artisan storage:link
   ```

7. Jalankan server:
   ```bash
   php artisan serve
   ```

## Struktur Database

### Tabel `anggota`
- `id` - Primary key
- `nama_lengkap` - Nama lengkap anggota
- `email` - Email (unique)
- `password` - Password (hashed)
- `no_ktp` - Nomor KTP (unique, 16 digit)
- `tempat_lahir` - Tempat lahir
- `tanggal_lahir` - Tanggal lahir
- `jenis_kelamin` - L/P
- `alamat` - Alamat lengkap
- `no_telepon` - Nomor telepon
- `pekerjaan` - Pekerjaan
- `foto` - Path foto anggota
- `no_anggota` - Nomor anggota (unique, format: PMRJ-YY-XXXXXX)
- `status` - Status anggota (pending/active/inactive)
- `remember_token` - Token untuk remember me
- `timestamps` - Created at & Updated at

## Routes

- `/` - Landing page
- `/register` - POST - Registrasi anggota baru
- `/login` - POST - Login anggota
- `/logout` - POST - Logout anggota
- `/dashboard` - Dashboard anggota (auth required)
- `/kartu-anggota` - Kartu anggota digital (auth required)
- `/profile` - Profile anggota (auth required)
- `/profile` - PUT - Update profile (auth required)

## Authentication

Sistem menggunakan custom authentication guard `anggota` yang terpisah dari user default Laravel. Anggota dapat login menggunakan email dan password.

## File Upload

Foto anggota disimpan di `storage/app/public/photos/` dan dapat diakses melalui symbolic link di `public/storage/`.

## Desain

- Menggunakan Tailwind CSS untuk styling modern
- Responsive design untuk mobile dan desktop
- Color scheme: Blue (#3985c3) untuk branding PMRJ
- Font Awesome icons untuk visual elements

## Pengembangan Selanjutnya

- Sistem notifikasi email
- Manajemen event dan kegiatan
- Direktori anggota
- Sistem pembayaran iuran
- Admin panel untuk manajemen anggota
- Export kartu anggota ke PDF
- QR Code generator untuk verifikasi
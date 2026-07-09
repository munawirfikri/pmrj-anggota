# Security Model — Model Keamanan Sistem PMRJ Anggota

Sistem Informasi Anggota PMRJ mengadopsi beberapa lapisan keamanan di tingkat aplikasi untuk melindungi data personal anggota yang sensitif (seperti NIK, KTP, dan info kontak).

---

## 1. Pemisahan Guard Autentikasi (Custom Auth Guard)

Untuk meminimalkan risiko eskalasi hak akses (*privilege escalation*) dan menjaga batas konteks sesi yang jelas, aplikasi memisahkan sistem autentikasi menjadi dua guard terpisah:

```text
┌────────────────────────────────────────────────────────┐
│                      Aplikasi Web                      │
├───────────────────────────┬────────────────────────────┤
│   Default 'web' Guard     │   Custom 'anggota' Guard   │
├───────────────────────────┼────────────────────────────┤
│  - User Administrator     │  - Anggota PMRJ            │
│  - Model: App\Models\User │  - Model: App\Models\Anggota│
│  - Tabel: users           │  - Tabel: anggota          │
│  - Sesi terisolasi        │  - Sesi terisolasi         │
└───────────────────────────┴────────────────────────────┘
```

* **Guard Anggota**: Dikonfigurasi dalam `config/auth.php` dengan driver `session` dan provider `anggota` (menggunakan model Eloquent `App\Models\Anggota`).
* **Proteksi Route**: Seluruh rute dashboard dan data anggota dilindungi oleh middleware bawaan Laravel dengan parameter guard tertentu: `auth:anggota`.
* **Keuntungan**: Kebocoran cookie sesi di dasbor admin tidak akan memberikan akses langsung ke dasbor anggota, dan sebaliknya.

---

## 2. Perlindungan Data Personal Anggota

### Validasi Input Ketat
Semua input data pengguna diverifikasi secara menyeluruh di backend:
- Keunikan bidang-bidang kunci (`nik`, `email`, `no_hp`) divalidasi pada setiap registrasi dan pembaruan profil.
- Pada aksi pembaruan profil, validasi keunikan akan mengecualikan record ID anggota yang sedang login untuk mencegah bentrok validasi dengan datanya sendiri.

### Pengamanan Password
- Password anggota disimpan dalam database menggunakan algoritma hashing aman satu arah (**Bcrypt**) via method `Hash::make()` di Laravel.
- Sistem tidak pernah menyimpan password dalam bentuk teks polos (*plain text*).

---

## 3. Perlindungan terhadap Serangan Web Umum

Aplikasi memanfaatkan fitur keamanan bawaan framework Laravel untuk menangkal serangan-serangan berikut:

### Cross-Site Request Forgery (CSRF)
- Setiap formulir pengisian data (registrasi, login, update profil) wajib menyertakan token CSRF (`@csrf` di file Blade).
- Middleware `VerifyCsrfToken` akan memblokir setiap permintaan POST, PUT, atau DELETE yang tidak menyertakan token CSRF yang valid.

### SQL Injection (SQLi)
- Interaksi database dilakukan melalui **Eloquent ORM** dan **Query Builder** Laravel yang menggunakan *PDO Parameter Binding* secara otomatis di balik layar.
- Permintaan data aman dari manipulasi input SQL berbahaya.

### Cross-Site Scripting (XSS)
- Semua keluaran data anggota yang ditampilkan pada template Blade menggunakan sintaks kurung kurawal ganda `{{ $data }}` yang otomatis melakukan sanitasi HTML entitas (*HTML escaping*) untuk mencegah eksekusi skrip berbahaya di sisi browser.

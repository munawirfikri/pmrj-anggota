# Request Flow — Alur Permintaan Sistem PMRJ Anggota

Dokumen ini mendokumentasikan alur eksekusi request utama dalam aplikasi PMRJ Anggota.

---

## 1. Alur Registrasi Anggota Baru

Calon anggota melakukan pendaftaran melalui formulir pendaftaran di landing page publik.

```mermaid
sequenceDiagram
    autonumber
    actor CalonAnggota as Calon Anggota
    participant Web as Browser (Blade/Tailwind)
    participant Route as Routes (web.php)
    participant HomeCtrl as HomeController (register)
    participant Trait as CompressesImages Trait
    participant DB as PostgreSQL
    participant Auth as Laravel Auth (anggota guard)

    CalonAnggota->>Web: Isi Form Pendaftaran & Unggah Foto
    Web->>Route: POST /register
    Route->>HomeCtrl: Panggil method register()
    
    Note over HomeCtrl: Validasi input data pribadi & NIK 16 digit
    
    HomeCtrl->>Trait: Panggil compressAndStore() untuk Foto Profil & KTP
    Trait-->>HomeCtrl: Mengembalikan path gambar terkompresi (.jpg)
    
    HomeCtrl->>DB: Query INSERT data anggota baru (status = pending)
    DB-->>HomeCtrl: Konfirmasi penyimpanan berhasil
    
    HomeCtrl->>Auth: Panggil Auth::guard('anggota')->login($anggota)
    Auth-->>HomeCtrl: Konfirmasi sesi login aktif
    
    HomeCtrl-->>Web: Redirect ke /dashboard dengan status sukses
    Web-->>CalonAnggota: Tampilkan Halaman Dashboard Anggota
```

---

## 2. Alur Autentikasi Login Anggota

Anggota terdaftar melakukan autentikasi untuk masuk ke dalam dasbor.

```mermaid
sequenceDiagram
    autonumber
    actor Anggota as Anggota PMRJ
    participant Web as Browser
    participant Route as Routes (web.php)
    participant HomeCtrl as HomeController (login)
    participant Auth as Laravel Auth (anggota guard)
    participant DB as PostgreSQL

    Anggota->>Web: Isi Email & Password
    Web->>Route: POST /login
    Route->>HomeCtrl: Panggil method login()
    
    HomeCtrl->>Auth: Panggil Auth::guard('anggota')->attempt(...)
    Auth->>DB: Query SELECT email anggota & bandingkan hash password
    DB-->>Auth: Kredensial cocok
    Auth-->>HomeCtrl: Mengembalikan status TRUE (Login Sukses)
    
    HomeCtrl-->>Web: Redirect ke /dashboard
    Web-->>Anggota: Tampilkan Halaman Dashboard
```

---

## 3. Alur Pembaruan Profil & Rekalkulasi Nomor Anggota

Anggota memperbarui profil diri dan mengubah pilihan kota asal IKK.

```mermaid
sequenceDiagram
    autonumber
    actor Anggota as Anggota PMRJ
    participant Web as Halaman Profil (Blade)
    participant Route as Routes (web.php)
    participant Middleware as auth:anggota Middleware
    participant AnggotaCtrl as AnggotaController (updateProfile)
    participant Trait as CompressesImages Trait
    participant Model as Anggota Model (generateNoAnggota)
    participant DB as PostgreSQL

    Anggota->>Web: Ubah Data Profil (e.g. Asal IKK, Unggah Foto Baru)
    Web->>Route: PUT /profile
    Route->>Middleware: Verifikasi sesi login aktif
    Middleware-->>Route: Sesi Valid (anggota guard)
    Route->>AnggotaCtrl: Panggil method updateProfile()
    
    Note over AnggotaCtrl: Validasi input profil & cek keunikan email/NIK/No HP (kecuali ID sendiri)
    
    alt Unggah Foto Profil Baru
        AnggotaCtrl->>DB: Hapus foto lama di storage (kecuali default avatar)
        AnggotaCtrl->>Trait: Panggil compressAndStore() untuk foto baru
        Trait-->>AnggotaCtrl: Path gambar baru terkompresi (.jpg)
    end
    
    AnggotaCtrl->>DB: Simpan pembaruan data dasar profil
    
    alt Asal IKK berubah dari data asli
        AnggotaCtrl->>Model: Panggil generateNoAnggota()
        Model->>DB: Cari IKK kode di tabel ikk & counter anggota tertinggi dari IKK tersebut
        DB-->>Model: Mengembalikan kode IKK dan list no_anggota
        Model-->>AnggotaCtrl: Mengembalikan No Anggota baru (e.g. PMRJ-02-0010)
        AnggotaCtrl->>DB: Simpan update no_anggota baru ke database
    end
    
    DB-->>AnggotaCtrl: Konfirmasi transaksi selesai
    AnggotaCtrl-->>Web: Redirect ke /profile dengan pesan sukses
    Web-->>Anggota: Tampilkan data profil terbaru & No Anggota baru
```

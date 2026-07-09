# Project Context — Sistem Informasi Anggota PMRJ

## Background

**Persatuan Masyarakat Riau Jakarta (PMRJ)** adalah paguyuban kemasyarakatan yang mewadahi warga asal Riau yang tinggal atau berdomisili di Jakarta dan sekitarnya (Jabodetabek). Sebagai wadah silaturahmi, PMRJ membutuhkan sarana pendataan anggota yang terpusat, aman, modern, dan mudah digunakan baik oleh pengurus paguyuban maupun warga biasa.

**Sistem Informasi Anggota PMRJ** dirancang untuk memenuhi kebutuhan ini melalui sebuah platform mandiri berbasis web.

---

## Business Objectives

Tujuan dari pembangunan aplikasi keanggotaan ini meliputi:
1. **Digitalisasi Data Anggota**: Mengubah pendataan anggota manual atau berbasis dokumen tersebar menjadi basis data terpusat menggunakan PostgreSQL.
2. **Pendaftaran Mandiri (Self-Registration)**: Memungkinkan calon anggota mendaftar langsung dari perangkat mobile atau desktop mereka secara mandiri.
3. **Penerbitan Identitas Digital**: Menghasilkan kartu tanda anggota (KTA) digital secara otomatis lengkap dengan kode QR unik untuk pembuktian keabsahan anggota.
4. **Verifikasi Keanggotaan Mudah**: Memudahkan pengurus memvalidasi status keaktifan warga melalui QR Code yang tertera di kartu digital.

---

## Core Capabilities & Features

Sistem ini memiliki beberapa fitur utama bagi pengguna (anggota):

### 1. Portal Landing & Registrasi Mandiri
- Halaman depan interaktif yang menampilkan informasi singkat tentang PMRJ.
- Formulir registrasi bagi warga baru dengan kolom isian data pribadi lengkap sesuai KTP.
- Unggah file Foto KTP dan Foto Profil diri dengan sistem kompresi gambar otomatis di sisi backend.

### 2. Autentikasi Anggota
- Login menggunakan alamat email terdaftar dan password yang aman.
- Autentikasi menggunakan session/cookie kustom di tingkat aplikasi (`anggota` guard) terpisah dari user administrative.

### 3. Dasbor Anggota (Member Dashboard)
- Tampilan kartu sambutan (*welcome card*) yang menampilkan nama dan status anggota.
- Tampilan profil ringkas beserta foto yang sudah dikompresi.
- Informasi status keanggotaan (*pending*, *active*, *inactive*).

### 4. Kartu Anggota Digital
- Tampilan Kartu Tanda Anggota (KTA) digital yang bersih dan modern.
- Dilengkapi dengan QR Code unik yang terhubung ke link verifikasi profil.
- Menampilkan logo PMRJ, data personal kunci, dan nomor keanggotaan resmi.

### 5. Pengelolaan Profil Mandiri (Profile Management)
- Memungkinkan anggota memperbarui informasi pribadi mereka (alamat, no HP, jenis pekerjaan, dll).
- Fitur ganti foto profil mandiri dengan otomatis menghapus berkas foto lama dari sistem penyimpanan.

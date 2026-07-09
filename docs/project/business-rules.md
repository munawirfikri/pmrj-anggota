# Business Rules — Sistem Informasi Anggota PMRJ

## 1. Aturan Penomoran Anggota (`no_anggota`)

Setiap anggota yang terdaftar secara sah dalam sistem wajib memiliki nomor anggota unik.

* **Format Nomor**: `PMRJ-{IKK_CODE}-{COUNTER}`
  * `PMRJ`: Prefiks statis organisasi.
  * `{IKK_CODE}`: Kode unik 2 digit asal daerah Ikatan Keluarga Kabupaten/Kota (IKK) asal Riau. Kode ini diambil dari tabel `ikk` (contoh: Kota Pekanbaru = `01`, Dumai = `02`, dst).
  * `{COUNTER}`: Counter numerik berurutan 4 digit dengan *left-padded* nol (contoh: `0001`, `0002`).
  * *Contoh*: `PMRJ-01-0005` (Anggota ke-5 dari IKK Kota Pekanbaru).
* **Penentuan Nomor**: Nomor ditentukan secara berurutan berdasarkan nomor tertinggi yang saat ini tercatat di database dengan format serupa.
* **Rekalkulasi Penomoran**: Jika seorang anggota memperbarui data profilnya dan mengubah kolom asal IKK (`asal_ikk`), nomor anggota mereka wajib di-generate ulang secara otomatis agar mencerminkan kode IKK baru.

---

## 2. Aturan Unggah & Pengolahan Gambar

Aplikasi harus menghemat kapasitas penyimpanan server dengan menerapkan kompresi gambar otomatis untuk setiap foto yang diunggah oleh anggota.

* **Metode Unggah**: Setiap file foto yang diunggah melalui formulir registrasi atau ganti profil harus diproses melalui trait `CompressesImages` di backend.
* **Format & Kompresi**:
  * Lebar maksimal gambar di-resize menjadi `800px` (tinggi disesuaikan secara proporsional).
  * Kualitas gambar dikompresi menjadi `80%`.
  * Output akhir disimpan dalam format **JPG** untuk konsistensi.
* **Lokasi Penyimpanan**: Disimpan di folder `storage/app/public/photos/`. Akses publik diarahkan melalui symbolic link di `/storage/photos/`.
* **Aturan Kebersihan Storage**: Ketika anggota mengganti foto profilnya dengan file baru, sistem wajib mendeteksi dan menghapus (*delete*) berkas foto profil yang lama dari sistem penyimpanan (kecuali foto tersebut adalah foto default seperti `default/avatar.png`) untuk menghindari tumpukan file usang di server.

---

## 3. Validasi Bidang Data Profil

Formulir pendaftaran dan pembaruan profil wajib menerapkan aturan validasi ketat sebelum data disimpan ke database.

### NIK (Nomor Induk Kependudukan)
- Bersifat unik (tidak boleh ada NIK yang sama di database anggota).
- Wajib memiliki panjang tepat **16 digit** berupa karakter angka.

### Alamat Email
- Bersifat nullable (boleh dikosongkan), namun jika diisi harus berformat email yang valid dan unik di database anggota.
- Saat melakukan pembaruan profil, validasi keunikan email harus mengecualikan ID dari anggota yang sedang login.

### Nomor Handphone (`no_hp`)
- Bersifat nullable (boleh dikosongkan), namun jika diisi harus unik di database anggota (mengecualikan ID anggota bersangkutan saat update profil).

### Relasi Asal IKK (`asal_ikk`)
- Kolom asal IKK wajib diisi dan nilainya harus berupa nama IKK yang sah yang terdaftar dalam tabel master `ikk`.

### Tabel Master / Validasi Kolom Lainnya
- **Jenis Kelamin**: Hanya boleh diisi dengan pilihan "Laki-laki" atau "Perempuan" sesuai data tabel master `jenis_kelamin`.
- **Golongan Darah**: Harus bernilai salah satu dari master golongan darah yang didukung (`A+`, `A-`, `B+`, `B-`, `AB+`, `AB-`, `O+`, `O-`).
- **Status Rumah**: Harus bernilai salah satu dari "Rumah Tetap" atau "Rumah Kontrak" sesuai data tabel master `status_rumah`.

---

## 4. Bahasa Kesalahan Validasi

* Semua pesan kegagalan validasi formulir di sisi front-end/blade harus diterjemahkan ke dalam **Bahasa Indonesia** yang baik dan jelas untuk memudahkan pemahaman pengguna.

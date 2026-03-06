<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Ikk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\CompressesImages;

class HomeController extends Controller
{
    use CompressesImages;
    public function index()
    {
        $ikkList = Ikk::orderBy('kode')->get();
        $jenisKelaminList = DB::table('jenis_kelamin')->get();
        $golonganDarahList = DB::table('golongan_darah')->get();
        $kotaBagianList = DB::table('kota_bagian')->get();
        $statusRumahList = DB::table('status_rumah')->get();
        
        return view('welcome', compact('ikkList', 'jenisKelaminList', 'golonganDarahList', 'kotaBagianList', 'statusRumahList'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'asal_ikk' => 'required|exists:ikk,nama',
            'email' => 'nullable|string|email|max:255|unique:anggota',
            'password' => 'required|string|min:8|confirmed',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'golongan_darah' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'nik' => 'nullable|string|size:16|unique:anggota',
            'pekerjaan' => 'nullable|string|max:255',
            'alamat_jakarta' => 'nullable|string',
            'kota_bagian' => 'nullable|in:Jakarta Utara,Jakarta Selatan,Jakarta Barat,Jakarta Timur,Jakarta Pusat,Kota Tangerang,Kabupaten Tangerang,Tangerang Selatan,Depok,Bekasi,Bogor',
            'no_hp' => 'nullable|string|max:15',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'status_rumah' => 'nullable|in:Rumah Tetap,Rumah Kontrak',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'golongan_darah.required' => 'Golongan darah wajib dipilih',
            'nik.required' => 'NIK wajib diisi',
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'pekerjaan.required' => 'Pekerjaan wajib diisi',
            'alamat_jakarta.required' => 'Alamat lengkap di Jakarta wajib diisi',
            'kota_bagian.required' => 'Kota bagian wajib dipilih',
            'asal_ikk.required' => 'Asal IKK wajib dipilih',
            'asal_ikk.exists' => 'Asal IKK tidak valid',
            'no_telepon.required' => 'Nomor HP wajib diisi',
            'foto_ktp.required' => 'Foto KTP wajib diupload',
            'foto_ktp.image' => 'File harus berupa gambar',
            'foto_ktp.mimes' => 'Format foto harus JPG, PNG, atau JPEG',
            'foto_ktp.max' => 'Ukuran foto maksimal 5MB',
            'status_rumah.required' => 'Status rumah wajib dipilih'
        ]);

        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $this->compressAndStore($request->file('foto_ktp'), 'ktp');
        }

        // Generate email if not provided
        $email = $request->email;
        if (empty($email)) {
            $namaParts = explode(' ', $request->nama_lengkap);
            $namaDepan = strtolower($namaParts[0]);
            if (count($namaParts) > 1) {
                $namaBelakang = strtolower($namaParts[1]);
                $baseEmail = $namaDepan . '.' . $namaBelakang;
            } else {
                $baseEmail = $namaDepan;
            }
            
            // Check if email exists and add suffix if needed
            $email = $baseEmail . '@pmrj.or.id';
            $counter = 1;
            while (Anggota::where('email', $email)->exists()) {
                $email = $baseEmail . $counter . '@pmrj.or.id';
                $counter++;
            }
        }

        $anggota = Anggota::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $email,
            'password' => Hash::make($request->password),
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'golongan_darah' => $request->golongan_darah,
            'nik' => $request->nik,
            'pekerjaan' => $request->pekerjaan,
            'alamat_jakarta' => $request->alamat_jakarta,
            'kota_bagian' => $request->kota_bagian,
            'asal_ikk' => $request->asal_ikk,
            'no_hp' => $request->no_hp,
            'foto_ktp' => $fotoKtpPath,
            'status_rumah' => $request->status_rumah,
            'status' => 'active'
        ]);

        try {
            $anggota->no_anggota = $anggota->generateNoAnggota();
            $anggota->save();

            auth('anggota')->login($anggota);

            return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di PMRJ');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        if (auth('anggota')->attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function getNews()
    {
        try {
            // Get posts from pmrj.or.id
            $postsUrl = "https://pmrj.or.id/wp-json/wp/v2/posts?per_page=5&_embed";
            $postsData = json_decode(file_get_contents($postsUrl), true);
            
            if (!$postsData) {
                return response()->json([]);
            }
            
            $articles = [];
            foreach ($postsData as $post) {
                $articles[] = [
                    'title' => html_entity_decode($post['title']['rendered'], ENT_QUOTES, 'UTF-8'),
                    'excerpt' => strip_tags(html_entity_decode($post['excerpt']['rendered'], ENT_QUOTES, 'UTF-8')),
                    'date' => date('d M Y', strtotime($post['date'])),
                    'link' => $post['link']
                ];
            }
            
            return response()->json($articles);
            
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    public function logout()
    {
        auth('anggota')->logout();
        return redirect()->route('home');
    }
}

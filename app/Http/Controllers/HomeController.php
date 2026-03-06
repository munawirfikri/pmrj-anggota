<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:anggota',
            'password' => 'required|string|min:8|confirmed',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'golongan_darah' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'nik' => 'required|string|size:16|unique:anggota',
            'pekerjaan' => 'required|string|max:255',
            'alamat_jakarta' => 'required|string',
            'kota_bagian' => 'required|in:Jakarta Utara,Jakarta Selatan,Jakarta Barat,Jakarta Timur,Jakarta Pusat,Kota Tangerang,Kabupaten Tangerang,Tangerang Selatan,Depok,Bekasi,Bogor',
            'no_telepon' => 'required|string|max:15',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'nama_ortu' => 'required|string|max:255',
            'tanggal_lahir_ortu' => 'required|date',
            'tempat_lahir_ortu' => 'required|string|max:255',
            'status_rumah' => 'required|in:Rumah Tetap,Rumah Kontrak',
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
            'no_telepon.required' => 'Nomor HP wajib diisi',
            'foto_ktp.required' => 'Foto KTP wajib diupload',
            'foto_ktp.image' => 'File harus berupa gambar',
            'foto_ktp.mimes' => 'Format foto harus JPG, PNG, atau JPEG',
            'foto_ktp.max' => 'Ukuran foto maksimal 5MB',
            'nama_ortu.required' => 'Nama orang tua wajib diisi',
            'tanggal_lahir_ortu.required' => 'Tanggal lahir orang tua wajib diisi',
            'tempat_lahir_ortu.required' => 'Tempat lahir orang tua wajib diisi',
            'status_rumah.required' => 'Status rumah wajib dipilih'
        ]);

        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');
        }

        $anggota = Anggota::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'golongan_darah' => $request->golongan_darah,
            'nik' => $request->nik,
            'pekerjaan' => $request->pekerjaan,
            'alamat_jakarta' => $request->alamat_jakarta,
            'kota_bagian' => $request->kota_bagian,
            'no_telepon' => $request->no_telepon,
            'foto_ktp' => $fotoKtpPath,
            'nama_ortu' => $request->nama_ortu,
            'tanggal_lahir_ortu' => $request->tanggal_lahir_ortu,
            'tempat_lahir_ortu' => $request->tempat_lahir_ortu,
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
            // Get category ID for 'agenda'
            $categoryUrl = 'https://ikmkbjakarta.or.id/wp-json/wp/v2/categories?slug=agenda';
            $categoryData = json_decode(file_get_contents($categoryUrl), true);
            
            if (empty($categoryData)) {
                return response()->json([]);
            }
            
            $categoryId = $categoryData[0]['id'];
            
            // Get posts from agenda category
            $postsUrl = "https://ikmkbjakarta.or.id/wp-json/wp/v2/posts?categories={$categoryId}&per_page=5&_embed";
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Ikk;
use App\Traits\CompressesImages;

class AnggotaController extends Controller
{
    use CompressesImages;
    public function __construct()
    {
        $this->middleware('auth:anggota');
    }

    public function dashboard()
    {
        $anggota = auth('anggota')->user();
        return view('dashboard.index', compact('anggota'));
    }

    public function kartuAnggota()
    {
        $anggota = auth('anggota')->user();
        return view('dashboard.kartu', compact('anggota'));
    }

    public function profile()
    {
        $anggota = auth('anggota')->user();
        $ikkList = Ikk::orderBy('kode')->get();
        $jenisKelaminList = DB::table('jenis_kelamin')->get();
        $golonganDarahList = DB::table('golongan_darah')->get();
        $kotaBagianList = DB::table('kota_bagian')->get();
        $statusRumahList = DB::table('status_rumah')->get();
        
        return view('dashboard.profile', compact('anggota', 'ikkList', 'jenisKelaminList', 'golonganDarahList', 'kotaBagianList', 'statusRumahList'));
    }

    public function updateProfile(Request $request)
    {
        $anggota = auth('anggota')->user();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'asal_ikk' => 'required|exists:ikk,nama',
            'email' => 'nullable|string|email|max:255|unique:anggota,email,' . $anggota->id,
            'nik' => 'nullable|string|size:16|unique:anggota,nik,' . $anggota->id,
            'no_hp' => 'nullable|string|max:15|unique:anggota,no_hp,' . $anggota->id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'golongan_darah' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alamat_jakarta' => 'nullable|string',
            'kota_bagian' => 'nullable|in:Jakarta Utara,Jakarta Selatan,Jakarta Barat,Jakarta Timur,Jakarta Pusat,Kota Tangerang,Kabupaten Tangerang,Tangerang Selatan,Depok,Bekasi,Bogor',
            'pekerjaan' => 'nullable|string|max:255',
            'status_rumah' => 'nullable|in:Rumah Tetap,Rumah Kontrak',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh anggota lain',
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah digunakan oleh anggota lain',
            'no_hp.unique' => 'Nomor HP sudah digunakan oleh anggota lain',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'alamat_jakarta.required' => 'Alamat Jakarta wajib diisi',
            'kota_bagian.required' => 'Kota bagian wajib dipilih',
            'asal_ikk.required' => 'Asal IKK wajib dipilih',
            'asal_ikk.exists' => 'Asal IKK tidak valid',
            'no_telepon.required' => 'No. telepon wajib diisi',
            'pekerjaan.required' => 'Pekerjaan wajib diisi',
            'status_rumah.required' => 'Status rumah wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau JPEG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        $data = $request->only([
            'nama_lengkap', 'email', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah',
            'alamat_jakarta', 'kota_bagian', 'asal_ikk', 'no_hp', 'pekerjaan', 'status_rumah'
        ]);

        if ($request->hasFile('foto')) {
            if ($anggota->foto && $anggota->foto !== 'default/avatar.png') {
                Storage::disk('public')->delete($anggota->foto);
            }
            $data['foto'] = $this->compressAndStore($request->file('foto'), 'photos');
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed'], [
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok'
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $anggota->update($data);

        // Update member number if asal_ikk changed
        if ($request->asal_ikk !== $anggota->getOriginal('asal_ikk')) {
            $anggota->no_anggota = $anggota->generateNoAnggota();
            $anggota->save();
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
}

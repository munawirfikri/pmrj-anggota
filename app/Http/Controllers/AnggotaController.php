<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
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
        return view('dashboard.profile', compact('anggota'));
    }

    public function updateProfile(Request $request)
    {
        $anggota = auth('anggota')->user();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:anggota,email,' . $anggota->id,
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat_jakarta' => 'required|string',
            'kota_bagian' => 'required|in:Jakarta Utara,Jakarta Selatan,Jakarta Barat,Jakarta Timur,Jakarta Pusat,Kota Tangerang,Kabupaten Tangerang,Tangerang Selatan,Depok,Bekasi,Bogor',
            'no_telepon' => 'required|string|max:15',
            'pekerjaan' => 'required|string|max:255',
            'status_rumah' => 'required|in:Rumah Tetap,Rumah Kontrak',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'alamat_jakarta.required' => 'Alamat Jakarta wajib diisi',
            'kota_bagian.required' => 'Kota bagian wajib dipilih',
            'no_telepon.required' => 'No. telepon wajib diisi',
            'pekerjaan.required' => 'Pekerjaan wajib diisi',
            'status_rumah.required' => 'Status rumah wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, PNG, atau JPEG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        $data = $request->only([
            'nama_lengkap', 'email', 'tempat_lahir', 'tanggal_lahir',
            'alamat', 'no_telepon', 'pekerjaan'
        ]);

        if ($request->hasFile('foto')) {
            if ($anggota->foto && $anggota->foto !== 'default/avatar.png') {
                Storage::disk('public')->delete($anggota->foto);
            }
            $data['foto'] = $request->file('foto')->store('photos', 'public');
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed'], [
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok'
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $anggota->update($data);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Ikk;
use App\Models\CetakKartuRequest;
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
        $latestRequest = CetakKartuRequest::where('anggota_id', $anggota->id)
            ->latest()
            ->first();
        return view('dashboard.kartu', compact('anggota', 'latestRequest'));
    }

    public function requestCetakKartu(Request $request)
    {
        $anggota = auth('anggota')->user();

        // Check checklist in backend to ensure integrity
        $checks = [
            'ktp' => !empty($anggota->nik) && strlen($anggota->nik) === 16 && !empty($anggota->foto_ktp),
            'kk' => !empty($anggota->no_kk) && strlen($anggota->no_kk) === 16 && !empty($anggota->foto_kk),
            'pekerjaan' => !empty($anggota->nama_perusahaan) && !empty($anggota->jabatan) && !empty($anggota->alamat_kantor),
            'keluarga_dekat' => !empty($anggota->nama_keluarga_dekat) && !empty($anggota->alamat_keluarga_dekat) && !empty($anggota->no_hp_keluarga_dekat),
            'no_hp' => !empty($anggota->no_hp),
            'email' => !empty($anggota->email),
        ];

        $allFitted = !in_array(false, $checks, true);

        if (!$allFitted) {
            return response()->json([
                'success' => false,
                'message' => 'Persyaratan belum lengkap. Silakan lengkapi profil Anda.',
                'checks' => $checks
            ], 422);
        }

        // Check if there is an active request (pending or diproses)
        $activeRequest = CetakKartuRequest::where('anggota_id', $anggota->id)
            ->whereIn('status', ['pending', 'diproses'])
            ->first();

        if ($activeRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan cetak kartu Anda sebelumnya masih dalam proses.'
            ], 400);
        }

        // Create request
        $cetakRequest = CetakKartuRequest::create([
            'anggota_id' => $anggota->id,
            'status' => 'pending'
        ]);

        \Log::info("Permintaan cetak kartu baru diajukan oleh anggota: {$anggota->email} (ID: {$anggota->id}, Request ID: {$cetakRequest->id})");

        return response()->json([
            'success' => true,
            'message' => 'Permintaan cetak kartu berhasil dikirim!'
        ]);
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
            'no_kk' => 'nullable|string|size:16|unique:anggota,no_kk,' . $anggota->id,
            'no_hp' => 'nullable|string|max:15|unique:anggota,no_hp,' . $anggota->id,
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'golongan_darah' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'alamat_jakarta' => 'nullable|string',
            'kota_bagian' => 'nullable|in:Jakarta Utara,Jakarta Selatan,Jakarta Barat,Jakarta Timur,Jakarta Pusat,Kota Tangerang,Kabupaten Tangerang,Tangerang Selatan,Depok,Bekasi,Bogor',
            'pekerjaan' => 'nullable|string|max:255',
            'nama_perusahaan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'alamat_kantor' => 'nullable|string',
            'status_rumah' => 'nullable|in:Rumah Tetap,Rumah Kontrak',
            'nama_keluarga_dekat' => 'nullable|string|max:255',
            'alamat_keluarga_dekat' => 'nullable|string',
            'no_hp_keluarga_dekat' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'foto_ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'foto_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh anggota lain',
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah digunakan oleh anggota lain',
            'no_kk.size' => 'Nomor KK harus 16 digit',
            'no_kk.unique' => 'Nomor KK sudah digunakan oleh anggota lain',
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
            'foto.max' => 'Ukuran foto maksimal 10MB',
            'foto_ktp.mimes' => 'Format KTP harus JPG, PNG, JPEG, atau PDF',
            'foto_ktp.max' => 'Ukuran KTP maksimal 5MB',
            'foto_kk.mimes' => 'Format Kartu Keluarga harus JPG, PNG, JPEG, atau PDF',
            'foto_kk.max' => 'Ukuran Kartu Keluarga maksimal 5MB',
        ]);

        $data = $request->only([
            'nama_lengkap', 'email', 'nik', 'no_kk', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah',
            'alamat_jakarta', 'kota_bagian', 'asal_ikk', 'no_hp', 'pekerjaan', 'nama_perusahaan', 'jabatan', 'alamat_kantor',
            'status_rumah', 'nama_keluarga_dekat', 'alamat_keluarga_dekat', 'no_hp_keluarga_dekat'
        ]);

        if ($request->hasFile('foto')) {
            if ($anggota->foto && $anggota->foto !== 'default/avatar.png') {
                Storage::disk('public')->delete($anggota->foto);
                \Log::info("Foto profil lama dihapus dari storage: " . $anggota->foto . " (ID Anggota: " . $anggota->id . ")");
            }
            try {
                $data['foto'] = $this->compressAndStore($request->file('foto'), 'photos', $request->nama_lengkap);
            } catch (\InvalidArgumentException $e) {
                return back()->withErrors(['foto' => $e->getMessage()])->withInput();
            }
        }

        if ($request->hasFile('foto_ktp')) {
            if ($anggota->foto_ktp) {
                Storage::disk('public')->delete($anggota->foto_ktp);
                \Log::info("Foto KTP lama dihapus dari storage: " . $anggota->foto_ktp . " (ID Anggota: " . $anggota->id . ")");
            }
            try {
                $data['foto_ktp'] = $this->compressAndStore($request->file('foto_ktp'), 'ktp', $request->nama_lengkap);
            } catch (\InvalidArgumentException $e) {
                return back()->withErrors(['foto_ktp' => $e->getMessage()])->withInput();
            }
        }

        if ($request->hasFile('foto_kk')) {
            if ($anggota->foto_kk) {
                Storage::disk('public')->delete($anggota->foto_kk);
                \Log::info("Foto KK lama dihapus dari storage: " . $anggota->foto_kk . " (ID Anggota: " . $anggota->id . ")");
            }
            try {
                $data['foto_kk'] = $this->compressAndStore($request->file('foto_kk'), 'kk', $request->nama_lengkap);
            } catch (\InvalidArgumentException $e) {
                return back()->withErrors(['foto_kk' => $e->getMessage()])->withInput();
            }
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed'], [
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok'
            ]);
            $data['password'] = Hash::make($request->password);
            \Log::info("Password anggota diperbarui: " . $anggota->email . " (ID: " . $anggota->id . ")");
        }

        $asalIkkChanged = ($request->asal_ikk !== $anggota->asal_ikk);

        $anggota->update($data);
        \Log::info("Profil anggota diperbarui: " . $anggota->email . " (ID: " . $anggota->id . ")");

        // Update member number if asal_ikk changed
        if ($asalIkkChanged) {
            $oldNoAnggota = $anggota->no_anggota;
            $anggota->no_anggota = $anggota->generateNoAnggota();
            $anggota->save();
            \Log::info("Nomor anggota diperbarui karena perubahan asal IKK. ID: {$anggota->id}, No Anggota lama: {$oldNoAnggota}, No Anggota baru: {$anggota->no_anggota}");
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
}

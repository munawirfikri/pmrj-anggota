@extends('dashboard.layout')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-0">
    <div class="bg-white rounded-lg shadow-lg">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Edit Profile</h3>
        </div>
        
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-4 sm:p-6">
            @csrf
            @method('PUT')
            
            <!-- Photo Section -->
            <div class="mb-6 sm:mb-8 text-center">
                <div class="mb-4">
                    @if($anggota->foto && $anggota->foto !== 'default/avatar.png')
                        <img id="photoPreview" src="{{ $anggota->foto_url }}" alt="Foto" class="w-24 sm:w-32 h-24 sm:h-32 rounded-full mx-auto object-cover border-4 border-gray-200">
                    @else
                        <div id="photoPreview" class="w-24 sm:w-32 h-24 sm:h-32 bg-gray-300 rounded-full mx-auto flex items-center justify-center border-4 border-gray-200">
                            <i class="fas fa-user text-gray-600 text-3xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <label for="foto" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 cursor-pointer">
                        <i class="fas fa-camera mr-2"></i>
                        Ganti Foto
                    </label>
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG. Maksimal 10MB (Opsional)</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                <!-- Personal Information -->
                <div class="space-y-3 sm:space-y-4">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">Informasi Pribadi</h4>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" value="{{ $anggota->nama_lengkap }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" value="{{ $anggota->email }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIK</label>
                        <input type="text" name="nik" value="{{ $anggota->nik }}" maxlength="16" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" value="{{ $anggota->no_kk }}" maxlength="16" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ $anggota->tempat_lahir }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Pilih</option>
                            @foreach($jenisKelaminList as $jk)
                                <option value="{{ $jk->nama }}" {{ $anggota->jenis_kelamin == $jk->nama ? 'selected' : '' }}>{{ $jk->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="">Pilih</option>
                            @foreach($golonganDarahList as $gd)
                                <option value="{{ $gd->nama }}" {{ $anggota->golongan_darah == $gd->nama ? 'selected' : '' }}>{{ $gd->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Supporting Documents -->
                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-3 sm:space-y-4">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">Dokumen Pendukung</h4>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Foto KTP</label>
                            @if($anggota->foto_ktp)
                                <div class="mb-2 flex items-center space-x-3 bg-blue-50 p-2 rounded-lg border border-blue-100">
                                    @if(pathinfo(strtolower($anggota->foto_ktp), PATHINFO_EXTENSION) === 'pdf')
                                        <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                                    @else
                                        <i class="fas fa-file-image text-blue-600 text-lg"></i>
                                    @endif
                                    <a href="{{ $anggota->foto_ktp_url }}" target="_blank" class="text-sm text-blue-600 hover:underline font-medium">Lihat KTP saat ini</a>
                                </div>
                            @else
                                <p class="text-xs text-red-500 mb-2 italic">Belum diunggah</p>
                            @endif
                            <input type="file" name="foto_ktp" accept="image/*,application/pdf" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maksimal 5MB</p>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Foto Kartu Keluarga (KK)</label>
                            @if($anggota->foto_kk)
                                <div class="mb-2 flex items-center space-x-3 bg-blue-50 p-2 rounded-lg border border-blue-100">
                                    @if(pathinfo(strtolower($anggota->foto_kk), PATHINFO_EXTENSION) === 'pdf')
                                        <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                                    @else
                                        <i class="fas fa-file-image text-blue-600 text-lg"></i>
                                    @endif
                                    <a href="{{ $anggota->foto_kk_url }}" target="_blank" class="text-sm text-blue-600 hover:underline font-medium">Lihat KK saat ini</a>
                                </div>
                            @else
                                <p class="text-xs text-red-500 mb-2 italic">Belum diunggah</p>
                            @endif
                            <input type="file" name="foto_kk" accept="image/*,application/pdf" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Maksimal 5MB</p>
                        </div>
                    </div>
                </div>

                <!-- Contact & Address Information -->
                <div class="space-y-3 sm:space-y-4">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">Informasi Kontak & Alamat</h4>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. HP</label>
                        <input type="text" name="no_hp" value="{{ $anggota->no_hp }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap di Jakarta</label>
                        <textarea name="alamat_jakarta" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">{{ $anggota->alamat_jakarta }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kota Bagian</label>
                        <select name="kota_bagian" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            @foreach($kotaBagianList as $kb)
                                <option value="{{ $kb->nama }}" {{ $anggota->kota_bagian == $kb->nama ? 'selected' : '' }}>{{ $kb->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Asal IKK *</label>
                        <select name="asal_ikk" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            @foreach($ikkList as $ikk)
                                <option value="{{ $ikk->nama }}" {{ $anggota->asal_ikk == $ikk->nama ? 'selected' : '' }}>{{ $ikk->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status Rumah</label>
                        <select name="status_rumah" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            @foreach($statusRumahList as $sr)
                                <option value="{{ $sr->nama }}" {{ $anggota->status_rumah == $sr->nama ? 'selected' : '' }}>{{ $sr->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Information -->
                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-3 sm:space-y-4">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">Informasi Pekerjaan</h4>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Sektor Pekerjaan</label>
                            <input type="text" name="pekerjaan" value="{{ $anggota->pekerjaan }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Contoh: Karyawan Swasta, PNS, Pengusaha">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Perusahaan / Instansi</label>
                            <input type="text" name="nama_perusahaan" value="{{ $anggota->nama_perusahaan }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ $anggota->jabatan }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Kantor</label>
                            <textarea name="alamat_kantor" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">{{ $anggota->alamat_kantor }}</textarea>
                        </div>
                    </div>

                    <!-- Family Contact Information -->
                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-3 sm:space-y-4">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">Keluarga Dekat Tidak Serumah</h4>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap Keluarga</label>
                            <input type="text" name="nama_keluarga_dekat" value="{{ $anggota->nama_keluarga_dekat }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap</label>
                            <textarea name="alamat_keluarga_dekat" rows="2" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">{{ $anggota->alamat_keluarga_dekat }}</textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">No. HP / Telepon Aktif</label>
                            <input type="text" name="no_hp_keluarga_dekat" value="{{ $anggota->no_hp_keluarga_dekat }}" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Membership Info -->
                    <div class="bg-gray-50 p-4 rounded-lg mt-6">
                        <h5 class="font-semibold text-gray-900 mb-2">Informasi Keanggotaan</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">No. Anggota:</span>
                                <span class="font-medium">{{ $anggota->no_anggota }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium capitalize">{{ $anggota->status }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Bergabung:</span>
                                <span class="font-medium">{{ $anggota->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="mt-6 sm:mt-8 border-t pt-4 sm:pt-6">
                <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Ubah Password</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('dashboard') }}" class="px-4 sm:px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center">
                    Batal
                </a>
                <button type="submit" class="px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-24 sm:w-32 h-24 sm:h-32 rounded-full object-cover border-4 border-gray-200">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
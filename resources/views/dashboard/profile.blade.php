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
                        <img id="photoPreview" src="{{ asset('storage/' . $anggota->foto) }}" alt="Foto" class="w-24 sm:w-32 h-24 sm:h-32 rounded-full mx-auto object-cover border-4 border-gray-200">
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
                    <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB (Opsional)</p>
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
                        <input type="email" name="email" value="{{ $anggota->email }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIK</label>
                        <input type="text" value="{{ $anggota->nik }}" disabled class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-600">
                        <p class="text-sm text-gray-500 mt-1">NIK tidak dapat diubah</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tempat Lahir *</label>
                        <input type="text" name="tempat_lahir" value="{{ $anggota->tempat_lahir }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" value="{{ $anggota->tanggal_lahir->format('Y-m-d') }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                        <input type="text" value="{{ $anggota->jenis_kelamin }}" disabled class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-600">
                        <p class="text-sm text-gray-500 mt-1">Jenis kelamin tidak dapat diubah</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Golongan Darah</label>
                        <input type="text" value="{{ $anggota->golongan_darah }}" disabled class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-600">
                        <p class="text-sm text-gray-500 mt-1">Golongan darah tidak dapat diubah</p>
                    </div>
                </div>

                <!-- Contact & Address Information -->
                <div class="space-y-3 sm:space-y-4">
                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 border-b pb-2">Informasi Kontak & Alamat</h4>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">No. Telepon *</label>
                        <input type="text" name="no_telepon" value="{{ $anggota->no_telepon }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pekerjaan *</label>
                        <input type="text" name="pekerjaan" value="{{ $anggota->pekerjaan }}" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap di Jakarta *</label>
                        <textarea name="alamat_jakarta" required rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">{{ $anggota->alamat_jakarta }}</textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kota Bagian *</label>
                        <select name="kota_bagian" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="Jakarta Utara" {{ $anggota->kota_bagian == 'Jakarta Utara' ? 'selected' : '' }}>Jakarta Utara</option>
                            <option value="Jakarta Selatan" {{ $anggota->kota_bagian == 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan</option>
                            <option value="Jakarta Barat" {{ $anggota->kota_bagian == 'Jakarta Barat' ? 'selected' : '' }}>Jakarta Barat</option>
                            <option value="Jakarta Timur" {{ $anggota->kota_bagian == 'Jakarta Timur' ? 'selected' : '' }}>Jakarta Timur</option>
                            <option value="Jakarta Pusat" {{ $anggota->kota_bagian == 'Jakarta Pusat' ? 'selected' : '' }}>Jakarta Pusat</option>
                            <option value="Kota Tangerang" {{ $anggota->kota_bagian == 'Kota Tangerang' ? 'selected' : '' }}>Kota Tangerang</option>
                            <option value="Kabupaten Tangerang" {{ $anggota->kota_bagian == 'Kabupaten Tangerang' ? 'selected' : '' }}>Kabupaten Tangerang</option>
                            <option value="Tangerang Selatan" {{ $anggota->kota_bagian == 'Tangerang Selatan' ? 'selected' : '' }}>Tangerang Selatan</option>
                            <option value="Depok" {{ $anggota->kota_bagian == 'Depok' ? 'selected' : '' }}>Depok</option>
                            <option value="Bekasi" {{ $anggota->kota_bagian == 'Bekasi' ? 'selected' : '' }}>Bekasi</option>
                            <option value="Bogor" {{ $anggota->kota_bagian == 'Bogor' ? 'selected' : '' }}>Bogor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status Rumah *</label>
                        <select name="status_rumah" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            <option value="Rumah Tetap" {{ $anggota->status_rumah == 'Rumah Tetap' ? 'selected' : '' }}>Rumah Tetap</option>
                            <option value="Rumah Kontrak" {{ $anggota->status_rumah == 'Rumah Kontrak' ? 'selected' : '' }}>Rumah Kontrak</option>
                        </select>
                    </div>

                    <!-- Membership Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
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
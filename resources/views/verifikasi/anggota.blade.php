<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keanggotaan PMRJ - {{ $anggota->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-10 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Banner/Header -->
        <div class="bg-gradient-to-r from-blue-700 to-blue-900 text-white text-center py-6 px-4 relative">
            <div class="absolute top-4 left-4">
                <i class="fas fa-shield-alt text-2xl text-blue-200"></i>
            </div>
            <h1 class="text-lg font-bold">PMRJ</h1>
            <p class="text-xs text-blue-200">Persatuan Masyarakat Riau Jakarta</p>
        </div>

        <!-- Content -->
        <div class="p-6 text-center space-y-6">
            <!-- Profile Photo -->
            <div class="relative inline-block">
                @if($anggota->foto && $anggota->foto !== 'default/avatar.png')
                    <img src="{{ $anggota->foto_url }}" alt="Foto Profil" class="w-28 h-28 rounded-full object-cover mx-auto border-4 border-blue-500 shadow-md">
                @else
                    <div class="w-28 h-28 bg-gray-200 rounded-full flex items-center justify-center mx-auto border-4 border-blue-500 shadow-md">
                        <i class="fas fa-user text-gray-400 text-4xl"></i>
                    </div>
                @endif
                <!-- Status Badge -->
                <span class="absolute bottom-0 right-0 transform translate-x-1 translate-y-1 px-3 py-1 text-xs font-bold rounded-full border-2 border-white shadow uppercase tracking-wider
                    @if($anggota->status === 'active') bg-green-500 text-white
                    @elseif($anggota->status === 'pending') bg-yellow-500 text-white
                    @else bg-red-500 text-white
                    @endif">
                    {{ $anggota->status === 'active' ? 'Aktif' : ($anggota->status === 'pending' ? 'Pending' : 'Tidak Aktif') }}
                </span>
            </div>

            <!-- Member Info Title -->
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $anggota->nama_lengkap }}</h2>
                <p class="text-sm text-gray-500">{{ $anggota->no_anggota }}</p>
            </div>

            <!-- Verification Status Text -->
            @if($anggota->status === 'active')
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center space-x-3 text-left">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    <div>
                        <h3 class="font-bold text-green-800 text-sm">Keanggotaan Terverifikasi</h3>
                        <p class="text-xs text-green-700 font-normal">Warga ini resmi terdaftar dan berstatus aktif dalam Sistem Informasi Anggota PMRJ.</p>
                    </div>
                </div>
            @elseif($anggota->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center space-x-3 text-left">
                    <i class="fas fa-exclamation-circle text-yellow-600 text-2xl"></i>
                    <div>
                        <h3 class="font-bold text-yellow-800 text-sm">Keanggotaan Pending</h3>
                        <p class="text-xs text-yellow-700 font-normal">Pendaftaran telah diterima. Menunggu verifikasi berkas oleh admin.</p>
                    </div>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center space-x-3 text-left">
                    <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                    <div>
                        <h3 class="font-bold text-red-800 text-sm">Keanggotaan Tidak Aktif</h3>
                        <p class="text-xs text-red-700 font-normal">Akun keanggotaan ini tidak aktif.</p>
                    </div>
                </div>
            @endif

            <!-- Profile Details Table -->
            <div class="border-t border-gray-100 pt-4 text-left space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-normal">Asal IKK</span>
                    <span class="font-semibold text-gray-800">{{ $anggota->asal_ikk }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-normal">NIK (Masked)</span>
                    <span class="font-mono font-semibold text-gray-800">
                        {{ substr($anggota->nik, 0, 4) . '**********' . substr($anggota->nik, -2) }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-normal">Jenis Kelamin</span>
                    <span class="font-semibold text-gray-800">{{ $anggota->jenis_kelamin }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-normal">Kota Bagian</span>
                    <span class="font-semibold text-gray-800">{{ $anggota->kota_bagian }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-normal">Tanggal Bergabung</span>
                    <span class="font-semibold text-gray-800">{{ $anggota->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Footer Badge -->
            <div class="border-t border-gray-100 pt-4 flex items-center justify-center space-x-2 text-xs text-gray-400">
                <i class="fas fa-lock"></i>
                <span>Tautan Verifikasi Resmi PMRJ</span>
            </div>
        </div>
    </div>
</body>
</html>

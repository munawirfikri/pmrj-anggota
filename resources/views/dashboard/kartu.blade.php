@extends('dashboard.layout')

@section('title', 'Kartu Anggota')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-0">
    @if($latestRequest)
        <div class="mb-6 p-4 rounded-lg flex items-center justify-between shadow-sm
            @if($latestRequest->status === 'pending') bg-yellow-50 border border-yellow-200 text-yellow-800
            @elseif($latestRequest->status === 'diproses') bg-blue-50 border border-blue-200 text-blue-800
            @elseif($latestRequest->status === 'selesai') bg-green-50 border border-green-200 text-green-800
            @elseif($latestRequest->status === 'ditolak') bg-red-50 border border-red-200 text-red-800
            @endif">
            <div class="flex items-center space-x-3">
                @if($latestRequest->status === 'pending')
                    <i class="fas fa-clock text-xl text-yellow-600"></i>
                    <div>
                        <p class="font-semibold text-sm sm:text-base">Request Cetak: Menunggu Persetujuan</p>
                        <p class="text-xs sm:text-sm text-yellow-700 font-normal">Diajukan pada {{ $latestRequest->created_at->format('d M Y H:i') }}</p>
                    </div>
                @elseif($latestRequest->status === 'diproses')
                    <i class="fas fa-print text-xl text-blue-600"></i>
                    <div>
                        <p class="font-semibold text-sm sm:text-base">Request Cetak: Sedang Diproses</p>
                        <p class="text-xs sm:text-sm text-blue-700 font-normal font-normal">Kartu Anda sedang diproses/dicetak oleh admin.</p>
                    </div>
                @elseif($latestRequest->status === 'selesai')
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                    <div>
                        <p class="font-semibold text-sm sm:text-base">Request Cetak: Selesai</p>
                        <p class="text-xs sm:text-sm text-green-700 font-normal">Kartu fisik Anda telah dicetak.</p>
                    </div>
                @elseif($latestRequest->status === 'ditolak')
                    <i class="fas fa-times-circle text-xl text-red-600"></i>
                    <div>
                        <p class="font-semibold text-sm sm:text-base">Request Cetak: Ditolak</p>
                        <p class="text-xs sm:text-sm text-red-700 font-normal font-normal">Alasan: {{ $latestRequest->keterangan ?? 'Data kurang valid.' }}</p>
                    </div>
                @endif
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                @if($latestRequest->status === 'pending') bg-yellow-200 text-yellow-800
                @elseif($latestRequest->status === 'diproses') bg-blue-200 text-blue-800
                @elseif($latestRequest->status === 'selesai') bg-green-200 text-green-800
                @elseif($latestRequest->status === 'ditolak') bg-red-200 text-red-800
                @endif">
                {{ $latestRequest->status }}
            </span>
        </div>
    @endif

    <!-- Digital Member Card -->
    <div id="digital-member-card" class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 rounded-xl shadow-2xl p-4 sm:p-6 lg:p-8 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full -translate-x-20 -translate-y-20"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-white rounded-full translate-x-16 translate-y-16"></div>
        </div>
        
        <!-- Card Header -->
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold">PMRJ</h2>
                    <p class="text-blue-200">Persatuan Masyarakat Riau Jakarta</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>

            <!-- Member Photo and Info -->
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 mb-6">
                @if($anggota->foto && $anggota->foto !== 'default/avatar.png')
                    <img src="{{ $anggota->foto_url }}" alt="Foto" class="w-20 sm:w-24 h-20 sm:h-24 rounded-full object-cover border-4 border-white border-opacity-30">
                @else
                    <div class="w-20 sm:w-24 h-20 sm:h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center border-4 border-white border-opacity-30">
                        <i class="fas fa-user text-3xl"></i>
                    </div>
                @endif
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-lg sm:text-xl font-bold">{{ $anggota->nama_lengkap }}</h3>
                    <p class="text-blue-200">{{ $anggota->asal_ikk }}</p>
                </div>
            </div>

            <!-- Member Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-blue-200 text-sm">No. Anggota</p>
                    <p class="text-lg sm:text-xl font-bold">{{ $anggota->no_anggota }}</p>
                </div>
                <div>
                    <p class="text-blue-200 text-sm">Status</p>
                    <p class="text-lg font-semibold capitalize">{{ $anggota->status }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-blue-200 text-sm">Bergabung</p>
                    <p class="text-lg">{{ $anggota->created_at->format('M Y') }}</p>
                </div>
            </div>


        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
        @if(!$latestRequest || in_array($latestRequest->status, ['selesai', 'ditolak']))
            <button onclick="requestCetak()" class="bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-700 flex items-center justify-center space-x-2">
                <i class="fas fa-print"></i>
                <span>Request Cetak Kartu</span>
            </button>
        @else
            <button disabled class="bg-gray-400 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg flex items-center justify-center space-x-2 cursor-not-allowed">
                <i class="fas fa-print"></i>
                <span>Request Cetak (Pending/Proses)</span>
            </button>
        @endif
        <button onclick="shareCard()" class="bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-700 flex items-center justify-center space-x-2">
            <i class="fas fa-share-alt"></i>
            <span>Bagikan</span>
        </button>
    </div>

    <!-- QR Code Section -->
    <div class="mt-6 sm:mt-8 bg-white rounded-lg shadow-lg p-4 sm:p-6 text-center">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">QR Code Anggota</h3>
        <div class="w-32 h-32 mx-auto flex items-center justify-center bg-white p-1 border rounded-lg">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('kartu.anggota')) }}" alt="QR Code" class="w-full h-full object-contain">
        </div>
        <p class="text-sm text-gray-600 mt-2">Scan untuk verifikasi keanggotaan</p>
    </div>
</div>

<script>
    const memberData = {
        nik: @json($anggota->nik),
        fotoKtp: @json($anggota->foto_ktp),
        noKk: @json($anggota->no_kk),
        fotoKk: @json($anggota->foto_kk),
        namaPerusahaan: @json($anggota->nama_perusahaan),
        jabatan: @json($anggota->jabatan),
        alamatKantor: @json($anggota->alamat_kantor),
        namaKeluargaDekat: @json($anggota->nama_keluarga_dekat),
        alamatKeluargaDekat: @json($anggota->alamat_keluarga_dekat),
        noHpKeluargaDekat: @json($anggota->no_hp_keluarga_dekat),
        noHp: @json($anggota->no_hp),
        email: @json($anggota->email)
    };

    function requestCetak() {
        const checks = {
            ktp: (memberData.nik && memberData.nik.length === 16 && memberData.fotoKtp),
            kk: (memberData.noKk && memberData.noKk.length === 16 && memberData.fotoKk),
            pekerjaan: (memberData.namaPerusahaan && memberData.jabatan && memberData.alamatKantor),
            keluarga: (memberData.namaKeluargaDekat && memberData.alamatKeluargaDekat && memberData.noHpKeluargaDekat),
            noHp: !!memberData.noHp,
            email: !!memberData.email
        };

        const isAllChecked = checks.ktp && checks.kk && checks.pekerjaan && checks.keluarga && checks.noHp && checks.email;

        let checklistHtml = `
            <div class="text-left space-y-3 p-3 bg-gray-50 rounded-lg border border-gray-200 text-sm">
                <div class="flex items-center justify-between py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-700">1. KTP (NIK & Foto KTP)</span>
                    <span>${checks.ktp ? '<i class="fas fa-check-circle text-green-500 text-lg"></i>' : '<i class="fas fa-times-circle text-red-500 text-lg"></i>'}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-700">2. Kartu Keluarga (No. KK & Foto KK)</span>
                    <span>${checks.kk ? '<i class="fas fa-check-circle text-green-500 text-lg"></i>' : '<i class="fas fa-times-circle text-red-500 text-lg"></i>'}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-700">3. Data Pekerjaan Lengkap (Perusahaan, Jabatan, Kantor)</span>
                    <span>${checks.pekerjaan ? '<i class="fas fa-check-circle text-green-500 text-lg"></i>' : '<i class="fas fa-times-circle text-red-500 text-lg"></i>'}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-700">4. Keluarga Dekat Tidak Serumah (Nama, Alamat, No. Telp)</span>
                    <span>${checks.keluarga ? '<i class="fas fa-check-circle text-green-500 text-lg"></i>' : '<i class="fas fa-times-circle text-red-500 text-lg"></i>'}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-gray-100">
                    <span class="font-medium text-gray-700">5. No. Telepon Aktif</span>
                    <span>${checks.noHp ? '<i class="fas fa-check-circle text-green-500 text-lg"></i>' : '<i class="fas fa-times-circle text-red-500 text-lg"></i>'}</span>
                </div>
                <div class="flex items-center justify-between py-1">
                    <span class="font-medium text-gray-700">6. Email Aktif</span>
                    <span>${checks.email ? '<i class="fas fa-check-circle text-green-500 text-lg"></i>' : '<i class="fas fa-times-circle text-red-500 text-lg"></i>'}</span>
                </div>
            </div>
        `;

        if (isAllChecked) {
            Swal.fire({
                title: 'Persyaratan Terpenuhi',
                html: `
                    <p class="text-sm text-gray-600 mb-4">Semua dokumen dan data persyaratan Anda telah lengkap:</p>
                    ${checklistHtml}
                `,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-paper-plane mr-2"></i>Kirim Request Cetak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3985c3',
                cancelButtonColor: '#aaa',
            }).then((result) => {
                if (result.isConfirmed) {
                    submitCetakRequest();
                }
            });
        } else {
            Swal.fire({
                title: 'Syarat Belum Lengkap',
                html: `
                    <p class="text-sm text-gray-600 mb-4">Lengkapi data profil Anda terlebih dahulu:</p>
                    ${checklistHtml}
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-user-edit mr-2"></i>Lengkapi Profil',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#3985c3',
                cancelButtonColor: '#aaa',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('profile') }}";
                }
            });
        }
    }

    function submitCetakRequest() {
        Swal.fire({
            title: 'Memproses...',
            text: 'Mengirimkan permintaan cetak kartu Anda.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch("{{ route('kartu.request-cetak') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#3985c3'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#3985c3'
                });
            }
        })
        .catch(error => {
            console.error('Error submitting print request:', error);
            Swal.fire({
                title: 'Terjadi Kesalahan!',
                text: 'Gagal menghubungi server.',
                icon: 'error',
                confirmButtonColor: '#3985c3'
            });
        });
    }

    function shareCard() {
        const title = 'Kartu Anggota PMRJ';
        const text = 'Saya adalah anggota PMRJ dengan nomor keanggotaan {{ $anggota->no_anggota }}.';
        const url = window.location.href;

        if (navigator.share) {
            navigator.share({
                title: title,
                text: text,
                url: url
            }).catch(err => {
                console.log('Share cancelled or failed', err);
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Disalin!',
                    text: 'Link kartu anggota telah disalin ke clipboard.',
                    confirmButtonColor: '#3985c3'
                });
            }).catch(err => {
                console.error('Clipboard copy error:', err);
                alert('Gagal menyalin link ke clipboard.');
            });
        }
    }
</script>
@endsection
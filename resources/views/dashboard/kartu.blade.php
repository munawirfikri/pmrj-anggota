@extends('dashboard.layout')

@section('title', 'Kartu Anggota')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-0">
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
        <button onclick="downloadCard()" class="bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-700 flex items-center justify-center space-x-2">
            <i class="fas fa-download"></i>
            <span>Download Kartu</span>
        </button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadCard() {
        const card = document.getElementById('digital-member-card');
        
        html2canvas(card, {
            useCORS: true,
            scale: 2,
            backgroundColor: null
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'KTA_PMRJ_{{ $anggota->no_anggota }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }).catch(error => {
            console.error('Download error:', error);
            alert('Gagal mengunduh kartu.');
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
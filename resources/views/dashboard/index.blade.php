@extends('dashboard.layout')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
    <!-- Welcome Card -->
    <div class="lg:col-span-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg text-white p-4 lg:p-6">
        <h2 class="text-xl lg:text-2xl font-bold mb-2">Selamat Datang, {{ $anggota->nama_lengkap }}!</h2>
        <p class="text-blue-100 mb-4">Terima kasih telah bergabung dengan PMRJ. Nikmati berbagai fasilitas dan kegiatan yang tersedia.</p>
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
            <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                <p class="text-sm">No. Anggota</p>
                <p class="font-bold">{{ $anggota->no_anggota }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                <p class="text-sm">Status</p>
                <p class="font-bold capitalize">{{ $anggota->status }}</p>
            </div>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-lg p-4 lg:p-6">
        <div class="text-center">
            @if($anggota->foto && $anggota->foto !== 'default/avatar.png')
                <img src="{{ asset('storage/' . $anggota->foto) }}" alt="Foto" class="w-20 lg:w-24 h-20 lg:h-24 rounded-full mx-auto mb-4 object-cover">
            @else
                <div class="w-20 lg:w-24 h-20 lg:h-24 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-user text-gray-600 text-2xl"></i>
                </div>
            @endif
            <h3 class="text-base lg:text-lg font-semibold text-gray-900">{{ $anggota->nama_lengkap }}</h3>
            <p class="text-gray-600">{{ $anggota->pekerjaan }}</p>
            <a href="{{ route('profile') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit Profile
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
    <a href="{{ route('kartu.anggota') }}" class="bg-white rounded-lg shadow-lg p-4 lg:p-6 hover:shadow-xl transition duration-300">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-id-card text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-base lg:text-lg font-semibold text-gray-900">Kartu Anggota</h3>
                <p class="text-gray-600 text-sm">Lihat kartu digital</p>
            </div>
        </div>
    </a>

    <div class="bg-white rounded-lg shadow-lg p-4 lg:p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-base lg:text-lg font-semibold text-gray-900">Event</h3>
                <p class="text-gray-600 text-sm">Kegiatan mendatang</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-4 lg:p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-base lg:text-lg font-semibold text-gray-900">Anggota</h3>
                <p class="text-gray-600 text-sm">Direktori anggota</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-4 lg:p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-newspaper text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-base lg:text-lg font-semibold text-gray-900">Berita</h3>
                <p class="text-gray-600 text-sm">Info terbaru</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow-lg">
    <div class="px-4 lg:px-6 py-4 border-b border-gray-200">
        <h3 class="text-base lg:text-lg font-semibold text-gray-900">Berita Terbaru</h3>
    </div>
    <div class="p-4 lg:p-6">
        <div class="space-y-4" id="news-container">
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i>
                <p class="text-gray-500 mt-2">Memuat berita...</p>
            </div>
        </div>
    </div>
</div>

<script>
fetch('/api/news')
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('news-container');
        if (data.length > 0) {
            container.innerHTML = data.map(item => `
                <div class="flex items-start space-x-4 pb-4 border-b border-gray-100 last:border-b-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 mb-1">${item.title}</h4>
                        <p class="text-sm text-gray-600 mb-2">${item.excerpt}</p>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">${item.date}</p>
                            <a href="${item.link}" target="_blank" class="text-xs text-blue-600 hover:text-blue-700">Baca selengkapnya</a>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-info-circle text-gray-400 text-2xl"></i>
                    <p class="text-gray-500 mt-2">Tidak ada agenda terbaru</p>
                </div>
            `;
        }
    })
    .catch(error => {
        document.getElementById('news-container').innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                <p class="text-gray-500 mt-2">Gagal memuat agenda</p>
            </div>
        `;
    });
</script>
@endsection
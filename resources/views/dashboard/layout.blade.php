<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PMRJ</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/logo-pmrj-black.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <!-- Mobile menu overlay -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
    
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-blue-800 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('assets/img/logo-pmrj-black.svg') }}" alt="PMRJ Logo" class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <h1 class="text-lg font-bold">PMRJ</h1>
                        <p class="text-sm text-blue-200">Dashboard Anggota</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-8">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('kartu.anggota') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('kartu.anggota') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-id-card mr-3"></i>
                    Kartu Anggota
                </a>
                <a href="{{ route('profile') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('profile') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-user mr-3"></i>
                    Profile
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center text-white hover:text-red-300">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-0 overflow-x-hidden overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <!-- Mobile menu button -->
                        <button id="mobileMenuBtn" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl lg:text-2xl font-semibold text-gray-900">@yield('title')</h1>
                        <div class="flex items-center space-x-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-gray-900">{{ auth('anggota')->user()->nama_lengkap }}</p>
                                <p class="text-sm text-gray-500">{{ auth('anggota')->user()->no_anggota }}</p>
                            </div>
                            @if(auth('anggota')->user()->foto && auth('anggota')->user()->foto !== 'default/avatar.png')
                                <img src="{{ asset('storage/' . auth('anggota')->user()->foto) }}" alt="Foto" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-4 lg:py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileMenuOverlay');
        
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3985c3'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#3985c3'
            });
        @endif
    </script>
</body>
</html>
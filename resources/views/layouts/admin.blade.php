<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Petugas & Admin - PerpusDigital')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <!-- SweetAlert2 for Instant Notifications & Pop-ups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Sembunyikan scrollbar di seluruh browser */
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .no-scrollbar {
            -ms-overflow-style: none !important;  /* IE and Edge */
            scrollbar-width: none !important;  /* Firefox */
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-xl">
        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-6 bg-slate-950/40 border-b border-slate-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-900/50">
                    <i class="fa-solid fa-book-bookmark text-base"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-wide">PerpusDigital</h1>
                    <span class="text-[10px] text-indigo-400 font-semibold tracking-wider uppercase">
                        Administrator
                    </span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto no-scrollbar px-4 py-3 space-y-3">
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Utama</p>
                <nav class="space-y-0.5">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-gauge-high w-6 text-base"></i> Dashboard
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Master Data</p>
                <nav class="space-y-0.5">
                    <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.kategori*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-layer-group w-6 text-base"></i> Kategori Buku
                    </a>
                    <a href="{{ route('admin.buku.index') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.buku*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-book w-6 text-base"></i> Data Buku & Harga
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Toko Buku Online</p>
                <nav class="space-y-0.5">
                    <a href="{{ route('admin.pesanan.index') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.pesanan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-cart-flatbed w-6 text-base"></i> Pesanan Masuk
                    </a>
                    <a href="{{ route('admin.laporan.penjualan') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.laporan.penjualan*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-line w-6 text-base"></i> Laporan Penjualan
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Perpustakaan</p>
                <nav class="space-y-0.5">
                    <a href="{{ route('admin.transaksi.index') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.transaksi*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-arrow-right-arrow-left w-6 text-base"></i> Peminjaman Buku
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.laporan.index') || request()->routeIs('admin.laporan.cetak') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-file-invoice w-6 text-base"></i> Laporan Peminjaman
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Layanan & Komunikasi</p>
                <nav class="space-y-0.5">
                    @php $pesanBaruCount = \App\Models\PesanKontak::where('status', 'belum_dibaca')->count(); @endphp
                    <a href="{{ route('admin.pesan.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.pesan.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center">
                            <i class="fa-solid fa-comments w-6 text-base"></i> Pesan Masuk
                        </span>
                        @if($pesanBaruCount > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full animate-pulse">
                                {{ $pesanBaruCount }}
                            </span>
                        @endif
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Manajemen Sistem</p>
                <nav class="space-y-0.5">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-users-gear w-6 text-base"></i> Kelola Pengguna
                    </a>
                </nav>
            </div>
        </div>

        <!-- User Profile & Logout in Sidebar Footer -->
        <div class="p-4 bg-slate-950/60 border-t border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex-shrink-0 flex items-center justify-center font-bold text-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Workspace Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top App Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between z-10">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="text-sm font-semibold text-slate-700 hidden sm:block">
                    <i class="fa-regular fa-calendar-days text-indigo-500 mr-1.5"></i>
                    {{ Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-indigo-500"></i> Buka Web Publik
                </a>
            </div>
        </header>

        <!-- Main Scrollable Content Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <!-- Alert Messages -->
            @if($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm mb-6 flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-bold text-rose-900">Gagal Menyimpan Data (Duplikasi / Kesalahan Input):</h4>
                            <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.bg-rose-50').remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-xmark text-rose-600 text-lg"></i>
                        <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-sky-50 border-l-4 border-sky-500 p-4 rounded-r-xl shadow-sm mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-info text-sky-600 text-lg"></i>
                        <p class="text-sm font-medium text-sky-800">{{ session('info') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Data Duplikat / Tidak Valid',
                html: '<div class="text-left text-xs leading-relaxed space-y-1"><p class="font-bold text-slate-800 mb-2">Terjadi kendala saat menyimpan data:</p><ul class="list-disc list-inside text-rose-600 font-medium space-y-1">@foreach($errors->all() as $err)<li>{{ addslashes($err) }}</li>@endforeach</ul></div>',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Saya Mengerti'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memproses Data',
                text: '{{ addslashes(session("error")) }}',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Tutup'
            });
        });
    </script>
    @endif

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: 'success',
                title: '{{ addslashes(session("success")) }}'
            });
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>

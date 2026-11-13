<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PerpusDigital - Perpustakaan Online Modern')</title>
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
    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen antialiased">

    <!-- Header Navigation -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm" x-data="{ mobileMenu: false, userDropdown: false }">
        <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
            <div class="flex justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-indigo-100 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-book-bookmark text-lg"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-700 to-indigo-500 bg-clip-text text-transparent">PerpusDigital</span>
                            <span class="hidden sm:inline-block text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full font-medium ml-1">Pra-USK</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex md:space-x-1 md:ml-8">
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-house mr-1.5 text-xs"></i> Beranda
                        </a>
                        <a href="{{ route('katalog') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('katalog*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-book-open mr-1.5 text-xs"></i> Katalog Buku
                        </a>
                        <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('about') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-circle-info mr-1.5 text-xs"></i> About Us
                        </a>
                        @if(!Auth::check() || !Auth::user()->isAdmin())
                            <a href="{{ route('contact') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('contact*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">
                                <i class="fa-solid fa-envelope mr-1.5 text-xs"></i> Contact to Admin
                            </a>
                        @endif
                        @auth
                            @if(Auth::user()->isPeminjam())
                                <a href="{{ route('peminjaman.riwayat') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('peminjaman.riwayat') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-clock-rotate-left mr-1.5 text-xs"></i> Riwayat Pinjam
                                </a>
                                <a href="{{ route('pesanan.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('pesanan*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-bag-shopping mr-1.5 text-xs"></i> Pesanan Saya
                                </a>
                                <a href="{{ route('koleksi.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('koleksi.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50' }}">
                                    <i class="fa-solid fa-bookmark mr-1.5 text-xs"></i> Koleksi Favorit
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center space-x-3">
                    @auth
                        @if(Auth::user()->isPeminjam())
                            @php $cartCount = Auth::user()->keranjang()->sum('jumlah'); @endphp
                            <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-full border border-slate-200 hover:border-indigo-400 bg-white text-slate-700 hover:text-indigo-600 transition shadow-sm" title="Keranjang Belanja">
                                <i class="fa-solid fa-cart-shopping text-sm"></i>
                                @if($cartCount > 0)
                                    <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold flex items-center justify-center shadow-md animate-pulse">
                                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition">
                            Daftar Anggota
                        </a>
                    @else
                        <!-- User Menu Dropdown -->
                        <div class="relative" @click.outside="userDropdown = false">
                            <button @click="userDropdown = !userDropdown" type="button" class="flex items-center space-x-2.5 p-1.5 pl-3 rounded-full border border-slate-200 hover:border-slate-300 bg-white transition focus:outline-none">
                                <div class="text-left hidden sm:block">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] uppercase font-semibold text-indigo-600 tracking-wider">
                                        {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Anggota' }}
                                    </p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 pr-1"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="userDropdown" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 z-50">
                                <div class="px-4 py-2.5 border-b border-slate-100">
                                    <p class="text-xs text-slate-500">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 text-[11px] font-semibold px-2 py-0.5 rounded-full {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        Role: {{ Auth::user()->isAdmin() ? 'Administrator' : 'Anggota' }}
                                    </span>
                                </div>

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-chart-line w-5 text-indigo-500"></i> Panel Administrator
                                    </a>
                                @endif

                                @if(Auth::user()->isPeminjam())
                                    <a href="{{ route('cart.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-cart-shopping w-5 text-indigo-500"></i> Keranjang Belanja
                                    </a>
                                    <a href="{{ route('pesanan.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-bag-shopping w-5 text-indigo-500"></i> Pesanan Saya
                                    </a>
                                    <a href="{{ route('peminjaman.riwayat') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-book-reader w-5 text-indigo-500"></i> Riwayat Pinjam
                                    </a>
                                    <a href="{{ route('koleksi.index') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-heart w-5 text-pink-500"></i> Koleksi Favorit
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    <!-- Mobile Menu Toggle Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <i :class="mobileMenu ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" x-transition class="md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                <i class="fa-solid fa-house mr-2"></i> Beranda
            </a>
            <a href="{{ route('katalog') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('katalog*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                <i class="fa-solid fa-book-open mr-2"></i> Katalog Buku
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('about') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                <i class="fa-solid fa-circle-info mr-2"></i> About Us (Tentang Kami)
            </a>
            @if(!Auth::check() || !Auth::user()->isAdmin())
                <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('contact*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                    <i class="fa-solid fa-envelope mr-2"></i> Contact to Admin
                </a>
            @endif
            @auth
                @if(Auth::user()->isPeminjam())
                    <a href="{{ route('cart.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('cart.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                        <i class="fa-solid fa-cart-shopping mr-2"></i> Keranjang Belanja
                    </a>
                    <a href="{{ route('pesanan.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('pesanan*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                        <i class="fa-solid fa-bag-shopping mr-2"></i> Pesanan Saya
                    </a>
                    <a href="{{ route('peminjaman.riwayat') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('peminjaman.riwayat') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i> Riwayat Pinjam
                    </a>
                    <a href="{{ route('koleksi.index') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('koleksi.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600' }}">
                        <i class="fa-solid fa-bookmark mr-2"></i> Koleksi Favorit
                    </a>
                @endif
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-indigo-600 bg-indigo-50">
                        <i class="fa-solid fa-chart-line mr-2"></i> Panel Administrator
                    </a>
                @endif
            @endauth
        </div>
    </nav>

    <!-- Flash Alert Messages -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 mt-4">
        @if($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm mb-4 flex items-start justify-between">
                <div class="flex items-start space-x-3">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-rose-900">Perhatian:</h4>
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
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm mb-4 flex items-center justify-between">
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
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm mb-4 flex items-center justify-between">
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
            <div class="bg-sky-50 border-l-4 border-sky-500 p-4 rounded-r-xl shadow-sm mb-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-circle-info text-sky-600 text-lg"></i>
                    <p class="text-sm font-medium text-sky-800">{{ session('info') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-16">
        <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: About -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-book-bookmark text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-white">PerpusDigital</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Sistem Perpustakaan Digital Berbasis Web modern untuk mendukung literasi sekolah dan kemudahan peminjaman buku secara online. Dibangun menggunakan Laravel dengan standar UKK/Pra-USK RPL.
                    </p>
                    <div class="flex space-x-3 text-slate-400">
                        <span class="inline-flex items-center text-xs bg-slate-800 px-3 py-1 rounded-full"><i class="fa-solid fa-shield-halved mr-1 text-emerald-400"></i> Standar Uji Kompetensi</span>
                        <span class="inline-flex items-center text-xs bg-slate-800 px-3 py-1 rounded-full"><i class="fa-brands fa-laravel mr-1 text-red-500"></i> Laravel 12</span>
                    </div>
                </div>

                <!-- Col 2: Navigasi -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigasi Cepat</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-indigo-400 transition">Beranda</a></li>
                        <li><a href="{{ route('katalog') }}" class="hover:text-indigo-400 transition">Katalog Buku</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-indigo-400 transition">About Us (Tentang Kami)</a></li>
                        @if(!Auth::check() || !Auth::user()->isAdmin())
                            <li><a href="{{ route('contact') }}" class="hover:text-indigo-400 transition">Contact to Admin</a></li>
                        @endif
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-400 transition">Masuk Akun</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-indigo-400 transition">Pendaftaran Anggota</a></li>
                    </ul>
                </div>

                <!-- Col 3: Jam Layanan & Kontak -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Layanan Sirkulasi</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex items-center space-x-2">
                            <i class="fa-regular fa-clock text-indigo-400"></i>
                            <span>Senin - Jumat: 07.30 - 15.30 WIB</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-location-dot text-indigo-400"></i>
                            <span>Gedung Perpustakaan Lantai 2</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-phone text-indigo-400"></i>
                            <span>(021) 555-0199</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} PerpusDigital - Projek Ujian Sertifikasi Kompetensi (Pra-USK RPL).</p>
                <p class="mt-2 sm:mt-0">Dibuat dengan dedikasi untuk kemajuan literasi digital.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Quick Contact / Chat Admin Button (Hanya untuk User / Tamu) -->
    @if(!Auth::check() || !Auth::user()->isAdmin())
    <div class="fixed bottom-6 right-6 z-40 group">
        <a href="{{ route('contact') }}" class="flex items-center space-x-2.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-full shadow-xl shadow-indigo-600/30 hover:scale-105 transition-all">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            <i class="fa-solid fa-headset text-base"></i>
            <span class="text-xs font-bold hidden sm:inline-block">Contact Admin</span>
        </a>
    </div>
    @endif

    @stack('scripts')
</body>
</html>

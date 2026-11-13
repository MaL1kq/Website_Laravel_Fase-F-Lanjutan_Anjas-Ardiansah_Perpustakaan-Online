@extends('layouts.app')

@section('title', 'PerpusDigital - Sistem Perpustakaan Online Terpadu')

@section('content')
<!-- Hero Section (Full Viewport Height & Fluid Scaling) -->
<section class="relative bg-gradient-to-b from-indigo-950 via-indigo-900 to-indigo-950 text-white overflow-hidden min-h-[calc(100vh-4rem)] flex flex-col justify-between py-8 sm:py-12">
    <!-- Subtle Background Grid Pattern -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="grid" width="48" height="48" patternUnits="userSpaceOnUse">
                    <path d="M 48 0 L 0 0 0 48" fill="none" stroke="currentColor" stroke-width="1"></path>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)"></rect>
        </svg>
    </div>

    <!-- Empty Spacer for vertical balance -->
    <div></div>

    <!-- Main Hero Center Content -->
    <div class="relative w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 text-center my-auto">
        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-indigo-500/25 text-indigo-200 border border-indigo-400/30 mb-6 backdrop-blur-md shadow-sm">
            <i class="fa-solid fa-sparkles mr-2 text-amber-300"></i> Perpustakaan Online Terpadu (Pra-USK RPL)
        </span>

        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight">
            Jelajahi Ribuan Ilmu & Pinjam Buku dengan Mudah
        </h1>

        <p class="mt-4 text-base sm:text-lg lg:text-xl text-indigo-200/90 max-w-3xl mx-auto font-normal leading-relaxed">
            Platform perpustakaan digital interaktif untuk siswa dan masyarakat. Cari buku favorit, baca ulasan, dan lakukan peminjaman online kapan saja.
        </p>

        <!-- Search Bar Hero (Expanded Width) -->
        <div class="mt-8 max-w-3xl lg:max-w-4xl mx-auto">
            <form action="{{ route('katalog') }}" method="GET" class="relative flex items-center">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-lg"></i>
                    </span>
                    <input type="text" name="q" placeholder="Cari judul buku, penulis, penerbit, atau ISBN..."
                        class="w-full pl-12 sm:pl-14 pr-32 sm:pr-36 py-4 sm:py-5 rounded-2xl text-slate-900 bg-white placeholder-slate-400 text-sm sm:text-base font-medium shadow-2xl focus:outline-none focus:ring-4 focus:ring-indigo-400/50">
                    <button type="submit" class="absolute right-2 top-2 bottom-2 px-5 sm:px-7 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs sm:text-sm transition flex items-center shadow-md">
                        <span>Cari</span>
                        <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </button>
                </div>
            </form>
            <div class="mt-3.5 flex flex-wrap justify-center items-center gap-2 text-xs sm:text-sm text-indigo-200">
                <span class="text-indigo-300/80">Pencarian populer:</span>
                <a href="{{ route('katalog', ['q' => 'Laravel']) }}" class="underline hover:text-white transition">Laravel</a>,
                <a href="{{ route('katalog', ['q' => 'Clean Code']) }}" class="underline hover:text-white transition">Clean Code</a>,
                <a href="{{ route('katalog', ['q' => 'Filosofi Teras']) }}" class="underline hover:text-white transition">Filosofi Teras</a>,
                <a href="{{ route('katalog', ['q' => 'Laskar Pelangi']) }}" class="underline hover:text-white transition">Laskar Pelangi</a>
            </div>
        </div>

        <!-- Quick Stat Badges Grid (Fluid Scaling) -->
        <div class="mt-10 sm:mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 max-w-5xl lg:max-w-6xl mx-auto">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition shadow-lg">
                <p class="text-2xl sm:text-4xl font-extrabold text-white">{{ $totalBuku }}</p>
                <p class="text-[11px] sm:text-xs text-indigo-200 uppercase font-semibold tracking-wider mt-1">Koleksi Buku</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition shadow-lg">
                <p class="text-2xl sm:text-4xl font-extrabold text-white">{{ $totalKategori }}</p>
                <p class="text-[11px] sm:text-xs text-indigo-200 uppercase font-semibold tracking-wider mt-1">Kategori Pilihan</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition shadow-lg">
                <p class="text-2xl sm:text-4xl font-extrabold text-white">{{ $totalPeminjam }}</p>
                <p class="text-[11px] sm:text-xs text-indigo-200 uppercase font-semibold tracking-wider mt-1">Anggota Aktif</p>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/10 hover:bg-white/15 transition shadow-lg">
                <p class="text-2xl sm:text-4xl font-extrabold text-white">{{ $totalTransaksi }}</p>
                <p class="text-[11px] sm:text-xs text-indigo-200 uppercase font-semibold tracking-wider mt-1">Buku Dipinjam</p>
            </div>
        </div>
    </div>

    <!-- Scroll Down Indicator -->
    <div class="relative text-center pt-6 pb-2">
        <a href="#kategori-section" class="inline-flex flex-col items-center text-xs text-indigo-300 hover:text-white transition animate-bounce">
            <span class="text-[11px] font-semibold tracking-wider uppercase mb-1">Jelajahi Koleksi</span>
            <i class="fa-solid fa-chevron-down text-xs"></i>
        </a>
    </div>
</section>

<!-- Kategori Buku Section (Fluid Wide Container) -->
<section id="kategori-section" class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-16 sm:py-20">
    <div class="flex justify-between items-end mb-8">
        <div>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Katalog Beragam</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mt-1">Kategori Buku</h2>
        </div>
        <a href="{{ route('katalog') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition flex items-center">
            <span>Lihat Semua</span>
            <i class="fa-solid fa-chevron-right ml-1 text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
        @foreach($kategoriList as $kat)
            <a href="{{ route('katalog', ['kategori' => $kat->slug]) }}"
               class="bg-white p-6 rounded-2xl border border-slate-200/80 hover:border-indigo-400 hover:shadow-xl hover:shadow-indigo-50 hover:-translate-y-1 transition duration-200 group flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-3.5 group-hover:bg-indigo-600 group-hover:text-white transition duration-200 shadow-sm">
                    <i class="fa-solid fa-book"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm sm:text-base line-clamp-1 group-hover:text-indigo-600 transition">{{ $kat->nama_kategori }}</h3>
                <span class="text-xs text-slate-400 mt-1">{{ $kat->buku_count }} Buku</span>
            </a>
        @endforeach
    </div>
</section>

<!-- Buku Terpopuler Section (Fluid Wide Container) -->
<section class="bg-white py-16 sm:py-20 border-y border-slate-200/80">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex justify-between items-end mb-8">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Paling Banyak Dibaca</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mt-1">Buku Terpopuler</h2>
            </div>
            <a href="{{ route('katalog', ['sort' => 'popular']) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition flex items-center">
                <span>Katalog Lengkap</span>
                <i class="fa-solid fa-chevron-right ml-1 text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6">
            @foreach($bukuPopuler as $item)
                <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-5 flex space-x-4 hover:shadow-lg hover:border-indigo-200 transition duration-200">
                    <img src="{{ $item->cover_url }}" alt="{{ $item->judul }}" class="w-24 h-36 object-cover rounded-xl shadow-md flex-shrink-0 bg-slate-200">
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-md inline-block mb-1 border border-indigo-100">
                                {{ $item->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base line-clamp-2 leading-snug">
                                <a href="{{ route('buku.detail', $item->slug) }}" class="hover:text-indigo-600 transition">
                                    {{ $item->judul }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">Penulis: {{ $item->penulis }}</p>
                            <span class="text-xs font-black text-emerald-700 block mt-1">{{ $item->harga_format }}</span>
                        </div>

                        <div class="pt-3 border-t border-slate-200 flex items-center justify-between text-xs">
                            <div class="flex items-center text-amber-500">
                                <i class="fa-solid fa-star mr-1 text-xs"></i>
                                <span class="font-bold text-slate-700">{{ $item->rataRataRating() }}</span>
                                <span class="text-slate-400 ml-1">({{ $item->totalUlasan() }})</span>
                            </div>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $item->stok > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $item->stok > 0 ? 'Stok: ' . $item->stok : 'Habis' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Buku Terbaru Section (Fluid Wide Container with 2xl scale) -->
<section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-16 sm:py-20">
    <div class="flex justify-between items-end mb-8">
        <div>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Koleksi Terkini</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mt-1">Baru Ditambahkan</h2>
        </div>
        <a href="{{ route('katalog') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition flex items-center">
            <span>Lihat Semua</span>
            <i class="fa-solid fa-chevron-right ml-1 text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
        @foreach($bukuTerbaru as $buku)
            <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-1.5 transition duration-200 flex flex-col">
                <div class="relative bg-slate-100 aspect-[3/4] overflow-hidden">
                    <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                    <div class="absolute top-2.5 left-2.5">
                        <span class="bg-slate-900/85 backdrop-blur-sm text-white text-[10px] font-semibold px-2.5 py-1 rounded-full shadow-sm">
                            {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                    </div>
                    <div class="absolute top-2.5 right-2.5">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm {{ $buku->stok > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                            {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm line-clamp-2 leading-snug hover:text-indigo-600 transition">
                            <a href="{{ route('buku.detail', $buku->slug) }}">{{ $buku->judul }}</a>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">Oleh <span class="font-medium text-slate-700">{{ $buku->penulis }}</span></p>
                        <span class="text-xs font-black text-emerald-700 block mt-1">{{ $buku->harga_format }}</span>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center text-xs text-amber-500">
                            <i class="fa-solid fa-star text-xs mr-1"></i>
                            <span class="font-bold text-slate-700">{{ $buku->rataRataRating() }}</span>
                        </div>
                        <a href="{{ route('buku.detail', $buku->slug) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-600 font-semibold text-xs transition shadow-sm">
                            Detail & Beli
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- CTA Banner (Fluid Wide) -->
<section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 pb-20">
    <div class="bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-800 rounded-3xl p-8 sm:p-14 text-white shadow-2xl shadow-indigo-200/50 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-2xl">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-200 bg-white/10 px-3 py-1 rounded-full border border-white/10">Mari Gemar Membaca</span>
            <h3 class="text-2xl sm:text-4xl font-extrabold mt-3 leading-snug">Sudah Siap Membaca Buku Hari Ini?</h3>
            <p class="text-indigo-100 text-sm sm:text-base mt-2 leading-relaxed">
                Daftar sekarang menjadi anggota perpustakaan digital untuk meminjam buku secara mandiri, mengumpulkan koleksi favorit, dan memberikan ulasan berharga.
            </p>
        </div>
        <div class="flex flex-wrap gap-3.5 flex-shrink-0">
            @guest
                <a href="{{ route('register') }}" class="px-7 py-4 rounded-2xl bg-white text-indigo-700 font-bold text-sm hover:bg-indigo-50 shadow-lg transition duration-150">
                    Daftar Anggota Gratis
                </a>
                <a href="{{ route('login') }}" class="px-7 py-4 rounded-2xl bg-indigo-900/60 text-white font-bold text-sm hover:bg-indigo-900 transition border border-white/20">
                    Masuk Akun
                </a>
            @else
                <a href="{{ route('katalog') }}" class="px-7 py-4 rounded-2xl bg-white text-indigo-700 font-bold text-sm hover:bg-indigo-50 shadow-lg transition duration-150">
                    Jelajahi Katalog Buku
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection

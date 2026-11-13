@extends('layouts.app')

@section('title', 'Katalog Buku Perpustakaan - PerpusDigital')

@section('content')
<div class="bg-indigo-900/5 border-b border-indigo-100 py-8">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Katalog Koleksi Buku</h1>
        <p class="text-sm text-slate-500 mt-1">Temukan berbagai referensi ilmu, novel, materi pembelajaran, dan buku berkualitas.</p>
    </div>
</div>

<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 2xl:grid-cols-5 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1 2xl:col-span-1 space-y-6">
            <!-- Search Widget -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-3">Pencarian</h3>
                <form action="{{ route('katalog') }}" method="GET">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Judul, Penulis, ISBN..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400 text-sm"></i>
                    </div>
                    <button type="submit" class="w-full mt-2.5 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition">
                        Cari Buku
                    </button>
                    @if(request()->anyFilled(['q', 'kategori', 'sort']))
                        <a href="{{ route('katalog') }}" class="block text-center mt-2 text-xs text-rose-600 hover:underline">
                            <i class="fa-solid fa-rotate-left mr-1"></i> Reset Filter
                        </a>
                    @endif
                </form>
            </div>

            <!-- Categories Widget -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-3">Kategori Buku</h3>
                <ul class="space-y-1.5 text-sm">
                    <li>
                        <a href="{{ route('katalog', array_merge(request()->except('kategori'), [])) }}"
                           class="flex justify-between items-center px-3 py-2 rounded-xl transition {{ !request('kategori') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>Semua Kategori</span>
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">{{ $buku->total() }}</span>
                        </a>
                    </li>
                    @foreach($kategoriList as $kat)
                        <li>
                            <a href="{{ route('katalog', array_merge(request()->except('kategori'), ['kategori' => $kat->slug])) }}"
                               class="flex justify-between items-center px-3 py-2 rounded-xl transition {{ request('kategori') === $kat->slug ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span class="line-clamp-1">{{ $kat->nama_kategori }}</span>
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-semibold">{{ $kat->buku_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Main Book List -->
        <div class="lg:col-span-3 2xl:col-span-4 space-y-6">
            <!-- Filter & Sort Bar -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $buku->firstItem() ?? 0 }}-{{ $buku->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $buku->total() }}</span> buku
                </p>

                <!-- Sorting Dropdown -->
                <form action="{{ route('katalog') }}" method="GET" class="flex items-center space-x-2 w-full sm:w-auto">
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    <label for="sort" class="text-xs text-slate-500 whitespace-nowrap">Urutkan:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-xs font-semibold rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul (Z - A)</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </form>
            </div>

            <!-- Book Grid -->
            @if($buku->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    @foreach($buku as $item)
                        <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-200 flex flex-col">
                            <div class="relative bg-slate-100 aspect-[3/4] overflow-hidden">
                                <img src="{{ $item->cover_url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                <div class="absolute top-2.5 left-2.5">
                                    <span class="bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                        {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                    </span>
                                </div>
                                <div class="absolute top-2.5 right-2.5">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm {{ $item->stok > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                        {{ $item->stok > 0 ? 'Sisa ' . $item->stok : 'Habis' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm line-clamp-2 leading-snug hover:text-indigo-600 transition">
                                        <a href="{{ route('buku.detail', $item->slug) }}">{{ $item->judul }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-1">Penulis: {{ $item->penulis }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $item->penerbit }} ({{ $item->tahun_terbit }})</p>
                                    <span class="text-xs font-black text-emerald-700 block mt-1.5">{{ $item->harga_format }}</span>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center text-xs text-amber-500">
                                        <i class="fa-solid fa-star text-[11px] mr-1"></i>
                                        <span class="font-bold text-slate-700">{{ $item->rataRataRating() }}</span>
                                    </div>
                                    <a href="{{ route('buku.detail', $item->slug) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-600 font-semibold text-xs transition">
                                        Detail & Beli
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-6">
                    {{ $buku->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak ada buku ditemukan</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                        Coba periksa ejaan kata kunci atau gunakan filter kategori yang berbeda.
                    </p>
                    <a href="{{ route('katalog') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition">
                        Tampilkan Semua Buku
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

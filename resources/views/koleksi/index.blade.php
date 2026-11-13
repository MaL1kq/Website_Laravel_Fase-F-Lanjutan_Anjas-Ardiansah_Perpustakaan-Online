@extends('layouts.app')

@section('title', 'Koleksi Buku Favorit - PerpusDigital')

@section('content')
<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-10">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Koleksi Buku Pribadi</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar buku-buku yang Anda tandai sebagai favorit untuk dibaca nanti.</p>
        </div>
        <a href="{{ route('katalog') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Tambah Buku Lain
        </a>
    </div>

    @if($koleksi->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-6">
            @foreach($koleksi as $item)
                @php $buku = $item->buku; @endphp
                <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-200 flex flex-col relative group">
                    <!-- Remove from favorite button -->
                    <form action="{{ route('koleksi.toggle', $buku->id) }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                        @csrf
                        <button type="submit" title="Hapus dari Koleksi" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm text-pink-600 hover:bg-pink-600 hover:text-white transition flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-heart text-sm"></i>
                        </button>
                    </form>

                    <div class="relative bg-slate-100 aspect-[3/4] overflow-hidden">
                        <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                        <div class="absolute top-2.5 left-2.5">
                            <span class="bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm line-clamp-2 leading-snug hover:text-indigo-600 transition">
                                <a href="{{ route('buku.detail', $buku->slug) }}">{{ $buku->judul }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">Penulis: {{ $buku->penulis }}</p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-semibold {{ $buku->stok > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $buku->stok > 0 ? 'Stok: ' . $buku->stok : 'Habis' }}
                            </span>
                            <a href="{{ route('buku.detail', $buku->slug) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-600 font-semibold text-xs transition">
                                Detail & Pinjam
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-6">
            {{ $koleksi->links() }}
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80">
            <div class="w-16 h-16 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-heart"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Koleksi Favorit Masih Kosong</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Anda belum menandai buku apapun ke koleksi favorit. Jelajahi katalog buku dan klik ikon hati untuk menyimpan.
            </p>
            <a href="{{ route('katalog') }}" class="inline-block mt-5 px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                Jelajahi Katalog Buku
            </a>
        </div>
    @endif
</div>
@endsection

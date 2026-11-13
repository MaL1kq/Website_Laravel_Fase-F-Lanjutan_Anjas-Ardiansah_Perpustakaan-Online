@extends('layouts.app')

@section('title', $buku->judul . ' - PerpusDigital')

@section('content')
<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-10" x-data="{ modalPinjam: false, ratingInput: {{ $userUlasan ? $userUlasan->rating : 5 }}, beliQty: 1 }">
    <!-- Breadcrumb -->
    <nav class="flex items-center text-xs text-slate-500 mb-6 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('katalog') }}" class="hover:text-indigo-600 transition">Katalog</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('katalog', ['kategori' => $buku->kategori->slug ?? '']) }}" class="hover:text-indigo-600 transition">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-800 font-semibold truncate max-w-xs">{{ $buku->judul }}</span>
    </nav>

    <!-- Main Detail Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Book Cover -->
            <div class="md:col-span-4 lg:col-span-3 flex flex-col items-center">
                <div class="w-full max-w-[260px] aspect-[3/4] rounded-2xl overflow-hidden shadow-xl border border-slate-200/60 relative">
                    <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3">
                        <span class="text-xs font-bold px-3 py-1 rounded-full shadow-md {{ $buku->stok > 0 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                            {{ $buku->stok > 0 ? 'Tersedia: ' . $buku->stok : 'Stok Habis' }}
                        </span>
                    </div>
                </div>

                <!-- Favorite / Koleksi Toggle -->
                @auth
                    @if(Auth::user()->isPeminjam())
                        <form action="{{ route('koleksi.toggle', $buku->id) }}" method="POST" class="w-full max-w-[260px] mt-4">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold border transition flex items-center justify-center space-x-2 {{ $isFavorit ? 'border-pink-300 bg-pink-50 text-pink-700 hover:bg-pink-100' : 'border-slate-300 text-slate-700 hover:bg-slate-50' }}">
                                <i class="fa-solid fa-heart {{ $isFavorit ? 'text-pink-600' : 'text-slate-400' }}"></i>
                                <span>{{ $isFavorit ? 'Tersimpan di Favorit' : 'Tambah ke Koleksi Favorit' }}</span>
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            <!-- Book Info & Action -->
            <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                            {{ $buku->kategori->nama_kategori ?? 'Umum' }}
                        </span>
                        <div class="flex items-center text-amber-500 text-sm ml-2">
                            <i class="fa-solid fa-star mr-1"></i>
                            <span class="font-bold text-slate-800">{{ $buku->rataRataRating() }}</span>
                            <span class="text-slate-400 ml-1">({{ $buku->totalUlasan() }} Ulasan Pembaca)</span>
                        </div>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                        {{ $buku->judul }}
                    </h1>
                    <p class="text-sm text-slate-600 mt-2">
                        Karya <span class="font-bold text-indigo-600">{{ $buku->penulis }}</span>
                    </p>

                    <!-- Harga Buku Banner -->
                    <div class="mt-4 p-4 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-white border border-emerald-200/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block">Harga Beli Toko Buku</span>
                            <div class="flex items-baseline space-x-2 mt-0.5">
                                <span class="text-2xl sm:text-3xl font-black text-emerald-700">{{ $buku->harga_format }}</span>
                                <span class="text-[11px] text-slate-400">/ eksemplar fisik</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center text-[11px] font-bold text-emerald-800 bg-white border border-emerald-200 px-3 py-1.5 rounded-xl shadow-sm">
                                <i class="fa-solid fa-box-check mr-1.5 text-emerald-600"></i> Buku Asli / Original
                            </span>
                        </div>
                    </div>

                    <!-- Metadata Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Penerbit</span>
                            <span class="font-bold text-slate-800 mt-0.5 block truncate">{{ $buku->penerbit }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Tahun Terbit</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $buku->tahun_terbit }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">ISBN</span>
                            <span class="font-bold text-slate-800 mt-0.5 block font-mono">{{ $buku->isbn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Lokasi Rak</span>
                            <span class="font-bold text-indigo-700 mt-0.5 block">{{ $buku->lokasi_rak ?? 'Umum' }}</span>
                        </div>
                    </div>

                    <!-- Sinopsis / Deskripsi -->
                    <div class="mt-6">
                        <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-2">Sinopsis Buku</h3>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $buku->deskripsi ?? 'Belum ada sinopsis untuk buku ini.' }}
                        </p>
                    </div>
                </div>

                <!-- Dual Action Area: Toko Buku (Beli) & Perpustakaan (Pinjam) -->
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Card 1: Beli Buku (Toko Buku) -->
                        <div class="p-5 rounded-2xl border-2 border-emerald-200 bg-emerald-50/40 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-black text-emerald-800 uppercase tracking-wider flex items-center">
                                        <i class="fa-solid fa-cart-shopping mr-1.5 text-emerald-600"></i> Beli Buku Baru
                                    </span>
                                    <span class="text-xs font-black text-emerald-700">{{ $buku->harga_format }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3">Beli buku fisik original langsung dari Toko Buku Digital Perpus.</p>
                            </div>

                            @guest
                                <a href="{{ route('login') }}" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-200 transition text-center flex items-center justify-center">
                                    <i class="fa-solid fa-lock mr-1.5"></i> Masuk untuk Membeli
                                </a>
                            @else
                                @if(Auth::user()->isPeminjam())
                                    @if($buku->stok <= 0)
                                        <button disabled class="w-full py-2.5 px-4 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed">
                                            <i class="fa-solid fa-ban mr-1.5"></i> Stok Buku Habis
                                        </button>
                                    @else
                                        <form action="{{ route('cart.add', $buku->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs font-semibold text-slate-600">Qty:</span>
                                                    <div class="flex items-center border border-slate-300 rounded-xl bg-white overflow-hidden shadow-sm">
                                                        <button type="button" @click="beliQty = Math.max(1, beliQty - 1)" class="px-2.5 py-1 text-slate-600 hover:bg-slate-100 font-bold text-xs transition">-</button>
                                                        <input type="number" name="jumlah" x-model.number="beliQty" min="1" max="{{ $buku->stok }}" class="w-12 text-center text-xs font-bold py-1 border-0 focus:ring-0">
                                                        <button type="button" @click="beliQty = Math.min({{ $buku->stok }}, beliQty + 1)" class="px-2.5 py-1 text-slate-600 hover:bg-slate-100 font-bold text-xs transition">+</button>
                                                    </div>
                                                </div>
                                                <span class="text-[11px] text-slate-500">Stok: <strong>{{ $buku->stok }}</strong></span>
                                            </div>

                                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-200 transition flex items-center justify-center">
                                                <i class="fa-solid fa-cart-plus mr-1.5"></i> Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-xs bg-white text-slate-500 p-2 rounded-xl border border-slate-200 block text-center">
                                        Login sebagai {{ Auth::user()->role }}
                                    </span>
                                @endif
                            @endguest
                        </div>

                        <!-- Card 2: Pinjam Buku Online (Perpustakaan Gratis) -->
                        <div class="p-5 rounded-2xl border-2 border-indigo-200 bg-indigo-50/40 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-black text-indigo-900 uppercase tracking-wider flex items-center">
                                        <i class="fa-solid fa-book-reader mr-1.5 text-indigo-600"></i> Pinjam Buku (Gratis)
                                    </span>
                                    <span class="text-[11px] font-bold text-indigo-600 bg-indigo-100/70 px-2 py-0.5 rounded-full">Maks. 14 Hari</span>
                                </div>
                                <p class="text-xs text-slate-500 mb-3">Pinjam gratis untuk keperluan belajar mengajar siswa.</p>
                            </div>

                            @guest
                                <a href="{{ route('login') }}" class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition text-center flex items-center justify-center">
                                    <i class="fa-solid fa-lock mr-1.5"></i> Masuk untuk Meminjam
                                </a>
                            @else
                                @if(Auth::user()->isPeminjam())
                                    @if($sudahPinjamAktif)
                                        <a href="{{ route('peminjaman.riwayat') }}" class="w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm transition flex items-center justify-center">
                                            <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Anda Sedang Meminjam Buku Ini
                                        </a>
                                    @elseif($buku->stok <= 0)
                                        <button disabled class="w-full py-2.5 px-4 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed">
                                            <i class="fa-solid fa-ban mr-1.5"></i> Stok Buku Habis
                                        </button>
                                    @else
                                        <button @click="modalPinjam = true" type="button" class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center justify-center">
                                            <i class="fa-solid fa-hand-holding-hand mr-1.5"></i> Ajukan Peminjaman Online
                                        </button>
                                    @endif
                                @else
                                    <span class="text-xs bg-white text-slate-500 p-2 rounded-xl border border-slate-200 block text-center">
                                        Login sebagai {{ Auth::user()->role }}
                                    </span>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating & Reviews Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Review Summary & Form -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm text-center">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nilai Rata-Rata</span>
                <div class="text-5xl font-extrabold text-slate-900 mt-2">{{ $buku->rataRataRating() }}</div>
                <div class="flex justify-center text-amber-400 text-lg my-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= round($buku->rataRataRating()) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                    @endfor
                </div>
                <p class="text-xs text-slate-500">Berdasarkan {{ $buku->totalUlasan() }} ulasan pembaca</p>
            </div>

            <!-- Form Beri Ulasan (Hanya untuk anggota yang login) -->
            @auth
                @if(Auth::user()->isPeminjam())
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                        <h3 class="font-bold text-slate-900 text-sm mb-3">
                            {{ $userUlasan ? 'Perbarui Ulasan Anda' : 'Beri Ulasan & Rating' }}
                        </h3>
                        <form action="{{ route('ulasan.store', $buku->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Rating Bintang:</label>
                                <input type="hidden" name="rating" :value="ratingInput">
                                <div class="flex space-x-1 text-2xl cursor-pointer">
                                    <template x-for="star in 5">
                                        <button type="button" @click="ratingInput = star" class="focus:outline-none transition-transform hover:scale-110">
                                            <i class="fa-solid fa-star" :class="star <= ratingInput ? 'text-amber-400' : 'text-slate-200'"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label for="ulasan" class="block text-xs font-semibold text-slate-600 mb-1">Ulasan Anda:</label>
                                <textarea name="ulasan" id="ulasan" rows="3" required placeholder="Bagikan pendapat Anda tentang isi buku ini..."
                                    class="w-full p-3 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ $userUlasan->ulasan ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs shadow-sm transition">
                                {{ $userUlasan ? 'Simpan Perubahan' : 'Kirim Ulasan' }}
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Readers Reviews List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-6 flex items-center justify-between">
                    <span>Ulasan Pembaca ({{ $buku->ulasan->count() }})</span>
                </h3>

                @if($buku->ulasan->count() > 0)
                    <div class="space-y-4 divide-y divide-slate-100">
                        @foreach($buku->ulasan as $ulasan)
                            <div class="pt-4 first:pt-0">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center uppercase">
                                            {{ substr($ulasan->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900">{{ $ulasan->user->name ?? 'Anonim' }}</h4>
                                            <span class="text-[10px] text-slate-400">{{ $ulasan->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex text-amber-400 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $ulasan->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 pl-10 leading-relaxed">{{ $ulasan->ulasan }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400">
                        <i class="fa-regular fa-comments text-3xl mb-2"></i>
                        <p class="text-xs">Belum ada ulasan untuk buku ini. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Books -->
    @if($bukuTerkait->count() > 0)
        <div>
            <h3 class="text-xl font-bold text-slate-900 mb-4">Buku Terkait dalam Kategori yang Sama</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($bukuTerkait as $terkait)
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-sm hover:shadow-md transition">
                        <img src="{{ $terkait->cover_url }}" alt="{{ $terkait->judul }}" class="w-full aspect-[3/4] object-cover rounded-xl mb-3">
                        <h4 class="font-bold text-xs text-slate-900 line-clamp-1">
                            <a href="{{ route('buku.detail', $terkait->slug) }}" class="hover:text-indigo-600 transition">{{ $terkait->judul }}</a>
                        </h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $terkait->penulis }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Modal Form Pinjam Buku -->
    <div x-show="modalPinjam" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalPinjam" @click="modalPinjam = false" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="modalPinjam" x-transition.scale class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-book-reader"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Formulir Peminjaman Buku</h3>
                            <p class="text-xs text-slate-500">Konfirmasi pengajuan peminjaman online</p>
                        </div>
                    </div>
                    <button @click="modalPinjam = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('peminjaman.ajukan', $buku->id) }}" method="POST" class="mt-5 space-y-4">
                    @csrf

                    <!-- Ringkasan Buku -->
                    <div class="bg-indigo-50/70 p-3.5 rounded-2xl flex items-center space-x-3 border border-indigo-100/80">
                        <img src="{{ $buku->cover_url }}" alt="{{ $buku->judul }}" class="w-12 h-16 object-cover rounded-lg flex-shrink-0 shadow-sm">
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-slate-900 truncate">{{ $buku->judul }}</h4>
                            <p class="text-[11px] text-slate-500">Penulis: {{ $buku->penulis }}</p>
                            <span class="text-[10px] text-indigo-700 font-semibold">Lokasi: {{ $buku->lokasi_rak ?? 'Rak Perpustakaan' }}</span>
                        </div>
                    </div>

                    <!-- Pilihan Durasi -->
                    <div>
                        <label for="durasi_hari" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Durasi Peminjaman</label>
                        <select name="durasi_hari" id="durasi_hari" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="3">3 Hari</option>
                            <option value="7" selected>7 Hari (1 Minggu - Standar)</option>
                            <option value="10">10 Hari</option>
                            <option value="14">14 Hari (2 Minggu - Maksimal)</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Denda keterlambatan sebesar Rp 1.000 / hari jika melewati batas waktu.</p>
                    </div>

                    <!-- Catatan Peminjaman -->
                    <div>
                        <label for="catatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" id="catatan" rows="2" placeholder="Contoh: Untuk keperluan tugas sekolah/referensi makalah..."
                            class="w-full p-3 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="pt-2 flex items-center justify-end space-x-3">
                        <button type="button" @click="modalPinjam = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-200 transition flex items-center">
                            <span>Konfirmasi & Ajukan</span>
                            <i class="fa-solid fa-arrow-right ml-1.5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

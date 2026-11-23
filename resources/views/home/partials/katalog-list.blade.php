            <!-- Filter & Sort Bar -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $buku->firstItem() ?? 0 }}-{{ $buku->lastItem() ?? 0 }}</span> dari total <span class="font-bold text-slate-800">{{ $buku->total() }}</span> buku
                </p>

                <form action="{{ route('katalog') }}" method="GET" id="catalog-sort-form" class="flex items-center space-x-2 w-full sm:w-auto">
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

            @if($buku->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 mt-6">
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

                <div class="pt-6">
                    {{ $buku->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200/80 mt-6">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak ada buku ditemukan</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                        Coba periksa ejaan kata kunci atau gunakan filter kategori yang berbeda.
                    </p>
                    <button type="button" data-reset-catalog class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition">
                        Tampilkan Semua Buku
                    </button>
                </div>
            @endif

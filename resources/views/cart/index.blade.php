@extends('layouts.app')

@section('title', 'Keranjang Belanja Buku - PerpusDigital')

@section('content')
<div class="bg-indigo-900/5 border-b border-indigo-100 py-8">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md">
                <i class="fa-solid fa-cart-shopping text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Keranjang Belanja Buku</h1>
                <p class="text-sm text-slate-500 mt-0.5">Kelola buku pilihan Anda sebelum melanjutkan ke formulir pembayaran.</p>
            </div>
        </div>
    </div>
</div>

<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-10">
    @if($items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Items Table / List -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600">
                            Daftar Buku ({{ $totalItem }} Item)
                        </span>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 flex items-center transition">
                                <i class="fa-regular fa-trash-can mr-1.5"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($items as $item)
                            <div class="p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $item->buku->cover_url }}" alt="{{ $item->buku->judul }}" class="w-16 h-24 object-cover rounded-xl shadow-sm flex-shrink-0 bg-slate-100">
                                    <div>
                                        <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md inline-block mb-1 border border-indigo-100">
                                            {{ $item->buku->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                        <h3 class="font-bold text-slate-900 text-sm sm:text-base line-clamp-1 hover:text-indigo-600 transition">
                                            <a href="{{ route('buku.detail', $item->buku->slug) }}">{{ $item->buku->judul }}</a>
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-0.5">Penulis: {{ $item->buku->penulis }}</p>
                                        <div class="text-xs font-extrabold text-indigo-600 mt-1">
                                            {{ $item->buku->harga_format }} <span class="text-[11px] font-normal text-slate-400">/ buku</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto space-x-6">
                                    <!-- Quantity Controller (+ / -) -->
                                    <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 overflow-hidden shadow-sm">
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="minus">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition focus:outline-none">
                                                <i class="fa-solid fa-minus text-xs"></i>
                                            </button>
                                        </form>

                                        <span class="w-10 text-center font-bold text-sm text-slate-800">{{ $item->jumlah }}</span>

                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="plus">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition focus:outline-none">
                                                <i class="fa-solid fa-plus text-xs"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right min-w-[100px]">
                                        <p class="text-[10px] text-slate-400 font-medium">Subtotal</p>
                                        <p class="text-sm font-extrabold text-slate-900">{{ $item->subtotal_format }}</p>
                                    </div>

                                    <!-- Delete Item Button -->
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus dari keranjang" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <a href="{{ route('katalog') }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Lanjut Belanja Buku Lain
                    </a>
                </div>
            </div>

            <!-- Right Side: Order Summary / Checkout Action Box -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm sticky top-24">
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wider mb-5">Ringkasan Belanja</h2>

                    <div class="space-y-3 text-sm pb-5 border-b border-slate-100">
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Total Item</span>
                            <span class="font-bold text-slate-800">{{ $totalItem }} Eksemplar</span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Total Harga Buku</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Biaya Pengemasan</span>
                            <span class="font-bold text-emerald-600">GRATIS</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-5">
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Total Tagihan:</p>
                            <p class="text-2xl font-black text-indigo-600 mt-0.5">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-200 transition duration-150 flex items-center justify-center space-x-2">
                        <span>Lanjut ke Pembayaran</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <div class="mt-6 pt-6 border-t border-slate-100 text-[11px] text-slate-400 space-y-2">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-shield-check text-emerald-500"></i>
                            <span>Transaksi Aman & Terjamin</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-box text-indigo-500"></i>
                            <span>Buku Asli & Berkualitas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-20 h-20 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Keranjang Belanja Masih Kosong</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Anda belum menambahkan buku apa pun ke dalam keranjang belanja. Yuk, jelajahi ribuan buku pilihan kami sekarang!
            </p>
            <a href="{{ route('katalog') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-200 transition">
                <i class="fa-solid fa-book-open mr-2"></i> Jelajahi Katalog Buku
            </a>
        </div>
    @endif
</div>
@endsection

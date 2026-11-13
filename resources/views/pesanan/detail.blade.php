@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $pesanan->kode_pesanan . ' - PerpusDigital')

@section('content')
<div class="bg-indigo-900/5 border-b border-indigo-100 py-8">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center text-xs text-slate-500 mb-2 space-x-2">
                    <a href="{{ route('pesanan.index') }}" class="hover:text-indigo-600 transition">Pesanan Saya</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-800 font-semibold">{{ $pesanan->kode_pesanan }}</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Detail Pesanan Pembelian</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pesanan.cetak', $pesanan->id) }}" target="_blank" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-200 transition flex items-center">
                    <i class="fa-solid fa-print mr-2"></i> Cetak Struk / Invoice
                </a>
            </div>
        </div>
    </div>
</div>

<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Order Details & Purchased Books -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Order Status Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
                    <div>
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Nomor Invoice</span>
                        <p class="text-xl font-black text-slate-900 font-mono mt-0.5">{{ $pesanan->kode_pesanan }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-bold px-4 py-1.5 rounded-full border {{ $pesanan->status_badge_class }}">
                            {{ $pesanan->status_label }}
                        </span>
                    </div>
                </div>

                <div class="pt-6 grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block">Tanggal Transaksi</span>
                        <p class="font-bold text-slate-800 mt-1">{{ $pesanan->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Metode Pembayaran</span>
                        <p class="font-bold text-slate-800 mt-1">{{ $pesanan->metode_pembayaran }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Status Pengiriman</span>
                        <p class="font-bold text-indigo-600 mt-1">
                            @if($pesanan->status === 'menunggu_pembayaran')
                                Menunggu Verifikasi Pembayaran
                            @elseif($pesanan->status === 'diproses')
                                Paket Sedang Dikemas
                            @elseif($pesanan->status === 'dikirim')
                                Dalam Perjalanan Kurir
                            @elseif($pesanan->status === 'selesai')
                                Paket Telah Diterima
                            @else
                                Pesanan Dibatalkan
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Items Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                <h3 class="text-base font-bold text-slate-900 mb-6">Daftar Buku yang Dibeli</h3>
                <div class="divide-y divide-slate-100">
                    @foreach($pesanan->detail as $item)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between space-x-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->buku->cover_url }}" alt="{{ $item->buku->judul }}" class="w-14 h-20 object-cover rounded-xl shadow-sm flex-shrink-0 bg-slate-100">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 hover:text-indigo-600 transition">
                                        <a href="{{ route('buku.detail', $item->buku->slug) }}">{{ $item->buku->judul }}</a>
                                    </h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Penulis: {{ $item->buku->penulis }}</p>
                                    <p class="text-xs font-semibold text-slate-600 mt-1">
                                        {{ $item->jumlah }} eksemplar x {{ $item->harga_satuan_format }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-slate-900">{{ $item->subtotal_format }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Shipping Info & Payment Summary -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Delivery Info -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Informasi Pengiriman</h3>
                <div class="space-y-3 text-xs text-slate-600">
                    <div>
                        <span class="text-slate-400 block font-medium">Nama Penerima</span>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $pesanan->nama_penerima }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">No. Telepon / WhatsApp</span>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $pesanan->no_telepon }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-medium">Alamat Lengkap</span>
                        <p class="font-medium text-slate-800 mt-0.5 leading-relaxed">{{ $pesanan->alamat_pengiriman }}</p>
                    </div>
                    @if($pesanan->catatan)
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-slate-400 block font-medium">Catatan Pembeli</span>
                            <p class="text-slate-700 italic mt-0.5">"{{ $pesanan->catatan }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cost Summary -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Rincian Pembayaran</h3>
                <div class="space-y-2.5 text-xs text-slate-600 pb-4 border-b border-slate-100">
                    <div class="flex justify-between">
                        <span>Total Harga Buku</span>
                        <span class="font-bold text-slate-900">{{ $pesanan->total_harga_format }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkos Kirim</span>
                        <span class="font-bold text-emerald-600">GRATIS</span>
                    </div>
                </div>

                <div class="pt-4 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-800">Total Akhir:</span>
                    <span class="text-xl font-black text-indigo-600">{{ $pesanan->total_harga_format }}</span>
                </div>

                <div class="mt-6 space-y-3">
                    @if($pesanan->status === 'menunggu_pembayaran')
                        <!-- Petunjuk Payment at Delivery -->
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-950 space-y-2">
                            <div class="flex items-center space-x-2 font-bold text-amber-900">
                                <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                                <span>Verifikasi Payment at Delivery</span>
                            </div>
                            <p class="text-[11px] leading-relaxed text-amber-800">
                                Harap lakukan pembayaran dan konfirmasi kepada Admin sebelum buku dikirimkan.
                            </p>
                            <div class="pt-1 flex flex-col gap-2">
                                <a href="{{ route('contact') }}"
                                   class="w-full py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs text-center transition flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Konfirmasi via Formulir Pesan
                                </a>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('pesanan.cetak', $pesanan->id) }}" target="_blank" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Bukti Pembelian</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

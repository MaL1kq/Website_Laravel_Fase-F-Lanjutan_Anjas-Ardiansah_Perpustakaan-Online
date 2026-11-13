@extends('layouts.app')

@section('title', 'Riwayat Pembelian Buku - PerpusDigital')

@section('content')
<div class="bg-indigo-900/5 border-b border-indigo-100 py-8">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Riwayat Pesanan Buku</h1>
                <p class="text-sm text-slate-500 mt-0.5">Pantau status pembayaran, proses pengiriman, dan riwayat pesanan buku Anda.</p>
            </div>
        </div>
    </div>
</div>

<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-10">
    @if($pesanan->count() > 0)
        <div class="space-y-4">
            @foreach($pesanan as $item)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                        <div class="flex items-center space-x-3">
                            <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-lg">
                                {{ $item->kode_pesanan }}
                            </span>
                            <span class="text-xs text-slate-400">
                                <i class="fa-regular fa-clock mr-1"></i> {{ $item->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $item->status_badge_class }}">
                                {{ $item->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="py-4 space-y-3">
                        @foreach($item->detail as $detail)
                            <div class="flex items-center space-x-4">
                                <img src="{{ $detail->buku->cover_url }}" alt="{{ $detail->buku->judul }}" class="w-12 h-16 object-cover rounded-lg shadow-sm flex-shrink-0 bg-slate-100">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-slate-900 line-clamp-1">{{ $detail->buku->judul }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $detail->jumlah }} x {{ $detail->harga_satuan_format }}</p>
                                </div>
                                <div class="text-xs font-bold text-slate-800">
                                    {{ $detail->subtotal_format }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="text-xs text-slate-500">
                            Metode Pembayaran: <span class="font-bold text-slate-800">{{ $item->metode_pembayaran }}</span>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <span class="text-[11px] text-slate-400 block">Total Tagihan:</span>
                                <span class="text-base font-extrabold text-indigo-600">{{ $item->total_harga_format }}</span>
                            </div>
                            <a href="{{ route('pesanan.detail', $item->id) }}" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-600 font-bold text-xs rounded-xl transition shadow-sm">
                                Detail Pesanan
                            </a>
                            <a href="{{ route('pesanan.cetak', $item->id) }}" target="_blank" class="px-3.5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl transition shadow-sm" title="Cetak Struk Invoice">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="pt-4">
                {{ $pesanan->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto shadow-sm">
            <div class="w-20 h-20 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Belum Ada Transaksi Pembelian</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Anda belum pernah membeli buku di PerpusDigital. Temukan berbagai buku pilihan bermutu di katalog kami!
            </p>
            <a href="{{ route('katalog') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-200 transition">
                <i class="fa-solid fa-book-open mr-2"></i> Belanja Sekarang
            </a>
        </div>
    @endif
</div>
@endsection

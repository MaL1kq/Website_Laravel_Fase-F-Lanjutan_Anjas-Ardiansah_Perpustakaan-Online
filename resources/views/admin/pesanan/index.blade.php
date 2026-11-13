@extends('layouts.admin')

@section('title', 'Manajemen Pesanan Pembelian Buku - Panel Administrator')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Pesanan Pembelian Buku</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola transaksi penjualan buku, verifikasi pembayaran, dan pantau pengiriman paket.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.laporan.penjualan') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm flex items-center">
                <i class="fa-solid fa-chart-line mr-1.5 text-indigo-600"></i> Rekap Penjualan
            </a>
        </div>
    </div>

    <!-- Filter Tabs Status -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 text-xs font-semibold">
        <a href="{{ route('admin.pesanan.index') }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ !request('status') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'menunggu_pembayaran']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ request('status') === 'menunggu_pembayaran' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Menunggu Bayar ({{ $countMenunggu }})
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'diproses']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ request('status') === 'diproses' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Diproses ({{ $countDiproses }})
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'dikirim']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ request('status') === 'dikirim' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Dikirim ({{ $countDikirim }})
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'selesai']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ request('status') === 'selesai' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Selesai ({{ $countSelesai }})
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="w-full sm:w-80">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari invoice, nama, telp..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>
        </form>
        <span class="text-xs text-slate-500">
            Menampilkan <span class="font-bold text-slate-800">{{ $pesanan->total() }}</span> total pesanan
        </span>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Invoice & Tanggal</th>
                        <th class="px-5 py-3.5">Pembeli / Penerima</th>
                        <th class="px-5 py-3.5">Item Buku</th>
                        <th class="px-5 py-3.5">Total Biaya</th>
                        <th class="px-5 py-3.5">Metode Bayar</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesanan as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-900 block">{{ $item->kode_pesanan }}</span>
                                <span class="text-[11px] text-slate-400 mt-0.5 block">{{ $item->created_at->isoFormat('D MMM Y, HH:mm') }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900 block">{{ $item->nama_penerima }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $item->no_telepon }}</span>
                                <span class="text-[11px] text-slate-500 truncate max-w-xs block">{{ $item->alamat_pengiriman }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800">{{ $item->detail->sum('jumlah') }} Buku</span>
                                <p class="text-[11px] text-slate-400 truncate max-w-[200px]">
                                    {{ $item->detail->pluck('buku.judul')->join(', ') }}
                                </p>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-bold font-mono text-indigo-600 text-sm">
                                {{ $item->total_harga_format }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-xs font-medium text-slate-700">
                                {{ $item->metode_pembayaran }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $item->status_badge_class }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('admin.pesanan.show', $item->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-600 font-bold text-[11px] transition shadow-sm">
                                    Kelola Pesanan
                                </a>
                                <a href="{{ route('admin.pesanan.cetak', $item->id) }}" target="_blank" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 text-xs transition" title="Cetak Invoice">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                                Tidak ada data pesanan pembelian ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pesanan->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $pesanan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

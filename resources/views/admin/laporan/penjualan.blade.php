@extends('layouts.admin')

@section('title', 'Laporan Penjualan Buku - Panel Administrator')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Laporan Penjualan Buku</h1>
            <p class="text-xs text-slate-500 mt-1">Rekapitulasi omset pendapatan dan statistik buku terjual berdasarkan periode tanggal.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.laporan.penjualan.cetak', request()->all()) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center">
                <i class="fa-solid fa-print mr-1.5"></i> Cetak Laporan Penjualan
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm flex items-center">
                <i class="fa-solid fa-book-bookmark mr-1.5 text-indigo-600"></i> Laporan Peminjaman
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label for="tgl_mulai" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Dari Tanggal</label>
                <input type="date" name="tgl_mulai" id="tgl_mulai" value="{{ $tglMulai }}"
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="tgl_selesai" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" id="tgl_selesai" value="{{ $tglSelesai }}"
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Status Pesanan</label>
                <select name="status" id="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu_pembayaran" {{ $status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="dikirim" {{ $status == 'dikirim' ? 'selected' : '' }}>Dalam Pengiriman</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition flex items-center justify-center">
                    <i class="fa-solid fa-filter mr-1.5"></i> Filter
                </button>
                <a href="{{ route('admin.laporan.penjualan') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Omset Penjualan</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">Rp {{ number_format($totalOmset, 0, ',', '.') }}</h3>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Periode terpilih</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Buku Terjual</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $totalBukuTerjual }} <span class="text-xs font-normal text-slate-400">Eksemplar</span></h3>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Dari pesanan valid</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalTransaksi }} <span class="text-xs font-normal text-slate-400">Pesanan</span></h3>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Seluruh order masuk</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">Invoice</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Pembeli</th>
                        <th class="px-5 py-3.5">Item Buku</th>
                        <th class="px-5 py-3.5">Metode</th>
                        <th class="px-5 py-3.5 text-right">Total Transaksi</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesanan as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-slate-900 whitespace-nowrap">{{ $item->kode_pesanan }}</td>
                            <td class="px-5 py-3.5 whitespace-nowrap">{{ $item->created_at->isoFormat('D MMM Y, HH:mm') }}</td>
                            <td class="px-5 py-3.5 font-medium text-slate-800">{{ $item->nama_penerima }}</td>
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-slate-800">{{ $item->detail->sum('jumlah') }} Buku:</span>
                                <span class="text-slate-500">{{ $item->detail->pluck('buku.judul')->join(', ') }}</span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">{{ $item->metode_pembayaran }}</td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-right font-mono font-bold text-indigo-600">{{ $item->total_harga_format }}</td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-center">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $item->status_badge_class }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                Tidak ada data transaksi penjualan pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

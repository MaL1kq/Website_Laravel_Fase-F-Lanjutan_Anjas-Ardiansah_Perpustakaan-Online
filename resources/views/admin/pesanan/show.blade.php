@extends('layouts.admin')

@section('title', 'Kelola Pesanan ' . $pesanan->kode_pesanan . ' - Panel Administrator')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center text-xs text-slate-500 mb-1 space-x-2">
                <a href="{{ route('admin.pesanan.index') }}" class="hover:text-indigo-600 transition">Pesanan Buku</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-800 font-semibold">{{ $pesanan->kode_pesanan }}</span>
            </nav>
            <h1 class="text-2xl font-black text-slate-900">Kelola Pesanan Buku</h1>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.pesanan.cetak', $pesanan->id) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm flex items-center">
                <i class="fa-solid fa-print mr-1.5 text-indigo-600"></i> Cetak Invoice
            </a>
            <a href="{{ route('admin.pesanan.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left: Order Details & Update Status -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Status & Payment Update Box -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center">
                    <i class="fa-solid fa-sliders text-indigo-600 mr-2"></i> Pembaruan Status Transaksi & Pembayaran
                </h2>
                <form action="{{ route('admin.pesanan.status', $pesanan->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                <i class="fa-solid fa-box mr-1 text-slate-400"></i> Status Pesanan
                            </label>
                            <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-slate-50">
                                <option value="menunggu_pembayaran" {{ $pesanan->status === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="diproses" {{ $pesanan->status === 'diproses' ? 'selected' : '' }}>Sedang Diproses (Kemas Buku)</option>
                                <option value="dikirim" {{ $pesanan->status === 'dikirim' ? 'selected' : '' }}>Dalam Pengiriman Kurir</option>
                                <option value="selesai" {{ $pesanan->status === 'selesai' ? 'selected' : '' }}>Selesai (Pesanan Diterima)</option>
                                <option value="dibatalkan" {{ $pesanan->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan (Kembalikan Stok)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                <i class="fa-solid fa-money-bill-wave mr-1 text-emerald-500"></i> Status Penerimaan Pembayaran
                            </label>
                            <select name="status_pembayaran" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                                <option value="belum_dibayar" {{ $pesanan->status_pembayaran === 'belum_dibayar' ? 'selected' : '' }}>
                                    ❌ Belum Terima Pembayaran (Belum Lunas)
                                </option>
                                <option value="sudah_dibayar" {{ $pesanan->status_pembayaran === 'sudah_dibayar' ? 'selected' : '' }}>
                                    ✅ Sudah Terima Pembayaran (Lunas)
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-200 transition flex items-center justify-center">
                            <i class="fa-solid fa-check mr-2"></i> Simpan Perubahan Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Rincian Item Buku Dibeli</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-slate-600">
                        <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">Buku</th>
                                <th class="py-3 px-4">Harga Satuan</th>
                                <th class="py-3 px-4 text-center">Jumlah</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pesanan->detail as $item)
                                <tr>
                                    <td class="py-3.5 px-4 flex items-center space-x-3">
                                        <img src="{{ $item->buku->cover_url }}" alt="{{ $item->buku->judul }}" class="w-12 h-16 object-cover rounded-lg shadow-sm flex-shrink-0 bg-slate-100">
                                        <div>
                                            <p class="font-bold text-slate-900 text-xs">{{ $item->buku->judul }}</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Penulis: {{ $item->buku->penulis }}</p>
                                            <p class="text-[11px] text-slate-400">Penerbit: {{ $item->buku->penerbit }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-medium text-slate-700">{{ $item->harga_satuan_format }}</td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $item->jumlah }}</td>
                                    <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">{{ $item->subtotal_format }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-slate-200 bg-slate-50 font-bold text-slate-800 text-xs">
                            <tr>
                                <td colspan="3" class="py-3.5 px-4 text-right">Total Transaksi:</td>
                                <td class="py-3.5 px-4 text-right text-indigo-600 font-mono text-sm">{{ $pesanan->total_harga_format }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Buyer Information & Payment -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Informasi Pembeli</h3>
                <div class="space-y-3 text-xs text-slate-600">
                    <div>
                        <span class="text-slate-400 block font-medium">Akun Pengguna</span>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $pesanan->user->name }} ({{ $pesanan->user->username }})</p>
                        <p class="text-slate-500">{{ $pesanan->user->email }}</p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block font-medium">Penerima Paket</span>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $pesanan->nama_penerima }}</p>
                        <p class="text-slate-700 font-medium">Telp/WA: {{ $pesanan->no_telepon }}</p>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block font-medium">Alamat Lengkap</span>
                        <p class="text-slate-800 mt-0.5 leading-relaxed">{{ $pesanan->alamat_pengiriman }}</p>
                    </div>
                    @if($pesanan->catatan)
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-slate-400 block font-medium">Catatan Pembeli</span>
                            <p class="text-slate-700 italic mt-0.5">"{{ $pesanan->catatan }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">Metode & Status</h3>
                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Metode Pembayaran</span>
                        <p class="font-bold text-indigo-700 mt-0.5">{{ $pesanan->metode_pembayaran }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block font-medium">Status Pembayaran</span>
                        @if($pesanan->status_pembayaran === 'sudah_dibayar')
                            <span class="inline-flex items-center mt-1 px-3 py-1 rounded-full border text-xs font-bold bg-emerald-100 text-emerald-800 border-emerald-200">
                                <i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i> Sudah Diterima (Lunas)
                            </span>
                            @if($pesanan->tanggal_pembayaran)
                                <p class="text-[11px] text-slate-400 mt-1">Diverifikasi: {{ $pesanan->tanggal_pembayaran->translatedFormat('d M Y, H:i') }}</p>
                            @endif
                        @else
                            <span class="inline-flex items-center mt-1 px-3 py-1 rounded-full border text-xs font-bold bg-rose-100 text-rose-800 border-rose-200">
                                <i class="fa-solid fa-clock mr-1.5 text-rose-600"></i> Belum Diterima (Menunggu Pembayaran)
                            </span>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block font-medium">Status Pengiriman Pesanan</span>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full border text-xs font-bold {{ $pesanan->status_badge_class }}">
                            {{ $pesanan->status_label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

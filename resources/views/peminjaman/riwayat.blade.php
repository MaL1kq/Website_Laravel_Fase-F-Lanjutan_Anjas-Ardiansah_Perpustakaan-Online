@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Buku - PerpusDigital')

@section('content')
<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-10">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Riwayat Peminjaman Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau status pengajuan, tenggat pengembalian, dan riwayat buku yang Anda pinjam.</p>
        </div>
        <a href="{{ route('katalog') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-sm transition">
            <i class="fa-solid fa-plus mr-1.5"></i> Pinjam Buku Lain
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 mb-6 text-xs font-semibold">
        <a href="{{ route('peminjaman.riwayat') }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ !$statusFilter ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ Auth::user()->peminjaman()->count() }})
        </a>
        <a href="{{ route('peminjaman.riwayat', ['status' => 'menunggu']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ $statusFilter === 'menunggu' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Menunggu Verifikasi
        </a>
        <a href="{{ route('peminjaman.riwayat', ['status' => 'dipinjam']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ $statusFilter === 'dipinjam' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Sedang Dipinjam
        </a>
        <a href="{{ route('peminjaman.riwayat', ['status' => 'terlambat']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ $statusFilter === 'terlambat' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Terlambat / Denda
        </a>
        <a href="{{ route('peminjaman.riwayat', ['status' => 'dikembalikan']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ $statusFilter === 'dikembalikan' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Sudah Dikembalikan
        </a>
    </div>

    <!-- Transaction List -->
    @if($peminjaman->count() > 0)
        <div class="space-y-4">
            @foreach($peminjaman as $item)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <!-- Left: Book cover & detail -->
                    <div class="flex items-start space-x-4">
                        <img src="{{ $item->buku->cover_url }}" alt="{{ $item->buku->judul }}" class="w-20 h-28 object-cover rounded-xl shadow-sm flex-shrink-0 bg-slate-100">
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                    {{ $item->kode_transaksi }}
                                </span>
                                @if($item->status_peminjaman === 'menunggu')
                                    <span class="text-[11px] font-bold bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full flex items-center">
                                        <i class="fa-regular fa-clock mr-1"></i> Menunggu Konfirmasi
                                    </span>
                                @elseif($item->status_peminjaman === 'dipinjam')
                                    <span class="text-[11px] font-bold bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full flex items-center">
                                        <i class="fa-solid fa-book-reader mr-1"></i> Sedang Dipinjam
                                    </span>
                                @elseif($item->status_peminjaman === 'terlambat')
                                    <span class="text-[11px] font-bold bg-rose-100 text-rose-800 px-2.5 py-0.5 rounded-full flex items-center">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Terlambat Kembali
                                    </span>
                                @elseif($item->status_peminjaman === 'dikembalikan')
                                    <span class="text-[11px] font-bold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full flex items-center">
                                        <i class="fa-solid fa-circle-check mr-1"></i> Telah Dikembalikan
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full">
                                        {{ ucfirst($item->status_peminjaman) }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-bold text-slate-900 text-base hover:text-indigo-600 transition">
                                <a href="{{ route('buku.detail', $item->buku->slug) }}">{{ $item->buku->judul }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">Penulis: {{ $item->buku->penulis }} | Rak: {{ $item->buku->lokasi_rak ?? '-' }}</p>

                            <!-- Date Information -->
                            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600">
                                <div>
                                    <span class="text-slate-400">Tgl Pinjam:</span>
                                    <span class="font-semibold text-slate-800">{{ Carbon\Carbon::parse($item->tanggal_peminjaman)->isoFormat('D MMM Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400">Batas Kembali:</span>
                                    <span class="font-semibold {{ $item->status_peminjaman === 'terlambat' ? 'text-rose-600 font-bold' : 'text-slate-800' }}">
                                        {{ Carbon\Carbon::parse($item->tanggal_pengembalian)->isoFormat('D MMM Y') }}
                                    </span>
                                </div>
                                @if($item->tanggal_dikembalikan)
                                    <div>
                                        <span class="text-slate-400">Dikembalikan pada:</span>
                                        <span class="font-semibold text-emerald-700">{{ Carbon\Carbon::parse($item->tanggal_dikembalikan)->isoFormat('D MMM Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($item->denda > 0)
                                <div class="mt-2 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 px-3 py-1 rounded-lg inline-block">
                                    <i class="fa-solid fa-money-bill-wave mr-1"></i> Denda Keterlambatan: Rp {{ number_format($item->denda, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-2 pt-4 md:pt-0 border-t md:border-t-0 border-slate-100">
                        <a href="{{ route('peminjaman.cetak', $item->id) }}" target="_blank"
                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition flex items-center shadow-sm">
                            <i class="fa-solid fa-print mr-1.5 text-indigo-600"></i> Cetak Bukti Pinjam
                        </a>
                        <a href="{{ route('buku.detail', $item->buku->slug) }}"
                            class="px-4 py-2 rounded-xl text-indigo-600 hover:bg-indigo-50 text-xs font-semibold transition flex items-center">
                            <span>Lihat Buku</span>
                            <i class="fa-solid fa-chevron-right ml-1 text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="pt-4">
                {{ $peminjaman->links() }}
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Belum ada data peminjaman</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                Anda belum pernah meminjam buku atau tidak ada transaksi dengan status ini.
            </p>
            <a href="{{ route('katalog') }}" class="inline-block mt-5 px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                Mulai Pinjam Buku
            </a>
        </div>
    @endif
</div>
@endsection

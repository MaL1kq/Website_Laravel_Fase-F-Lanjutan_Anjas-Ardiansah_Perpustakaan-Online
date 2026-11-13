@extends('layouts.admin')

@section('title', 'Dashboard - Panel Administrator')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Dashboard Perpustakaan & Toko Buku</h1>
            <p class="text-xs text-slate-500 mt-1">
                Ringkasan inventaris buku, peminjaman siswa, dan transaksi penjualan buku terkini.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.buku.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center">
                <i class="fa-solid fa-plus mr-1.5"></i> Tambah Buku
            </a>
            <a href="{{ route('admin.pesanan.index') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-200 transition flex items-center">
                <i class="fa-solid fa-receipt mr-1.5"></i> Pesanan Masuk
                @if($pesananMenunggu > 0)
                    <span class="ml-1.5 bg-white text-emerald-700 text-[10px] font-black px-1.5 py-0.2 rounded-full">{{ $pesananMenunggu }}</span>
                @endif
            </a>
            <a href="{{ route('admin.pesan.index') }}" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md shadow-purple-200 transition flex items-center">
                <i class="fa-solid fa-comments mr-1.5"></i> Pesan Masuk
                @if($totalPesanBaru > 0)
                    <span class="ml-1.5 bg-white text-purple-700 text-[10px] font-black px-1.5 py-0.2 rounded-full animate-pulse">{{ $totalPesanBaru }}</span>
                @endif
            </a>
            <a href="{{ route('admin.laporan.penjualan') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm flex items-center">
                <i class="fa-solid fa-chart-line mr-1.5 text-indigo-600"></i> Laporan Toko
            </a>
        </div>
    </div>

    <!-- Metric Widgets Grid 1: Perpustakaan & Inventaris -->
    <div>
        <div class="flex items-center space-x-2 mb-3">
            <i class="fa-solid fa-book-open-reader text-indigo-600 text-sm"></i>
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Statistik Sirkulasi Perpustakaan</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Buku -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Judul Buku</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalBuku }} <span class="text-xs font-normal text-slate-400">Judul</span></h3>
                    <span class="text-[11px] text-indigo-600 font-semibold mt-1 inline-block">{{ $totalKategori }} Kategori terdaftar</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>

            <!-- Anggota Peminjam -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Anggota Siswa</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalAnggota }} <span class="text-xs font-normal text-slate-400">Orang</span></h3>
                    <span class="text-[11px] text-slate-500 mt-1 inline-block font-medium">{{ $totalAdmin }} Administrator</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <!-- Peminjaman Aktif -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peminjaman Aktif</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $pinjamAktif }} <span class="text-xs font-normal text-slate-400">Buku</span></h3>
                    @if($pinjamMenunggu > 0)
                        <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full inline-block mt-1">
                            {{ $pinjamMenunggu }} Perlu Dikonfirmasi
                        </span>
                    @else
                        <span class="text-[11px] text-slate-400 mt-1 inline-block">Semua telah diverifikasi</span>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                </div>
            </div>

            <!-- Terlambat & Denda -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Denda Terkumpul</p>
                    <h3 class="text-2xl font-black text-rose-600 mt-1">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                    <span class="text-[11px] font-semibold text-rose-500 mt-1 inline-block">
                        {{ $pinjamTerlambat }} Buku terlambat
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Metric Widgets Grid 2: Penjualan Toko Buku -->
    <div>
        <div class="flex items-center space-x-2 mb-3">
            <i class="fa-solid fa-cash-register text-emerald-600 text-sm"></i>
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Statistik Penjualan Toko Buku</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Penjualan -->
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-emerald-100 uppercase tracking-wider">Pendapatan Toko</p>
                    <h3 class="text-2xl font-black mt-1">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                    <span class="text-[11px] text-emerald-100 mt-1 inline-block">Total omset bersih</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-xl">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>

            <!-- Total Transaksi Pesanan -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pesanan</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalPesanan }} <span class="text-xs font-normal text-slate-400">Transaksi</span></h3>
                    <span class="text-[11px] text-slate-400 mt-1 inline-block">Semua order tercatat</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-shopping-bag"></i>
                </div>
            </div>

            <!-- Pesanan Menunggu Pembayaran/Proses -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Tindakan</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $pesananMenunggu }} <span class="text-xs font-normal text-slate-400">Pesanan</span></h3>
                    <span class="text-[11px] {{ $pesananMenunggu > 0 ? 'text-amber-600 font-bold' : 'text-slate-400' }} mt-1 inline-block">
                        {{ $pesananMenunggu > 0 ? 'Perlu dicek / diproses' : 'Semua beres' }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>

            <!-- Pengembalian Buku Peminjaman Selesai -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peminjaman Tuntas</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $pinjamSelesai }} <span class="text-xs font-normal text-slate-400">Buku</span></h3>
                    <span class="text-[11px] text-emerald-600 font-semibold mt-1 inline-block">Sudah dikembalikan</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Recent Orders & Recent Loans Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Col 1 & 2: Recent Orders & Loans (2 Cols) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Table 1: Pesanan Toko Buku Terbaru -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <h2 class="text-base font-bold text-slate-900">Pesanan Toko Buku Terbaru</h2>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola verifikasi pembayaran dan pengiriman buku anggota</p>
                    </div>
                    <a href="{{ route('admin.pesanan.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition">
                        Lihat Semua Pesanan &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px]">
                            <tr>
                                <th class="p-3 rounded-l-xl">No. Invoice</th>
                                <th class="p-3">Pembeli</th>
                                <th class="p-3">Item Buku</th>
                                <th class="p-3">Total Tagihan</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pesananTerbaru as $order)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3">
                                        <span class="font-mono font-bold text-slate-800 block">{{ $order->no_pesanan }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="p-3">
                                        <p class="font-bold text-slate-900">{{ $order->nama_penerima }}</p>
                                        <span class="text-[10px] text-slate-400">{{ $order->user->email ?? '-' }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-semibold text-slate-700">{{ $order->detail->count() }} Judul</span>
                                        <span class="text-[10px] text-slate-400 block">({{ $order->detail->sum('jumlah') }} pcs)</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-black text-emerald-700">{{ $order->total_harga_format }}</span>
                                        <span class="text-[10px] text-slate-400 block uppercase">{{ $order->metode_pembayaran }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $order->status_badge }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <a href="{{ route('admin.pesanan.show', $order->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-lg transition inline-flex items-center">
                                            Kelola <i class="fa-solid fa-chevron-right ml-1 text-[9px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-400">Belum ada pesanan buku masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 2: Transaksi Peminjaman Terbaru -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            <h2 class="text-base font-bold text-slate-900">Transaksi Peminjaman Terbaru</h2>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Pantau dan verifikasi pengajuan pinjaman anggota</p>
                    </div>
                    <a href="{{ route('admin.transaksi.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        Lihat Semua Peminjaman &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px]">
                            <tr>
                                <th class="p-3 rounded-l-xl">Kode / Tanggal</th>
                                <th class="p-3">Peminjam</th>
                                <th class="p-3">Buku</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transaksiTerbaru as $trx)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3">
                                        <span class="font-mono font-bold text-slate-700 block">{{ $trx->kode_transaksi }}</span>
                                        <span class="text-[10px] text-slate-400">{{ Carbon\Carbon::parse($trx->tanggal_peminjaman)->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="p-3">
                                        <p class="font-bold text-slate-900">{{ $trx->user->name }}</p>
                                        <span class="text-[10px] text-slate-400">{{ $trx->user->email }}</span>
                                    </td>
                                    <td class="p-3 max-w-[200px]">
                                        <p class="font-semibold text-slate-800 truncate">{{ $trx->buku->judul }}</p>
                                        <span class="text-[10px] text-slate-400">Rak: {{ $trx->buku->lokasi_rak ?? '-' }}</span>
                                    </td>
                                    <td class="p-3">
                                        @if($trx->status_peminjaman === 'menunggu')
                                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Menunggu</span>
                                        @elseif($trx->status_peminjaman === 'dipinjam')
                                            <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Dipinjam</span>
                                        @elseif($trx->status_peminjaman === 'terlambat')
                                            <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Terlambat</span>
                                        @elseif($trx->status_peminjaman === 'dikembalikan')
                                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">Selesai</span>
                                        @else
                                            <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $trx->status_peminjaman }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right">
                                        @if($trx->status_peminjaman === 'menunggu')
                                            <form action="{{ route('admin.transaksi.konfirmasi', $trx->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold shadow-sm">
                                                    Setujui
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.transaksi.index', ['q' => $trx->kode_transaksi]) }}" class="text-indigo-600 hover:underline font-bold text-[11px]">
                                                Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-400">Belum ada transaksi peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Col 3: Side Widget - Low Stock Books & Shortcuts (1 Col) -->
        <div class="space-y-6">
            <!-- Stok Buku Menipis -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Stok Buku Menipis</h2>
                        <p class="text-xs text-slate-400">Perlu restok fisik buku</p>
                    </div>
                    <span class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </span>
                </div>

                @if($bukuStokMenipis->count() > 0)
                    <div class="space-y-3">
                        @foreach($bukuStokMenipis as $sbuku)
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <div class="min-w-0 flex-1 pr-3">
                                    <h4 class="font-bold text-xs text-slate-800 truncate">{{ $sbuku->judul }}</h4>
                                    <span class="text-[10px] text-slate-400">Harga: {{ $sbuku->harga_format }}</span>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-xs font-black {{ $sbuku->stok == 0 ? 'text-rose-600 bg-rose-50 border border-rose-200' : 'text-amber-600 bg-amber-50 border border-amber-200' }} px-2 py-0.5 rounded-lg">
                                        Sisa {{ $sbuku->stok }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400">
                        <i class="fa-solid fa-circle-check text-2xl text-emerald-500 mb-2"></i>
                        <p class="text-xs">Semua stok buku dalam jumlah aman!</p>
                    </div>
                @endif

                <a href="{{ route('admin.buku.index') }}" class="w-full mt-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition flex items-center justify-center">
                    <span>Kelola Seluruh Buku</span>
                    <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
                </a>
            </div>

            <!-- Pesan Pengguna Masuk (Contact to Admin) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Pesan Pengguna (Contact)</h2>
                        <p class="text-xs text-slate-400">Pertanyaan & konfirmasi user</p>
                    </div>
                    <span class="w-7 h-7 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                </div>

                @if($pesanTerbaru->count() > 0)
                    <div class="space-y-3">
                        @foreach($pesanTerbaru as $psn)
                            <a href="{{ route('admin.pesan.show', $psn->id) }}" class="block p-3 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-100 transition">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-xs text-slate-900 truncate">{{ $psn->nama }}</h4>
                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-full border {{ $psn->status_badge }}">{{ $psn->status_label }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500 line-clamp-1">{{ $psn->subjek }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-slate-400 text-xs">
                        Belum ada pesan masuk dari pengguna.
                    </div>
                @endif

                <a href="{{ route('admin.pesan.index') }}" class="w-full mt-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition flex items-center justify-center">
                    <span>Lihat Semua Pesan ({{ $totalPesanBaru }} Baru)</span>
                    <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
                </a>
            </div>

            <!-- Quick Action Card -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-sm">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-indigo-300">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">Sistem 2 Peran Aktif</h3>
                        <p class="text-[11px] text-indigo-200">Administrator & Anggota Siswa</p>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed mb-4">
                    Semua wewenang sirkulasi peminjaman, pengelolaan buku, dan transaksi penjualan buku berada di bawah pengawasan Administrator.
                </p>
                <div class="grid grid-cols-2 gap-2 text-center text-xs">
                    <a href="{{ route('admin.users.index') }}" class="p-2.5 bg-white/10 hover:bg-white/20 rounded-xl font-bold transition">
                        Kelola Akun
                    </a>
                    <a href="{{ route('admin.laporan.penjualan') }}" class="p-2.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl font-bold transition">
                        Cetak Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


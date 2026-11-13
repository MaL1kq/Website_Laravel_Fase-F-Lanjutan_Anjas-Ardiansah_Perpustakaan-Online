@extends('layouts.admin')

@section('title', 'Sirkulasi Peminjaman Buku - PerpusDigital')

@section('content')
<div class="space-y-6" x-data="{ modalKembalikan: false, modalTolak: false, selectedTrx: null }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Sirkulasi & Peminjaman</h1>
            <p class="text-xs text-slate-500 mt-1">Konfirmasi permohonan pinjam, proses pengembalian buku, dan kelola denda keterlambatan.</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-file-invoice mr-1.5 text-indigo-600"></i> Rekap Laporan
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Status Filter Tabs -->
        <div class="flex items-center space-x-1.5 overflow-x-auto w-full md:w-auto pb-1 text-xs font-semibold">
            <a href="{{ route('admin.transaksi.index') }}" class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'menunggu']) }}" class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ request('status') === 'menunggu' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Menunggu
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'dipinjam']) }}" class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ request('status') === 'dipinjam' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Dipinjam
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'terlambat']) }}" class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ request('status') === 'terlambat' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Terlambat
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'dikembalikan']) }}" class="px-3 py-1.5 rounded-xl transition whitespace-nowrap {{ request('status') === 'dikembalikan' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Selesai
            </a>
        </div>

        <!-- Search input -->
        <form action="{{ route('admin.transaksi.index') }}" method="GET" class="w-full md:w-72">
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, judul..."
                    class="w-full pl-9 pr-4 py-1.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2 text-slate-400 text-xs"></i>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="p-4">Kode Transaksi</th>
                        <th class="p-4">Peminjam (Siswa)</th>
                        <th class="p-4">Buku Dipinjam</th>
                        <th class="p-4">Tgl Pinjam / Batas</th>
                        <th class="p-4">Status & Denda</th>
                        <th class="p-4 text-right">Aksi Sirkulasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksi as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4">
                                <span class="font-mono font-bold text-slate-900 block text-xs">{{ $item->kode_transaksi }}</span>
                                <span class="text-[10px] text-slate-400">ID: #{{ $item->id }}</span>
                            </td>
                            <td class="p-4">
                                <h4 class="font-bold text-slate-900">{{ $item->user->name }}</h4>
                                <span class="text-[10px] text-slate-400">{{ $item->user->email }}</span>
                                @if($item->user->no_telepon)
                                    <p class="text-[10px] text-indigo-600 font-semibold">{{ $item->user->no_telepon }}</p>
                                @endif
                            </td>
                            <td class="p-4 max-w-xs">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ $item->buku->cover_url }}" alt="{{ $item->buku->judul }}" class="w-8 h-11 object-cover rounded shadow-sm bg-slate-100 flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ $item->buku->judul }}</p>
                                        <span class="text-[10px] text-slate-400">Rak: {{ $item->buku->lokasi_rak ?? '-' }} | Sisa Stok: {{ $item->buku->stok }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="text-slate-700">Pinjam: <strong>{{ Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') }}</strong></p>
                                <p class="text-[11px] {{ in_array($item->status_peminjaman, ['terlambat']) ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                                    Batas: {{ Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y') }}
                                </p>
                                @if($item->tanggal_dikembalikan)
                                    <p class="text-[10px] text-emerald-600 font-semibold">Kembali: {{ Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d/m/Y') }}</p>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($item->status_peminjaman === 'menunggu')
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-block">Menunggu</span>
                                @elseif($item->status_peminjaman === 'dipinjam')
                                    <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-block">Dipinjam</span>
                                @elseif($item->status_peminjaman === 'terlambat')
                                    <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-block">Terlambat</span>
                                    <p class="text-[11px] font-bold text-rose-600 mt-1">Denda: Rp {{ number_format($item->denda, 0, ',', '.') }}</p>
                                @elseif($item->status_peminjaman === 'dikembalikan')
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-block">Dikembalikan</span>
                                    @if($item->denda > 0)
                                        <span class="text-[10px] text-rose-600 block">Denda: Rp {{ number_format($item->denda, 0, ',', '.') }}</span>
                                    @endif
                                @else
                                    <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-block">{{ ucfirst($item->status_peminjaman) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                @if($item->status_peminjaman === 'menunggu')
                                    <!-- Konfirmasi Peminjaman -->
                                    <form action="{{ route('admin.transaksi.konfirmasi', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Konfirmasi dan setujui peminjaman ini?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[11px] font-bold shadow-sm transition">
                                            <i class="fa-solid fa-check mr-1"></i> Setujui
                                        </button>
                                    </form>

                                    <!-- Tolak Peminjaman -->
                                    <button type="button" @click="selectedTrx = {{ json_encode($item) }}; modalTolak = true"
                                        class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-[11px] font-bold transition">
                                        <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                    </button>
                                @elseif(in_array($item->status_peminjaman, ['dipinjam', 'terlambat']))
                                    <!-- Proses Pengembalian Buku -->
                                    <button type="button" @click="selectedTrx = {{ json_encode($item) }}; modalKembalikan = true"
                                        class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[11px] font-bold shadow-sm transition">
                                        <i class="fa-solid fa-arrow-rotate-left mr-1"></i> Proses Kembali
                                    </button>
                                @endif

                                <!-- Cetak Bukti -->
                                <a href="{{ route('peminjaman.cetak', $item->id) }}" target="_blank" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition inline-block" title="Cetak Bukti">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Tidak ada transaksi yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $transaksi->links() }}
        </div>
    </div>

    <!-- Modal Proses Pengembalian Buku -->
    <div x-show="modalKembalikan" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="modalKembalikan = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" x-show="selectedTrx">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-900 text-sm">Konfirmasi Pengembalian Buku</h3>
                    <button @click="modalKembalikan = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <template x-if="selectedTrx">
                    <form :action="'{{ url('admin/transaksi') }}/' + selectedTrx.id + '/kembalikan'" method="POST" class="space-y-4">
                        @csrf
                        <div class="bg-slate-50 p-3.5 rounded-2xl text-xs space-y-1">
                            <p><span class="text-slate-400">Kode:</span> <strong x-text="selectedTrx.kode_transaksi"></strong></p>
                            <p><span class="text-slate-400">Peminjam:</span> <strong x-text="selectedTrx.user.name"></strong></p>
                            <p><span class="text-slate-400">Buku:</span> <strong x-text="selectedTrx.buku.judul"></strong></p>
                            <p><span class="text-slate-400">Batas Kembali:</span> <span x-text="selectedTrx.tanggal_pengembalian"></span></p>
                        </div>

                        <template x-if="selectedTrx.denda > 0">
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800">
                                <p class="font-bold"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Terdapat Denda Keterlambatan:</p>
                                <p class="text-sm font-extrabold mt-1">Rp <span x-text="new Intl.NumberFormat('id-ID').format(selectedTrx.denda)"></span></p>
                                <p class="text-[10px] text-rose-600 mt-0.5">Pastikan anggota telah melunasi denda sebelum mengembalikan stok.</p>
                            </div>
                        </template>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Catatan Pengembalian (Opsional)</label>
                            <textarea name="catatan" rows="2" placeholder="Kondisi buku baik, denda telah dibayar..."
                                class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-2 pt-2">
                            <button type="button" @click="modalKembalikan = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 shadow-sm">
                                <i class="fa-solid fa-check mr-1"></i> Konfirmasi Selesai
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Peminjaman -->
    <div x-show="modalTolak" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="modalTolak = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" x-show="selectedTrx">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-900 text-sm">Tolak Pengajuan Peminjaman</h3>
                    <button @click="modalTolak = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <template x-if="selectedTrx">
                    <form :action="'{{ url('admin/transaksi') }}/' + selectedTrx.id + '/tolak'" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Alasan Penolakan</label>
                            <textarea name="catatan" rows="3" required placeholder="Contoh: Stok fisik sedang dalam perbaikan, anggota memiliki tanggungan denda..."
                                class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-2 pt-2">
                            <button type="button" @click="modalTolak = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700 shadow-sm">
                                Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

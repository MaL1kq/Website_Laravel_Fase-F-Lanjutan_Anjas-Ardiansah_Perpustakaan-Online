@extends('layouts.admin')

@section('title', 'Laporan Peminjaman Perpustakaan - PerpusDigital')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Laporan Sirkulasi & Peminjaman</h1>
            <p class="text-xs text-slate-500 mt-1">Rekapitulasi transaksi peminjaman, tingkat kepatuhan pengembalian, dan pendapatan denda.</p>
        </div>
        <a href="{{ route('admin.laporan.cetak', ['tgl_mulai' => $tglMulai, 'tgl_selesai' => $tglSelesai, 'status' => $status]) }}" target="_blank"
            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-print mr-2"></i> Cetak Laporan PDF / Print
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label for="tgl_mulai" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" id="tgl_mulai" value="{{ $tglMulai }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="tgl_selesai" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" id="tgl_selesai" value="{{ $tglSelesai }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Filter Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ $status === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Telah Dikembalikan</option>
                    <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat / Denda</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-filter mr-1.5"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalPinjam }}</p>
            <span class="text-[10px] text-slate-400">Periode Terpilih</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Buku Dikembalikan</span>
            <p class="text-2xl font-black text-emerald-600 mt-1">{{ $totalSelesai }}</p>
            <span class="text-[10px] text-slate-400">Transaksi Selesai</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Sedang Dipinjam</span>
            <p class="text-2xl font-black text-blue-600 mt-1">{{ $totalAktif }}</p>
            <span class="text-[10px] text-slate-400">Buku di Tangan Siswa</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm">
            <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider">Total Denda Terkumpul</span>
            <p class="text-2xl font-black text-rose-600 mt-1">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
            <span class="text-[10px] text-slate-400">Penerimaan Kas Denda</span>
        </div>
    </div>

    <!-- Table Report -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 text-sm">Data Rekap Peminjaman Buku</h3>
            <span class="text-xs text-slate-500">
                Periode: <strong>{{ Carbon\Carbon::parse($tglMulai)->format('d/m/Y') }}</strong> s/d <strong>{{ Carbon\Carbon::parse($tglSelesai)->format('d/m/Y') }}</strong>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="p-3.5 w-10 text-center">#</th>
                        <th class="p-3.5">Kode Transaksi</th>
                        <th class="p-3.5">Peminjam</th>
                        <th class="p-3.5">Judul Buku</th>
                        <th class="p-3.5">Tgl Pinjam</th>
                        <th class="p-3.5">Batas Kembali</th>
                        <th class="p-3.5">Tgl Kembali</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksi as $index => $trx)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-3.5 font-mono font-bold text-slate-900">{{ $trx->kode_transaksi }}</td>
                            <td class="p-3.5">
                                <p class="font-bold text-slate-900">{{ $trx->user->name }}</p>
                                <span class="text-[10px] text-slate-400">{{ $trx->user->username ?? '-' }}</span>
                            </td>
                            <td class="p-3.5 font-medium text-slate-800">{{ $trx->buku->judul }}</td>
                            <td class="p-3.5 text-slate-600">{{ Carbon\Carbon::parse($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
                            <td class="p-3.5 text-slate-600">{{ Carbon\Carbon::parse($trx->tanggal_pengembalian)->format('d/m/Y') }}</td>
                            <td class="p-3.5 text-slate-600">{{ $trx->tanggal_dikembalikan ? Carbon\Carbon::parse($trx->tanggal_dikembalikan)->format('d/m/Y') : '-' }}</td>
                            <td class="p-3.5">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase
                                    {{ $trx->status_peminjaman === 'dikembalikan' ? 'bg-emerald-100 text-emerald-800' :
                                       ($trx->status_peminjaman === 'terlambat' ? 'bg-rose-100 text-rose-800' :
                                       ($trx->status_peminjaman === 'dipinjam' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800')) }}">
                                    {{ $trx->status_peminjaman }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right font-bold {{ $trx->denda > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                {{ $trx->denda > 0 ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">Tidak ada transaksi dalam rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($transaksi->count() > 0)
                    <tfoot class="bg-slate-50 font-bold border-t border-slate-200 text-xs">
                        <tr>
                            <td colspan="8" class="p-3.5 text-right uppercase text-[10px] text-slate-600">Total Denda Periode Ini:</td>
                            <td class="p-3.5 text-right text-rose-600 font-extrabold text-sm">
                                Rp {{ number_format($totalDenda, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

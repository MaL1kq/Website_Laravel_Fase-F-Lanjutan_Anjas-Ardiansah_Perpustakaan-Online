<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Perpustakaan - {{ Carbon\Carbon::parse($tglMulai)->format('d/m/Y') }} s/d {{ Carbon\Carbon::parse($tglSelesai)->format('d/m/Y') }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            table { font-size: 11px !important; }
        }
    </style>
</head>
<body class="bg-slate-100 py-10 antialiased text-slate-900">

    <!-- Print Action Bar -->
    <div class="max-w-5xl mx-auto mb-6 px-4 no-print flex items-center justify-between">
        <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Panel Laporan
        </a>
        <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Cetak / Simpan PDF
        </button>
    </div>

    <!-- Printable Paper Area -->
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-200 p-10 print:shadow-none print:border-none">
        <!-- Letterhead Header -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-800 flex items-center justify-center text-white text-2xl">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black uppercase tracking-wider text-slate-900">PERPUSTAKAAN DIGITAL TERPADU</h1>
                    <p class="text-xs text-slate-600 font-semibold">UNIT PELAKSANA TEKNIS (UPT) PERPUSTAKAAN & LITERASI SEKOLAH</p>
                    <p class="text-[11px] text-slate-500">Jl. Pendidikan No. 45 Jakarta | Telp: (021) 555-0199 | Website: perpus.sekolah.sch.id</p>
                </div>
            </div>
            <div class="text-right text-xs">
                <span class="inline-block px-3 py-1 rounded-full bg-slate-100 font-bold uppercase text-[10px] text-slate-700">DOKUMEN RESMI</span>
                <p class="text-[11px] text-slate-500 mt-1">Dicetak: {{ Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            </div>
        </div>

        <!-- Title of Report -->
        <div class="text-center my-6">
            <h2 class="text-lg font-black tracking-wider uppercase text-slate-900 underline underline-offset-4">LAPORAN REKAPITULASI PEMINJAMAN BUKU</h2>
            <p class="text-xs text-slate-600 mt-1">
                Periode: <strong>{{ Carbon\Carbon::parse($tglMulai)->isoFormat('D MMMM Y') }}</strong> s/d <strong>{{ Carbon\Carbon::parse($tglSelesai)->isoFormat('D MMMM Y') }}</strong>
                @if($status) | Status: <strong>{{ strtoupper($status) }}</strong> @endif
            </p>
        </div>

        <!-- Summary Statistics Table -->
        <div class="grid grid-cols-4 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs mb-6 text-center">
            <div>
                <span class="text-slate-500 text-[11px] block">Total Transaksi</span>
                <strong class="text-base text-slate-900">{{ $totalPinjam }}</strong>
            </div>
            <div>
                <span class="text-slate-500 text-[11px] block">Buku Dikembalikan</span>
                <strong class="text-base text-emerald-700">{{ $totalSelesai }}</strong>
            </div>
            <div>
                <span class="text-slate-500 text-[11px] block">Sedang Dipinjam</span>
                <strong class="text-base text-blue-700">{{ $totalAktif }}</strong>
            </div>
            <div>
                <span class="text-slate-500 text-[11px] block">Total Denda Terkumpul</span>
                <strong class="text-base text-rose-600">Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Transaction Details Table -->
        <table class="w-full text-xs text-left border border-slate-300 mb-8">
            <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10px] border-b border-slate-300">
                <tr>
                    <th class="p-2.5 border-r border-slate-300 text-center w-8">No</th>
                    <th class="p-2.5 border-r border-slate-300">Kode Transaksi</th>
                    <th class="p-2.5 border-r border-slate-300">Nama Peminjam</th>
                    <th class="p-2.5 border-r border-slate-300">Judul Buku</th>
                    <th class="p-2.5 border-r border-slate-300 text-center">Tgl Pinjam</th>
                    <th class="p-2.5 border-r border-slate-300 text-center">Batas Kembali</th>
                    <th class="p-2.5 border-r border-slate-300 text-center">Tgl Selesai</th>
                    <th class="p-2.5 border-r border-slate-300 text-center">Status</th>
                    <th class="p-2.5 text-right">Denda</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($transaksi as $index => $trx)
                    <tr>
                        <td class="p-2 border-r border-slate-200 text-center">{{ $index + 1 }}</td>
                        <td class="p-2 border-r border-slate-200 font-mono font-bold">{{ $trx->kode_transaksi }}</td>
                        <td class="p-2 border-r border-slate-200 font-semibold">{{ $trx->user->name }}</td>
                        <td class="p-2 border-r border-slate-200">{{ $trx->buku->judul }}</td>
                        <td class="p-2 border-r border-slate-200 text-center">{{ Carbon\Carbon::parse($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
                        <td class="p-2 border-r border-slate-200 text-center">{{ Carbon\Carbon::parse($trx->tanggal_pengembalian)->format('d/m/Y') }}</td>
                        <td class="p-2 border-r border-slate-200 text-center">{{ $trx->tanggal_dikembalikan ? Carbon\Carbon::parse($trx->tanggal_dikembalikan)->format('d/m/Y') : '-' }}</td>
                        <td class="p-2 border-r border-slate-200 text-center uppercase font-bold text-[10px]">
                            {{ $trx->status_peminjaman }}
                        </td>
                        <td class="p-2 text-right font-semibold {{ $trx->denda > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                            {{ $trx->denda > 0 ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-6 text-center text-slate-400">Tidak ada transaksi dalam periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-slate-100 font-bold border-t border-slate-300 text-xs">
                <tr>
                    <td colspan="8" class="p-2.5 text-right uppercase">Total Penerimaan Denda:</td>
                    <td class="p-2.5 text-right text-rose-600 font-extrabold">
                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures Section -->
        <div class="grid grid-cols-2 text-center text-xs mt-12 pt-6">
            <div>
                <p class="text-slate-500 mb-20">Mengetahui,<br><strong class="text-slate-800">Kepala Perpustakaan Sekolah</strong></p>
                <p class="font-bold underline text-slate-900 uppercase">Dra. Hj. Nuraini, M.Pd.</p>
                <p class="text-[11px] text-slate-500">NIP. 19740512 199903 2 003</p>
            </div>
            <div>
                <p class="text-slate-500 mb-20">Jakarta, {{ Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br><strong class="text-slate-800">Administrator Perpustakaan</strong></p>
                <p class="font-bold underline text-slate-900 uppercase">{{ Auth::user()->name }}</p>
                <p class="text-[11px] text-slate-500">NIP / ID: 19880914 201201 1 005</p>
            </div>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Buku ({{ $tglMulai }} s.d {{ $tglSelesai }}) - PerpusDigital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: white; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-4 sm:p-10 min-h-screen">

    <div class="max-w-4xl mx-auto mb-6 no-print flex justify-between items-center">
        <a href="javascript:history.back()" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center">
            Cetak Dokumen Laporan (PDF)
        </button>
    </div>

    <!-- Official Report Paper -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-lg border border-slate-200 print:shadow-none print:border print:rounded-none">
        <!-- Header Kop -->
        <div class="text-center pb-6 border-b-2 border-slate-800">
            <h1 class="text-xl font-black text-slate-900 tracking-wider uppercase">PERPUSTAKAAN & TOKO BUKU DIGITAL</h1>
            <p class="text-xs text-slate-600 mt-1">Laporan Resmi Rekapitulasi Penjualan Buku Berbasis Web</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Gedung Perpustakaan Lantai 1, Jl. Pendidikan No. 45, Jakarta | Telp: (021) 555-0199</p>
        </div>

        <!-- Meta Info -->
        <div class="py-5 flex justify-between items-center text-xs border-b border-slate-200">
            <div>
                <span class="text-slate-500">Periode Laporan:</span>
                <span class="font-bold text-slate-800 ml-1">
                    {{ Carbon\Carbon::parse($tglMulai)->isoFormat('D MMMM Y') }} s.d {{ Carbon\Carbon::parse($tglSelesai)->isoFormat('D MMMM Y') }}
                </span>
            </div>
            <div>
                <span class="text-slate-500">Dicetak Pada:</span>
                <span class="font-bold text-slate-800 ml-1">{{ Carbon\Carbon::now()->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
            </div>
        </div>

        <!-- Summary Statistics in Boxes -->
        <div class="grid grid-cols-3 gap-4 py-5 border-b border-slate-200 text-center text-xs">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-slate-500 block text-[11px]">Total Transaksi</span>
                <span class="text-lg font-black text-slate-900 mt-0.5 block">{{ $totalTransaksi }} Pesanan</span>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-slate-500 block text-[11px]">Total Buku Terjual</span>
                <span class="text-lg font-black text-emerald-600 mt-0.5 block">{{ $totalBukuTerjual }} Eksemplar</span>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-slate-500 block text-[11px]">Total Omset Pendapatan</span>
                <span class="text-lg font-black text-indigo-600 mt-0.5 block font-mono">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Table -->
        <div class="py-6">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="border-b-2 border-slate-300 text-slate-600 uppercase text-[10px] tracking-wider">
                        <th class="py-2.5">No</th>
                        <th class="py-2.5">Invoice</th>
                        <th class="py-2.5">Tanggal</th>
                        <th class="py-2.5">Pembeli</th>
                        <th class="py-2.5">Item Buku</th>
                        <th class="py-2.5 text-right">Total Biaya</th>
                        <th class="py-2.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[11px]">
                    @forelse($pesanan as $index => $item)
                        <tr>
                            <td class="py-2.5 text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="py-2.5 font-mono font-bold text-slate-800">{{ $item->kode_pesanan }}</td>
                            <td class="py-2.5 whitespace-nowrap">{{ $item->created_at->isoFormat('D/MM/Y') }}</td>
                            <td class="py-2.5 font-medium text-slate-800">{{ $item->nama_penerima }}</td>
                            <td class="py-2.5">
                                {{ $item->detail->sum('jumlah') }} Buku ({{ $item->detail->pluck('buku.judul')->join(', ') }})
                            </td>
                            <td class="py-2.5 text-right font-mono font-bold">{{ $item->total_harga_format }}</td>
                            <td class="py-2.5 text-center uppercase font-bold text-[10px]">{{ $item->status_label }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">Tidak ada transaksi penjualan dalam periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-12 mt-8 border-t border-slate-200 text-center text-xs">
            <div>
                <p class="text-slate-500 mb-16">Mengetahui,<br>Kepala Perpustakaan & Toko,</p>
                <p class="font-bold text-slate-800 underline uppercase">Drs. H. Mulyadi, M.Pd.</p>
                <p class="text-[10px] text-slate-400">NIP. 19750812 200003 1 002</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Jakarta, {{ Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>Administrator Toko & Sirkulasi,</p>
                <p class="font-bold text-slate-800 underline uppercase">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400">ID Petugas: ADM-{{ Auth::id() }}</p>
            </div>
        </div>
    </div>

</body>
</html>

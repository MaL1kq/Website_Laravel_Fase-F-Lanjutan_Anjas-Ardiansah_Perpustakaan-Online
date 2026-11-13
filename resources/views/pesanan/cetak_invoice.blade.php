<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembelian {{ $pesanan->kode_pesanan }} - PerpusDigital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: white; }
            .print-border { border-color: #cbd5e1 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-4 sm:p-10 min-h-screen">

    <div class="max-w-3xl mx-auto mb-6 no-print flex justify-between items-center">
        <a href="javascript:history.back()" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center">
            Cetak Invoice (PDF)
        </button>
    </div>

    <!-- Official Invoice Container -->
    <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-lg border border-slate-200 print:shadow-none print:border print:rounded-none">
        <!-- Header -->
        <div class="flex justify-between items-start pb-8 border-b-2 border-slate-800">
            <div>
                <div class="flex items-center space-x-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold">
                        PD
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight">PerpusDigital Store</h1>
                        <p class="text-xs text-slate-500">Layanan Toko Buku & Perpustakaan Terpadu</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-3 max-w-xs leading-relaxed">
                    Gedung Perpustakaan & Toko Buku Lantai 1<br>
                    Jl. Pendidikan No. 45, Jakarta | Telp: (021) 555-0199
                </p>
            </div>
            <div class="text-right">
                <span class="text-xs uppercase tracking-widest font-bold text-indigo-600">INVOICE RESMI</span>
                <p class="text-xl font-black text-slate-900 font-mono mt-1">{{ $pesanan->kode_pesanan }}</p>
                <p class="text-xs text-slate-500 mt-1">Tanggal: {{ $pesanan->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
                <div class="mt-2">
                    <span class="inline-block px-3 py-1 text-[11px] font-bold rounded-md bg-slate-100 text-slate-800 border border-slate-200 uppercase">
                        {{ $pesanan->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="grid grid-cols-2 gap-8 py-6 border-b border-slate-200 text-xs">
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Ditagihkan Kepada:</span>
                <p class="font-bold text-slate-900 text-sm">{{ $pesanan->user->name }}</p>
                <p class="text-slate-600 mt-0.5">Email: {{ $pesanan->user->email }}</p>
                <p class="text-slate-600">Username: {{ $pesanan->user->username }}</p>
            </div>
            <div>
                <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Alamat Pengiriman Paket:</span>
                <p class="font-bold text-slate-900 text-sm">{{ $pesanan->nama_penerima }}</p>
                <p class="text-slate-600 mt-0.5">Telp/WA: {{ $pesanan->no_telepon }}</p>
                <p class="text-slate-600 mt-0.5 leading-relaxed">{{ $pesanan->alamat_pengiriman }}</p>
                @if($pesanan->catatan)
                    <p class="text-slate-500 italic mt-1">Catatan: "{{ $pesanan->catatan }}"</p>
                @endif
            </div>
        </div>

        <!-- Purchased Items Table -->
        <div class="py-6">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-300 text-slate-500 uppercase tracking-wider text-[11px]">
                        <th class="text-left py-2.5">No</th>
                        <th class="text-left py-2.5">Judul Buku</th>
                        <th class="text-left py-2.5">Penulis</th>
                        <th class="text-right py-2.5">Harga</th>
                        <th class="text-center py-2.5">Qty</th>
                        <th class="text-right py-2.5">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pesanan->detail as $index => $item)
                        <tr>
                            <td class="py-3 text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="py-3 font-bold text-slate-800">{{ $item->buku->judul }}</td>
                            <td class="py-3 text-slate-500">{{ $item->buku->penulis }}</td>
                            <td class="py-3 text-right font-mono text-slate-600">{{ $item->harga_satuan_format }}</td>
                            <td class="py-3 text-center font-bold text-slate-800">{{ $item->jumlah }}</td>
                            <td class="py-3 text-right font-bold text-slate-900 font-mono">{{ $item->subtotal_format }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Calculation -->
        <div class="border-t-2 border-slate-800 pt-4 flex justify-between items-start text-xs">
            <div class="max-w-xs text-slate-500 leading-relaxed">
                <span class="font-bold text-slate-700 block mb-1">Metode Pembayaran:</span>
                <p class="font-bold text-indigo-600">{{ $pesanan->metode_pembayaran }}</p>
                <p class="text-[11px] text-slate-400 mt-2">Terima kasih atas pembelian Anda di PerpusDigital. Buku yang dibeli tidak dapat ditukar kembali kecuali terjadi kerusakan cetak.</p>
            </div>
            <div class="w-64 space-y-2 text-right">
                <div class="flex justify-between text-slate-600">
                    <span>Total Harga Buku:</span>
                    <span class="font-bold font-mono">{{ $pesanan->total_harga_format }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Kirim:</span>
                    <span class="font-bold text-emerald-600">GRATIS</span>
                </div>
                <div class="flex justify-between text-base font-black text-slate-900 pt-2 border-t border-slate-200">
                    <span>Total Tagihan:</span>
                    <span class="text-indigo-600 font-mono">{{ $pesanan->total_harga_format }}</span>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-12 mt-8 border-t border-slate-200 text-center text-xs">
            <div>
                <p class="text-slate-500 mb-16">Penerima Pesanan,</p>
                <p class="font-bold text-slate-800 underline uppercase">{{ $pesanan->nama_penerima }}</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Hormat Kami, Administrator,</p>
                <p class="font-bold text-slate-800 underline uppercase">PerpusDigital Admin</p>
            </div>
        </div>
    </div>

</body>
</html>

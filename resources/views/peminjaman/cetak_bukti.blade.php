<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman - {{ $peminjaman->kode_transaksi }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { box-shadow: none !important; border: 1px solid #000 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 py-10 antialiased text-slate-900">

    <!-- Action Bar (Hidden on Print) -->
    <div class="max-w-2xl mx-auto mb-6 px-4 no-print flex items-center justify-between">
        <a href="{{ route('peminjaman.riwayat') }}" class="inline-flex items-center text-xs font-semibold text-slate-600 hover:text-indigo-600 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Riwayat
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition flex items-center">
            <i class="fa-solid fa-print mr-2"></i> Cetak Dokumen Ini
        </button>
    </div>

    <!-- Printable Card -->
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-200 p-8 print-card">
        <!-- Letterhead Header -->
        <div class="text-center pb-6 border-b-2 border-slate-900">
            <div class="flex items-center justify-center space-x-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-indigo-700 flex items-center justify-center text-white text-xl">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <h1 class="text-2xl font-black tracking-wider uppercase">PERPUSTAKAAN DIGITAL</h1>
            </div>
            <p class="text-xs text-slate-600 font-medium">Sistem Sirkulasi & Literasi Online Terpadu</p>
            <p class="text-[11px] text-slate-500">Jl. Pendidikan No. 45 Jakarta | Telp: (021) 555-0199 | Email: perpus@sekolah.sch.id</p>
        </div>

        <div class="text-center my-6">
            <h2 class="text-base font-extrabold tracking-widest uppercase text-slate-800 underline underline-offset-4">BUKTI RESMI PEMINJAMAN BUKU</h2>
            <p class="font-mono text-sm font-bold text-indigo-700 mt-1">{{ $peminjaman->kode_transaksi }}</p>
        </div>

        <!-- Information Columns -->
        <div class="grid grid-cols-2 gap-6 text-xs mb-6">
            <div class="space-y-1.5 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <h3 class="font-bold text-slate-900 uppercase text-[11px] border-b border-slate-200 pb-1 mb-2">Data Peminjam</h3>
                <p><span class="text-slate-500 w-24 inline-block">Nama Lengkap:</span> <strong class="text-slate-800">{{ $peminjaman->user->name }}</strong></p>
                <p><span class="text-slate-500 w-24 inline-block">Username / NIS:</span> <strong class="text-slate-800">{{ $peminjaman->user->username ?? '-' }}</strong></p>
                <p><span class="text-slate-500 w-24 inline-block">No. Telepon:</span> {{ $peminjaman->user->no_telepon ?? '-' }}</p>
                <p><span class="text-slate-500 w-24 inline-block">Alamat:</span> {{ $peminjaman->user->alamat ?? '-' }}</p>
            </div>

            <div class="space-y-1.5 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <h3 class="font-bold text-slate-900 uppercase text-[11px] border-b border-slate-200 pb-1 mb-2">Detail Peminjaman</h3>
                <p><span class="text-slate-500 w-28 inline-block">Tgl Peminjaman:</span> <strong>{{ Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->isoFormat('D MMMM Y') }}</strong></p>
                <p><span class="text-slate-500 w-28 inline-block">Batas Waktu:</span> <strong class="text-indigo-700">{{ Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->isoFormat('D MMMM Y') }}</strong></p>
                <p><span class="text-slate-500 w-28 inline-block">Status:</span>
                    <span class="font-bold uppercase {{ $peminjaman->status_peminjaman === 'dikembalikan' ? 'text-emerald-600' : 'text-blue-600' }}">
                        {{ $peminjaman->status_peminjaman }}
                    </span>
                </p>
                <p><span class="text-slate-500 w-28 inline-block">Validasi Admin:</span> {{ $peminjaman->admin->name ?? 'Sistem Otomatis' }}</p>
            </div>
        </div>

        <!-- Book Detail Table -->
        <table class="w-full text-xs text-left border border-slate-200 mb-6">
            <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10px]">
                <tr>
                    <th class="p-3 border-b border-slate-200">Judul Buku</th>
                    <th class="p-3 border-b border-slate-200">Penulis</th>
                    <th class="p-3 border-b border-slate-200">Kategori</th>
                    <th class="p-3 border-b border-slate-200 text-center">Lokasi Rak</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-3 font-bold text-slate-900 border-b border-slate-100">{{ $peminjaman->buku->judul }}</td>
                    <td class="p-3 text-slate-700 border-b border-slate-100">{{ $peminjaman->buku->penulis }}</td>
                    <td class="p-3 text-slate-600 border-b border-slate-100">{{ $peminjaman->buku->kategori->nama_kategori ?? 'Umum' }}</td>
                    <td class="p-3 text-indigo-700 font-bold text-center border-b border-slate-100">{{ $peminjaman->buku->lokasi_rak ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Rules & Conditions -->
        <div class="bg-amber-50 border border-amber-200 p-3.5 rounded-xl text-[11px] text-amber-900 mb-8 space-y-1">
            <p class="font-bold uppercase tracking-wider text-[10px]">Ketentuan Peminjaman:</p>
            <ol class="list-decimal list-inside space-y-0.5 text-amber-800">
                <li>Buku wajib dijaga kebersihan, kelengkapan lembar, dan tidak dicoret-coret.</li>
                <li>Harap mengembalikan buku tepat waktu sebelum tanggal <strong>{{ Carbon\Carbon::parse($peminjaman->tanggal_pengembalian)->isoFormat('D MMMM Y') }}</strong>.</li>
                <li>Keterlambatan pengembalian dikenakan denda administrasi sebesar Rp 1.000 / hari.</li>
                <li>Harap menunjukkan struk/kartu ini saat mengambil atau mengembalikan buku di meja layanan.</li>
            </ol>
        </div>

        <!-- Signatures Area -->
        <div class="grid grid-cols-2 text-center text-xs mt-10">
            <div>
                <p class="text-slate-500 mb-16">Peminjam / Anggota,</p>
                <p class="font-bold underline text-slate-800 uppercase">{{ $peminjaman->user->name }}</p>
                <p class="text-[10px] text-slate-400">NIS/ID: {{ $peminjaman->user->username ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Administrator Perpustakaan,</p>
                <p class="font-bold underline text-slate-800 uppercase">{{ $peminjaman->admin->name ?? 'Admin Perpustakaan' }}</p>
                <p class="text-[10px] text-slate-400">NIP/ID: 19850720 201001 1 008</p>
            </div>
        </div>

        <!-- Barcode Decorative Stamp -->
        <div class="mt-8 pt-4 border-t border-slate-200 text-center">
            <div class="inline-block tracking-[0.5em] font-mono text-sm font-bold text-slate-400">
                ||| | ||||| || |||||| | ||| ||||||| |
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Dicetak secara digital pada {{ Carbon\Carbon::now()->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
        </div>
    </div>

</body>
</html>

@extends('layouts.app')

@section('title', 'About Us - Tentang PerpusDigital & BookStore')

@section('content')
<!-- Hero Section (Fluid Wide Container) -->
<section class="relative bg-gradient-to-b from-indigo-900 via-indigo-800 to-slate-900 text-white py-16 sm:py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider bg-white/10 text-indigo-200 px-3.5 py-1.5 rounded-full border border-white/15 mb-4">
                <i class="fa-solid fa-circle-info mr-2 text-indigo-400"></i> Detail Halaman About Us
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                Membuka Gerbang Ilmu Melalui Literasi Digital & Toko Buku Modern
            </h1>
            <p class="mt-4 text-sm sm:text-base text-slate-300 leading-relaxed">
                <strong>PerpusDigital BookStore</strong> adalah platform terpadu yang menggabungkan kemudahan sirkulasi peminjaman buku perpustakaan online serta pembelian buku fisik original berkualitas tinggi untuk siswa, pengajar, dan masyarakat umum.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('katalog') }}" class="px-6 py-3 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 transition flex items-center">
                    <i class="fa-solid fa-book-open mr-2"></i> Jelajahi Koleksi Buku
                </a>
                @if(!Auth::check() || !Auth::user()->isAdmin())
                    <a href="{{ route('contact') }}" class="px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center">
                        <i class="fa-solid fa-envelope mr-2"></i> Contact to Admin
                    </a>
                @else
                    <a href="{{ route('admin.pesan.index') }}" class="px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center">
                        <i class="fa-solid fa-comments mr-2"></i> Kelola Pesan Masuk
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Stats Highlight Section -->
<section class="relative -mt-10 z-20">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-lg shadow-slate-200/50 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalBuku }}</h3>
                    <p class="text-xs text-slate-500 font-medium">Judul Buku Terdaftar</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-lg shadow-slate-200/50 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalKategori }}</h3>
                    <p class="text-xs text-slate-500 font-medium">Kategori Ilmu & Sastra</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-lg shadow-slate-200/50 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ $totalUser }}</h3>
                    <p class="text-xs text-slate-500 font-medium">Anggota Siswa Aktif</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-lg shadow-slate-200/50 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">100%</h3>
                    <p class="text-xs text-slate-500 font-medium">Payment at Delivery Ready</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Details Section -->
<section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-16 space-y-16">
    <!-- Story & Mission Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Tentang Platform Kami</span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2 leading-tight">
                Solusi Lengkap Membaca & Memiliki Buku Impian Anda
            </h2>
            <div class="space-y-4 text-xs sm:text-sm text-slate-600 mt-4 leading-relaxed">
                <p>
                    Aplikasi ini dirancang khusus untuk memenuhi standar sertifikasi profesi keahlian Rekayasa Perangkat Lunak (RPL) dan BNSP, mengedepankan sistem arsitektur modern <strong>2 Aktor (Admin & User)</strong> yang handal, cepat, dan mudah digunakan.
                </p>
                <p>
                    Sebagai <strong>Perpustakaan Digital</strong>, kami memfasilitasi peminjaman buku fisik secara online tanpa antre, pencatatan otomatis masa tenggang, dan penghitungan denda yang transparan.
                </p>
                <p>
                    Sebagai <strong>Toko Buku (BookStore)</strong>, kami melayani pemesanan buku fisik original melalui fitur Keranjang Belanja (*Shopping Cart*), pilihan metode pembayaran aman <em>Payment at Delivery</em>, serta penerbitan invoice resmi.
                </p>
            </div>
        </div>

        <!-- Pillars Card Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="font-bold text-sm text-slate-900">2 Aktor Terintegrasi</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pengendalian penuh oleh Administrator dan kemudahan akses peminjaman serta belanja bagi User/Siswa.
                </p>
            </div>

            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <h3 class="font-bold text-sm text-slate-900">Add to Cart & Checkout</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Simpan buku favorit dalam keranjang, atur kuantitas fleksibel, dan checkout dalam hitungan detik.
                </p>
            </div>

            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3 class="font-bold text-sm text-slate-900">Payment at Delivery</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Mendukung pembayaran sebelum pengiriman maupun bayar di tempat demi kenyamanan dan keamanan transaksi.
                </p>
            </div>

            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="font-bold text-sm text-slate-900">Contact to Admin</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Fitur komunikasi langsung antara pembaca dan admin untuk konsultasi buku, konfirmasi, dan pertanyaan.
                </p>
            </div>
        </div>
    </div>

    <!-- Alur Layanan BookStore & Perpustakaan (Mermaid / Flow Style) -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-sm">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Cara Kerja Sistem</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Alur Mudah Pembelian & Peminjaman Buku</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Dua jalur fungsionalitas utama yang dapat diakses oleh seluruh pengguna terdaftar.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Jalur 1: Pembelian Buku -->
            <div class="p-6 rounded-2xl bg-emerald-50/50 border border-emerald-100 space-y-4">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center">1</span>
                    <h3 class="font-bold text-sm text-emerald-950 uppercase tracking-wider">Alur Pembelian Buku (Toko Buku)</h3>
                </div>
                <ol class="space-y-3 text-xs text-slate-600">
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Cari & Pilih Buku:</strong> Telusuri buku impian melalui katalog dan pencarian judul/ISBN.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Add to Cart:</strong> Masukkan ke keranjang belanja dan sesuaikan jumlah kuantitas.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Payment at Delivery:</strong> Isi alamat dan pilih pembayaran transfer sebelum dikirim atau COD.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                        <span><strong>Verifikasi & Pengiriman:</strong> Admin memproses pesanan dan menerbitkan invoice resmi.</span>
                    </li>
                </ol>
            </div>

            <!-- Jalur 2: Peminjaman Gratis -->
            <div class="p-6 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-4">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center">2</span>
                    <h3 class="font-bold text-sm text-indigo-950 uppercase tracking-wider">Alur Peminjaman Buku (Perpustakaan)</h3>
                </div>
                <ol class="space-y-3 text-xs text-slate-600">
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-indigo-600 mt-0.5"></i>
                        <span><strong>Ajukan Online:</strong> Pilih durasi peminjaman (hingga 14 hari) pada halaman detail buku.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-indigo-600 mt-0.5"></i>
                        <span><strong>Verifikasi Administrator:</strong> Admin menyetujui pengajuan sirkulasi buku secara real-time.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-indigo-600 mt-0.5"></i>
                        <span><strong>Cetak Bukti Pinjam:</strong> Cetak struk bukti peminjaman berbarcode untuk pengambilan fisik.</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i class="fa-solid fa-check text-indigo-600 mt-0.5"></i>
                        <span><strong>Kembalikan Tepat Waktu:</strong> Bebas denda jika dikembalikan sebelum tanggal jatuh tempo.</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Contact & Operational Info Card -->
    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <div class="lg:col-span-2 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Hubungi Kami</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold">Ada Pertanyaan Mengenai Koleksi atau Pesanan Buku?</h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Tim Administrator kami siap membantu Anda setiap hari kerja. Gunakan fitur <strong>Contact to Admin</strong> untuk mengirimkan pertanyaan, keluhan, atau konsultasi ketersediaan buku.
                </p>
                <div class="pt-2 flex flex-wrap gap-4 text-xs text-slate-300">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-location-dot text-indigo-400"></i>
                        <span>Gedung Perpustakaan & Toko Buku Lantai 1</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-clock text-indigo-400"></i>
                        <span>Senin - Jumat: 08.00 - 16.00 WIB</span>
                    </div>
                </div>
            </div>
            <div class="text-left lg:text-right">
                <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pesan ke Admin Sekarang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

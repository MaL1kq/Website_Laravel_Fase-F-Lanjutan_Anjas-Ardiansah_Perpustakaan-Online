@extends('layouts.app')

@section('title', 'Formulir Checkout & Pembayaran - PerpusDigital')

@section('content')
<div class="bg-indigo-900/5 border-b border-indigo-100 py-8">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md">
                <i class="fa-solid fa-credit-card text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Checkout & Pembayaran</h1>
                <p class="text-sm text-slate-500 mt-0.5">Lengkapi informasi pengiriman buku dan pilih metode pembayaran favorit Anda.</p>
            </div>
        </div>
    </div>
</div>

<div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-10">
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Shipping & Payment Form -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Section 1: Alamat Pengiriman -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 flex items-center mb-6">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs mr-3">1</span>
                        Informasi Penerima & Alamat Pengiriman
                    </h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_penerima" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Nama Lengkap Penerima <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nama_penerima" id="nama_penerima" required
                                    value="{{ old('nama_penerima', $user->name) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('nama_penerima') border-rose-500 @enderror"
                                    placeholder="Nama penerima paket">
                                @error('nama_penerima')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_telepon" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="no_telepon" id="no_telepon" required
                                    value="{{ old('no_telepon', $user->no_telepon) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('no_telepon') border-rose-500 @enderror"
                                    placeholder="Contoh: 081234567890">
                                @error('no_telepon')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="alamat_pengiriman" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Alamat Lengkap Pengiriman <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" required
                                class="w-full p-4 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('alamat_pengiriman') border-rose-500 @enderror"
                                placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, dan kota/kabupaten...">{{ old('alamat_pengiriman', $user->alamat) }}</textarea>
                            @error('alamat_pengiriman')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Catatan untuk Penjual / Kurir (Opsional)
                            </label>
                            <input type="text" name="catatan" id="catatan"
                                value="{{ old('catatan') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                placeholder="Contoh: Titipkan di pos satpam jika tidak ada orang.">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Metode Pembayaran -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 flex items-center mb-6">
                        <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs mr-3">2</span>
                        Pilih Metode Pembayaran
                    </h2>

                    <div class="space-y-3" x-data="{ selectedMethod: 'Payment at Delivery (Pembayaran sebelum dikirim)' }">
                        <!-- Pilihan Khusus Sesuai Standar Uji BNSP -->
                        <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                            :class="selectedMethod === 'Payment at Delivery (Pembayaran sebelum dikirim)' ? 'border-indigo-600 bg-indigo-50/70 shadow-sm ring-2 ring-indigo-500/20' : 'border-slate-200 hover:border-slate-300'">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="metode_pembayaran" value="Payment at Delivery (Pembayaran sebelum dikirim)" x-model="selectedMethod" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-bold text-slate-900">Payment at Delivery (Pembayaran sebelum dikirim)</p>
                                        <span class="text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">Standar BNSP</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">Pembayaran diverifikasi via Transfer/QRIS sebelum kurir memberangkatkan pesanan buku.</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-truck-fast text-indigo-600 text-lg"></i>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                            :class="selectedMethod === 'Transfer Bank BCA' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300'">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="metode_pembayaran" value="Transfer Bank BCA" x-model="selectedMethod" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Transfer Bank BCA</p>
                                    <p class="text-xs text-slate-500 mt-0.5">No. Rek: <span class="font-mono font-bold text-slate-700">123-456-7890</span> a/n PerpusDigital</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-building-columns text-slate-400 text-lg"></i>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                            :class="selectedMethod === 'Transfer Bank Mandiri' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300'">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="metode_pembayaran" value="Transfer Bank Mandiri" x-model="selectedMethod" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Transfer Bank Mandiri</p>
                                    <p class="text-xs text-slate-500 mt-0.5">No. Rek: <span class="font-mono font-bold text-slate-700">987-654-3210</span> a/n PerpusDigital</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-building-columns text-slate-400 text-lg"></i>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                            :class="selectedMethod === 'QRIS' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300'">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="metode_pembayaran" value="QRIS" x-model="selectedMethod" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">QRIS (Semua E-Wallet & M-Banking)</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Gopay, OVO, DANA, ShopeePay, BCA Mobile, dll.</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-qrcode text-slate-400 text-lg"></i>
                        </label>

                        <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition"
                            :class="selectedMethod === 'COD / Bayar di Tempat' ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' : 'border-slate-200 hover:border-slate-300'">
                            <div class="flex items-center space-x-3">
                                <input type="radio" name="metode_pembayaran" value="COD / Bayar di Tempat" x-model="selectedMethod" class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-bold text-slate-900">COD (Cash on Delivery)</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Bayar tunai kepada kurir saat buku sampai di alamat Anda.</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-hand-holding-dollar text-slate-400 text-lg"></i>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm sticky top-24">
                    <h2 class="text-base font-black text-slate-900 uppercase tracking-wider mb-5">Rincian Buku</h2>

                    <div class="space-y-3.5 max-h-72 overflow-y-auto pr-1 divide-y divide-slate-100">
                        @foreach($items as $item)
                            <div class="pt-3 first:pt-0 flex items-center justify-between space-x-3">
                                <img src="{{ $item->buku->cover_url }}" alt="{{ $item->buku->judul }}" class="w-12 h-16 object-cover rounded-lg shadow-sm flex-shrink-0 bg-slate-100">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate">{{ $item->buku->judul }}</h4>
                                    <p class="text-[11px] text-slate-400">{{ $item->jumlah }} x {{ $item->buku->harga_format }}</p>
                                </div>
                                <div class="text-xs font-extrabold text-slate-800 flex-shrink-0">
                                    {{ $item->subtotal_format }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-5 border-t border-slate-100 space-y-2.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal Buku ({{ $totalItem }} item)</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span class="font-bold text-emerald-600">GRATIS ONGKIR</span>
                        </div>
                    </div>

                    <div class="pt-5 mt-5 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-700">Total Pembayaran:</span>
                        <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="w-full mt-6 py-4 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-xl shadow-indigo-200 transition duration-150 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-lock text-xs"></i>
                        <span>Konfirmasi & Buat Pesanan</span>
                    </button>

                    <p class="text-[11px] text-center text-slate-400 mt-4">
                        Dengan mengklik tombol di atas, Anda menyetujui transaksi pembelian buku di PerpusDigital.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

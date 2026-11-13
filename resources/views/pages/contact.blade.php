@extends('layouts.app')

@section('title', 'Contact to Admin - Hubungi Pengelola BookStore')

@section('content')
<div class="bg-indigo-900/5 border-b border-indigo-100 py-8">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
        <div class="flex items-center text-xs text-slate-500 space-x-2 mb-2">
            <a href="{{ route('home') }}" class="hover:text-indigo-600">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-800 font-semibold">Contact to Admin</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Contact to Admin (Hubungi Kami)</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Kirimkan pesan resmi, konfirmasi pembayaran sebelum buku dikirim (Payment at Delivery), atau pertanyaan ketersediaan buku langsung melalui formulir pesan di bawah ini.
        </p>
    </div>
</div>

<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Formulir Pesan Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Layanan Komunikasi</span>
                <h2 class="text-xl font-bold text-slate-900 mt-0.5">Formulir Pesan ke Administrator</h2>
                <p class="text-xs text-slate-500 mt-0.5">Administrator akan meninjau pesan Anda dan memberikan tanggapan secara berkala.</p>
            </div>
            <span class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa-solid fa-paper-plane"></i>
            </span>
        </div>

        <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama"
                        value="{{ old('nama', Auth::check() ? Auth::user()->name : '') }}" required
                        placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama') border-rose-500 @enderror">
                    @error('nama') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" required
                        placeholder="budi@example.com"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-rose-500 @enderror">
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nomor WhatsApp / Telepon -->
                <div>
                    <label for="no_telepon" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        No. Telepon / WhatsApp (Opsional)
                    </label>
                    <input type="text" name="no_telepon" id="no_telepon"
                        value="{{ old('no_telepon', Auth::check() ? Auth::user()->no_telepon : '') }}"
                        placeholder="081234567890"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Subjek Pesan -->
                <div>
                    <label for="subjek" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Subjek Pesan <span class="text-rose-500">*</span>
                    </label>
                    <select name="subjek" id="subjek" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="Konfirmasi Pembayaran (Payment at Delivery)">Konfirmasi Pembayaran (Payment at Delivery)</option>
                        <option value="Pertanyaan Stok Buku">Pertanyaan Stok & Ketersediaan Buku</option>
                        <option value="Permintaan Restok Judul Buku">Permintaan Restok Judul Buku</option>
                        <option value="Pengajuan Peminjaman Sirkulasi">Pengajuan Peminjaman Sirkulasi</option>
                        <option value="Kritik, Saran & Masukan">Kritik, Saran & Masukan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <!-- Isi Pesan -->
            <div>
                <label for="pesan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Isi Pesan Lengkap <span class="text-rose-500">*</span>
                </label>
                <textarea name="pesan" id="pesan" rows="5" required
                    placeholder="Tuliskan pertanyaan, nomor invoice pesanan, atau pesan Anda untuk admin di sini..."
                    class="w-full p-4 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('pesan') border-rose-500 @enderror">{{ old('pesan') }}</textarea>
                @error('pesan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2 flex items-center justify-end">
                <button type="submit" class="px-7 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-200 transition duration-150 flex items-center">
                    <span>Kirim Pesan ke Admin</span>
                    <i class="fa-solid fa-paper-plane ml-2 text-xs"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Message History Card (Tampil jika user login) -->
    @auth
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                <div>
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Riwayat Komunikasi</span>
                    <h2 class="text-lg font-bold text-slate-900 mt-0.5">Pesan Anda yang Sudah Terkirim ({{ $riwayatPesan ? $riwayatPesan->count() : 0 }})</h2>
                </div>
                <span class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </span>
            </div>

            @if($riwayatPesan && $riwayatPesan->count() > 0)
                <div class="space-y-4 divide-y divide-slate-100">
                    @foreach($riwayatPesan as $item)
                        <div class="pt-4 first:pt-0 space-y-2">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 text-xs">
                                <h3 class="font-bold text-slate-900 text-sm flex items-center">
                                    <i class="fa-regular fa-message mr-2 text-indigo-600 text-xs"></i>
                                    {{ $item->subjek }}
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $item->status_badge }}">
                                        {{ $item->status_label }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed">
                                {{ $item->pesan }}
                            </div>

                            <!-- Balasan Admin jika ada -->
                            @if($item->balasan_admin)
                                <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200/80 text-xs text-emerald-950 sm:ml-4 space-y-1">
                                    <div class="flex items-center justify-between text-[11px] font-bold text-emerald-800 mb-1">
                                        <span class="flex items-center">
                                            <i class="fa-solid fa-reply mr-1.5"></i> Balasan dari Administrator:
                                        </span>
                                        <span class="text-[10px] text-emerald-600 font-normal">
                                            {{ $item->dibalas_pada ? $item->dibalas_pada->format('d/m/Y H:i') : '' }}
                                        </span>
                                    </div>
                                    <p class="leading-relaxed">{{ $item->balasan_admin }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <i class="fa-regular fa-envelope-open text-3xl mb-2 text-slate-300"></i>
                    <p class="text-xs">Anda belum pernah mengirimkan pesan ke admin.</p>
                </div>
            @endif
        </div>
    @endauth
</div>
@endsection

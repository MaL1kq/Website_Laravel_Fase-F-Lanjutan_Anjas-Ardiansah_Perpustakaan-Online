@extends('layouts.admin')

@section('title', 'Detail Pesan Masuk - Panel Administrator')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center text-xs text-slate-400 space-x-2 mb-1">
                <a href="{{ route('admin.pesan.index') }}" class="hover:text-indigo-600">Pesan Masuk</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-700 font-semibold">Detail Pesan</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">Detail Pesan Pengguna</h1>
        </div>
        <a href="{{ route('admin.pesan.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
            &larr; Kembali
        </a>
    </div>

    <!-- Message Content Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        <!-- Sender Meta & Status -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 font-black text-base flex items-center justify-center uppercase">
                    {{ substr($pesan->nama, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="font-extrabold text-slate-900 text-base">{{ $pesan->nama }}</h2>
                        @if($pesan->user)
                            <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                                Anggota Terdaftar (ID: {{ $pesan->user->id }})
                            </span>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mt-1">
                        <span><i class="fa-regular fa-envelope mr-1 text-slate-400"></i> {{ $pesan->email }}</span>
                        @if($pesan->no_telepon)
                            <span><i class="fa-solid fa-phone mr-1 text-slate-400"></i> {{ $pesan->no_telepon }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $pesan->status_badge }}">
                    {{ $pesan->status_label }}
                </span>
                <span class="text-[11px] text-slate-400 block mt-1">
                    Diterima: {{ $pesan->created_at->format('d/m/Y H:i') }} WIB
                </span>
            </div>
        </div>

        <!-- Subject & Body -->
        <div class="space-y-3">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Subjek:</span>
                <h3 class="text-base font-bold text-slate-900 mt-0.5">{{ $pesan->subjek }}</h3>
            </div>

            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Isi Pesan:</span>
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-800 leading-relaxed whitespace-pre-line mt-1">
                    {{ $pesan->pesan }}
                </div>
            </div>
        </div>


    </div>

    <!-- Reply Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center">
                <i class="fa-solid fa-reply mr-2 text-indigo-600"></i>
                {{ $pesan->balasan_admin ? 'Perbarui Balasan Admin' : 'Kirim Balasan Pesan' }}
            </h3>
            @if($pesan->dibalas_pada)
                <span class="text-[11px] text-emerald-600 font-semibold">
                    Dibalas pada: {{ $pesan->dibalas_pada->format('d/m/Y H:i') }} WIB
                </span>
            @endif
        </div>

        <form action="{{ route('admin.pesan.reply', $pesan->id) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="balasan_admin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Teks Tanggapan / Balasan untuk Pengguna
                </label>
                <textarea name="balasan_admin" id="balasan_admin" rows="5" required
                    placeholder="Tuliskan jawaban resmi administrator di sini..."
                    class="w-full p-4 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">{{ old('balasan_admin', $pesan->balasan_admin) }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">
                    Balasan ini akan langsung tampil di halaman <strong>Contact to Admin</strong> akun pengguna yang bersangkutan.
                </p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Simpan & Kirim Balasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

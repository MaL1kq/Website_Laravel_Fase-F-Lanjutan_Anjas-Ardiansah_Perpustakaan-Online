@extends('layouts.admin')

@section('title', 'Pesan Masuk (Contact to Admin) - Panel Administrator')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Pesan Masuk Pengguna</h1>
            <p class="text-xs text-slate-500 mt-1">
                Kelola pesan, pertanyaan, dan konfirmasi pembayaran yang dikirimkan oleh pengguna melalui fitur Contact to Admin.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-sm">
                Total: {{ $totalSemua }} Pesan
            </span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.pesan.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="relative w-full sm:w-72">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, subjek..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>

            <!-- Filter Status Pill Buttons -->
            <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
                <a href="{{ route('admin.pesan.index') }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua ({{ $totalSemua }})
                </a>
                <a href="{{ route('admin.pesan.index', ['status' => 'belum_dibaca']) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'belum_dibaca' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Pesan Baru ({{ $totalBelumDibaca }})
                </a>
                <a href="{{ route('admin.pesan.index', ['status' => 'dibalas']) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('status') === 'dibalas' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Sudah Dibalas ({{ $totalDibalas }})
                </a>
            </div>

            @if(request()->anyFilled(['q', 'status']))
                <a href="{{ route('admin.pesan.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4">Pengirim & Kontak</th>
                        <th class="p-4">Subjek & Cuplikan Pesan</th>
                        <th class="p-4">Waktu Masuk</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesanList as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition {{ $item->status === 'belum_dibaca' ? 'bg-rose-50/30' : '' }}">
                            <td class="p-4 text-center font-bold text-slate-400">
                                {{ $pesanList->firstItem() + $index }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full {{ $item->user ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }} font-bold text-xs flex items-center justify-center uppercase">
                                        {{ substr($item->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-xs">{{ $item->nama }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $item->email }}</p>
                                        @if($item->no_telepon)
                                            <p class="text-[10px] text-emerald-600 font-mono">{{ $item->no_telepon }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 max-w-sm">
                                <h4 class="font-bold text-slate-900 text-xs truncate">{{ $item->subjek }}</h4>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $item->pesan }}</p>
                                @if($item->balasan_admin)
                                    <span class="inline-block text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md mt-1 border border-emerald-100 font-medium">
                                        <i class="fa-solid fa-reply mr-1"></i> Telah dijawab
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-700 block">{{ $item->created_at->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $item->status_badge }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.pesan.show', $item->id) }}"
                                   class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-600 font-bold text-xs transition inline-flex items-center">
                                    <span>Buka & Balas</span>
                                    <i class="fa-solid fa-chevron-right ml-1.5 text-[9px]"></i>
                                </a>
                                <form action="{{ route('admin.pesan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Tidak ada pesan masuk yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $pesanList->links() }}
        </div>
    </div>
</div>
@endsection

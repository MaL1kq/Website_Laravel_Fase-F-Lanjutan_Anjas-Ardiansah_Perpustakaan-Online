@extends('layouts.admin')

@section('title', 'Manajemen Koleksi Buku - PerpusDigital')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Koleksi Buku</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data buku, nomor rak, stok fisik, dan detail referensi pustaka.</p>
        </div>
        <a href="{{ route('admin.buku.create') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-plus mr-1.5"></i> Tambah Buku Baru
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.buku.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="relative w-full sm:w-72">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis, ISBN..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>

            <select name="kategori" onchange="this.form.submit()" class="w-full sm:w-auto px-3 py-2 rounded-xl border border-slate-300 text-xs bg-slate-50 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition">
                Cari
            </button>

            @if(request()->anyFilled(['q', 'kategori']))
                <a href="{{ route('admin.buku.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <span class="text-xs text-slate-500">Total: <strong>{{ $buku->total() }}</strong> Judul Buku</span>
    </div>

    <!-- Book Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4">Buku & Penulis</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Penerbit / Tahun</th>
                        <th class="p-4 text-center">Lokasi Rak</th>
                        <th class="p-4 text-center">Stok</th>
                        <th class="p-4 text-right">Harga Jual</th>
                        <th class="p-4 text-center">Dipinjam</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($buku as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-center font-bold text-slate-400">
                                {{ $buku->firstItem() + $index }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $item->cover_url }}" alt="{{ $item->judul }}" class="w-10 h-14 object-cover rounded-lg flex-shrink-0 shadow-sm bg-slate-100">
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-slate-900 text-xs truncate max-w-xs">{{ $item->judul }}</h4>
                                        <p class="text-[11px] text-slate-500">Penulis: {{ $item->penulis }}</p>
                                        <p class="text-[10px] font-mono text-slate-400">ISBN: {{ $item->isbn ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="bg-indigo-50 text-indigo-700 font-bold px-2.5 py-0.5 rounded-full text-[10px]">
                                    {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <p class="text-slate-800 font-medium">{{ $item->penerbit }}</p>
                                <span class="text-[10px] text-slate-400">Tahun {{ $item->tahun_terbit }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-bold text-indigo-700 bg-slate-100 px-2 py-1 rounded-md text-[11px]">
                                    {{ $item->lokasi_rak ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-bold px-2 py-0.5 rounded-full text-[11px] {{ $item->stok > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $item->stok }} Exp
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <span class="font-black text-emerald-700 text-xs">{{ $item->harga_format }}</span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-slate-600 font-semibold">{{ $item->peminjaman_count }}x</span>
                            </td>
                            <td class="p-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('buku.detail', $item->slug) }}" target="_blank" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Lihat Halaman Publik">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <a href="{{ route('admin.buku.edit', $item->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Buku">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.buku.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini dari sistem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Buku">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">
                                Tidak ada buku yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $buku->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Manajemen Kategori Buku - PerpusDigital')

@section('content')
<div class="space-y-6" x-data="{
    modalTambah: {{ $errors->any() && !old('edit_id') ? 'true' : 'false' }},
    modalEdit: {{ old('edit_id') ? 'true' : 'false' }},
    editData: {
        id: '{{ old('edit_id') }}',
        nama_kategori: '{{ addslashes(old('nama_kategori')) }}',
        deskripsi: '{{ addslashes(old('deskripsi')) }}'
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Kategori Buku</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola klasifikasi dan genre koleksi pustaka.</p>
        </div>
        <button @click="modalTambah = true" type="button" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-plus mr-1.5"></i> Tambah Kategori
        </button>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.kategori.index') }}" method="GET" class="w-full sm:w-80">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kategori..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>
        </form>
        <span class="text-xs text-slate-400">Total: <strong>{{ $kategori->total() }}</strong> Kategori</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4">Nama Kategori</th>
                        <th class="p-4">Slug</th>
                        <th class="p-4">Deskripsi</th>
                        <th class="p-4 text-center">Jumlah Buku</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategori as $index => $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-center font-bold text-slate-400">
                                {{ $kategori->firstItem() + $index }}
                            </td>
                            <td class="p-4 font-bold text-slate-900">
                                {{ $item->nama_kategori }}
                            </td>
                            <td class="p-4 font-mono text-[11px] text-slate-500">
                                {{ $item->slug }}
                            </td>
                            <td class="p-4 text-slate-600 max-w-xs truncate">
                                {{ $item->deskripsi ?? '-' }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-indigo-50 text-indigo-700 font-bold px-2.5 py-1 rounded-full text-[11px]">
                                    {{ $item->buku_count }} Buku
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                <button type="button"
                                    @click="editData = { id: '{{ $item->id }}', nama_kategori: '{{ addslashes($item->nama_kategori) }}', deskripsi: '{{ addslashes($item->deskripsi) }}' }; modalEdit = true"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Belum ada kategori yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $kategori->links() }}
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="modalTambah = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-900 text-sm">Tambah Kategori Baru</h3>
                    <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required placeholder="Contoh: Novel Remaja"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 @error('nama_kategori') border-rose-500 ring-2 ring-rose-200 @enderror">
                        @if($errors->has('nama_kategori') && !old('edit_id'))
                            <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center">
                                <i class="fa-solid fa-circle-exclamation mr-1.5"></i> {{ $errors->first('nama_kategori') }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Deskripsi Kategori</label>
                        <textarea name="deskripsi" rows="3" placeholder="Penjelasan singkat kategori..."
                            class="w-full p-3 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="modalEdit = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-900 text-sm">Edit Kategori</h3>
                    <button @click="modalEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form :action="'{{ url('admin/kategori') }}/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edit_id" :value="editData.id">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kategori" x-model="editData.nama_kategori" required
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 @error('nama_kategori') border-rose-500 @enderror">
                        @if($errors->has('nama_kategori') && old('edit_id'))
                            <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center">
                                <i class="fa-solid fa-circle-exclamation mr-1.5"></i> {{ $errors->first('nama_kategori') }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Deskripsi Kategori</label>
                        <textarea name="deskripsi" x-model="editData.deskripsi" rows="3"
                            class="w-full p-3 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="modalEdit = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

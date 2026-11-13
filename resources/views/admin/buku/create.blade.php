@extends('layouts.admin')

@section('title', 'Tambah Buku Baru - PerpusDigital')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ coverType: 'url', previewUrl: '' }">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center text-xs text-slate-400 space-x-2 mb-1">
                <a href="{{ route('admin.buku.index') }}" class="hover:text-indigo-600">Koleksi Buku</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-slate-700 font-semibold">Tambah Baru</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">Tambah Buku Baru</h1>
        </div>
        <a href="{{ route('admin.buku.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Buku -->
                <div class="md:col-span-2">
                    <label for="judul" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Judul Buku <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required placeholder="Contoh: Pemrograman Web dengan Laravel Modern"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 @error('judul') border-rose-500 @enderror">
                    @error('judul') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Penulis -->
                <div>
                    <label for="penulis" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Penulis <span class="text-rose-500">*</span></label>
                    <input type="text" name="penulis" id="penulis" value="{{ old('penulis') }}" required placeholder="Contoh: Taylor Otwell"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 @error('penulis') border-rose-500 @enderror">
                    @error('penulis') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Penerbit -->
                <div>
                    <label for="penerbit" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Penerbit <span class="text-rose-500">*</span></label>
                    <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit') }}" required placeholder="Contoh: Informatika Bandung"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 @error('penerbit') border-rose-500 @enderror">
                    @error('penerbit') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kategori Buku -->
                <div>
                    <label for="kategori_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kategori Buku <span class="text-rose-500">*</span></label>
                    <select name="kategori_id" id="kategori_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label for="tahun_terbit" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tahun Terbit <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}" required min="1900" max="{{ date('Y') + 1 }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- ISBN -->
                <div>
                    <label for="isbn" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nomor ISBN</label>
                    <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" placeholder="Contoh: 978-602-1234-56-7"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-indigo-500 @error('isbn') border-rose-500 ring-2 ring-rose-200 @enderror">
                    @error('isbn') <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p> @enderror
                </div>

                <!-- Stok, Harga & Lokasi Rak -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="stok" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jumlah Stok <span class="text-rose-500">*</span></label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', 5) }}" required min="0"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="harga" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Harga Jual / Beli (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', 50000) }}" required min="0" step="500" placeholder="50000"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 font-bold text-emerald-700">
                        @error('harga') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="lokasi_rak" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" id="lokasi_rak" value="{{ old('lokasi_rak', 'Rak A-01') }}" placeholder="Rak A-01"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Pilihan Sampul Buku (Cover) -->
                <div class="md:col-span-2 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Sampul Buku (Cover)</label>
                    <div class="flex items-center space-x-4 mb-3 text-xs font-semibold">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="cover_choice" value="url" x-model="coverType" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-slate-700">Tautan Gambar (URL Web)</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="cover_choice" value="upload" x-model="coverType" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-slate-700">Upload File Gambar</span>
                        </label>
                    </div>

                    <div x-show="coverType === 'url'">
                        <input type="url" name="cover_url" x-model="previewUrl" placeholder="https://images.unsplash.com/photo-..."
                            class="w-full px-4 py-2 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-indigo-500 @error('cover_url') border-rose-500 ring-2 ring-rose-200 @enderror">
                        @error('cover_url') <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p> @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Masukkan URL gambar sampul buku dari Unsplash atau sumber online lainnya.</p>
                    </div>

                    <div x-show="coverType === 'upload'" x-cloak>
                        <input type="file" name="cover_file" accept="image/*"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 @error('cover_file') border-rose-500 @enderror">
                        @error('cover_file') <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p> @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
                    </div>

                    <!-- Live Image Preview -->
                    <template x-if="previewUrl">
                        <div class="mt-3 flex items-center space-x-3 p-2 bg-white rounded-xl border border-slate-200">
                            <img :src="previewUrl" alt="Preview Cover" class="w-12 h-16 object-cover rounded-lg shadow-sm">
                            <span class="text-xs text-slate-500">Pratinjau Sampul Aktif</span>
                        </div>
                    </template>
                </div>

                <!-- Deskripsi / Sinopsis -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Sinopsis & Ringkasan Buku</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Tuliskan rangkuman dan sinopsis isi buku..."
                        class="w-full p-4 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.buku.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Buku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
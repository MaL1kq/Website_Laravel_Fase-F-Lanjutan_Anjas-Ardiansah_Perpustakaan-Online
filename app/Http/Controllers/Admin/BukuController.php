<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with(['kategori', 'ulasan'])->withCount('peminjaman');

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                  ->orWhere('penulis', 'like', $keyword)
                  ->orWhere('penerbit', 'like', $keyword)
                  ->orWhere('isbn', 'like', $keyword);
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $buku = $query->latest()->paginate(10)->withQueryString();
        $kategoriList = KategoriBuku::orderBy('nama_kategori')->get();

        return view('admin.buku.index', compact('buku', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = KategoriBuku::orderBy('nama_kategori')->get();
        return view('admin.buku.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255|unique:buku,judul',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:50|unique:buku,isbn',
            'kategori_id' => 'required|exists:kategori_buku,id',
            'harga' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_url' => 'nullable|url|max:500',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.unique' => 'Buku dengan judul "' . $request->judul . '" sudah terdaftar! Judul buku tidak boleh duplikat.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'isbn.unique' => 'Nomor ISBN ' . $request->isbn . ' sudah digunakan oleh buku lain! Buku tidak boleh memiliki ISBN yang sama.',
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'stok.required' => 'Stok buku wajib diisi minimal 0.',
        ]);

        if ($request->filled('cover_url')) {
            $existingCover = Buku::where('cover', trim($request->cover_url))->first();
            if ($existingCover && strtolower(trim($existingCover->penulis)) === strtolower(trim($request->penulis))) {
                return back()->withInput()->withErrors([
                    'cover_url' => 'Buku dengan gambar cover dan penulis yang sama sudah ada di sistem ("' . $existingCover->judul . '"). Harap periksa apakah buku ini merupakan duplikasi.'
                ]);
            }
        }

        $cover = null;
        if ($request->hasFile('cover_file')) {
            $cover = $request->file('cover_file')->store('covers', 'public');
        } elseif ($request->filled('cover_url')) {
            $cover = $request->cover_url;
        }

        $buku = Buku::create([
            'judul' => trim($request->judul),
            'slug' => Str::slug($request->judul) . '-' . Str::random(5),
            'penulis' => trim($request->penulis),
            'penerbit' => trim($request->penerbit),
            'tahun_terbit' => $request->tahun_terbit,
            'isbn' => $request->isbn ? trim($request->isbn) : null,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga ?? 0,
            'stok' => $request->stok,
            'lokasi_rak' => $request->lokasi_rak,
            'deskripsi' => $request->deskripsi,
            'cover' => $cover,
        ]);

        // Hubungkan ke pivot relasi
        $buku->kategoriRelasi()->sync([$request->kategori_id]);

        return redirect()->route('admin.buku.index')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoriList = KategoriBuku::orderBy('nama_kategori')->get();
        return view('admin.buku.edit', compact('buku', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255|unique:buku,judul,' . $buku->id,
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:50|unique:buku,isbn,' . $buku->id,
            'kategori_id' => 'required|exists:kategori_buku,id',
            'harga' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_url' => 'nullable|url|max:500',
        ], [
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.unique' => 'Buku dengan judul "' . $request->judul . '" sudah terdaftar pada buku lain! Judul buku tidak boleh duplikat.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'isbn.unique' => 'Nomor ISBN ' . $request->isbn . ' sudah digunakan oleh buku lain! Buku tidak boleh memiliki ISBN yang sama.',
            'kategori_id.required' => 'Kategori buku wajib dipilih.',
            'stok.required' => 'Stok buku wajib diisi minimal 0.',
        ]);

        if ($request->filled('cover_url')) {
            $existingCover = Buku::where('cover', trim($request->cover_url))
                ->where('id', '!=', $buku->id)
                ->first();
            if ($existingCover && strtolower(trim($existingCover->penulis)) === strtolower(trim($request->penulis))) {
                return back()->withInput()->withErrors([
                    'cover_url' => 'Buku dengan gambar cover dan penulis yang sama sudah ada di sistem ("' . $existingCover->judul . '"). Harap periksa apakah buku ini merupakan duplikasi.'
                ]);
            }
        }

        $cover = $buku->cover;
        if ($request->hasFile('cover_file')) {
            // Hapus cover lama jika file lokal
            if ($buku->cover && !Str::startsWith($buku->cover, 'http')) {
                Storage::disk('public')->delete($buku->cover);
            }
            $cover = $request->file('cover_file')->store('covers', 'public');
        } elseif ($request->filled('cover_url')) {
            $cover = $request->cover_url;
        }

        $buku->update([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul) . '-' . substr($buku->slug, -5),
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'isbn' => $request->isbn,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga ?? 0,
            'stok' => $request->stok,
            'lokasi_rak' => $request->lokasi_rak,
            'deskripsi' => $request->deskripsi,
            'cover' => $cover,
        ]);

        $buku->kategoriRelasi()->sync([$request->kategori_id]);

        return redirect()->route('admin.buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // 1. Cek jika buku sedang dipinjam aktif di perpustakaan
        $adaPeminjamanAktif = $buku->peminjaman()
            ->whereIn('status_peminjaman', ['menunggu', 'dipinjam', 'terlambat'])
            ->exists();

        if ($adaPeminjamanAktif) {
            return back()->with('error', 'Buku tidak dapat dihapus karena sedang dalam status peminjaman aktif perpustakaan.');
        }

        // 2. Cek jika buku masih terkait pesanan pembelian aktif di toko buku online
        $adaPesananAktif = $buku->pesananDetail()
            ->whereHas('pesanan', function ($q) {
                $q->whereIn('status', ['menunggu_pembayaran', 'diproses', 'dikirim']);
            })
            ->exists();

        if ($adaPesananAktif) {
            return back()->with('error', 'Buku tidak dapat dihapus karena masih terkait dengan pesanan pembelian toko buku yang sedang aktif (menunggu pembayaran, diproses, atau dikirim).');
        }

        if ($buku->cover && !Str::startsWith($buku->cover, 'http')) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus dari sistem.');
    }
}

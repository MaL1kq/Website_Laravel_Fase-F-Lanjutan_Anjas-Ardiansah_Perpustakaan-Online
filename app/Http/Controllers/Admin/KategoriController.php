<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriBuku::withCount('buku');

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where('nama_kategori', 'like', $keyword)
                  ->orWhere('deskripsi', 'like', $keyword);
        }

        $kategori = $query->orderBy('nama_kategori')->paginate(10)->withQueryString();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_buku,nama_kategori',
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Kategori "' . $request->nama_kategori . '" sudah ada! Tidak boleh membuat kategori duplikat.',
        ]);

        $slug = Str::slug($request->nama_kategori);
        if (KategoriBuku::where('slug', $slug)->exists()) {
            return back()->withInput()->withErrors([
                'nama_kategori' => 'Kategori dengan nama serupa sudah terdaftar di sistem. Harap gunakan nama kategori lain.'
            ]);
        }

        KategoriBuku::create([
            'nama_kategori' => trim($request->nama_kategori),
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori buku berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBuku::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_buku,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:500',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori "' . $request->nama_kategori . '" sudah digunakan oleh kategori lain! Tidak boleh duplikat.',
        ]);

        $slug = Str::slug($request->nama_kategori);
        if (KategoriBuku::where('slug', $slug)->where('id', '!=', $kategori->id)->exists()) {
            return back()->withInput()->withErrors([
                'nama_kategori' => 'Kategori dengan nama serupa sudah digunakan oleh kategori lain.'
            ]);
        }

        $kategori->update([
            'nama_kategori' => trim($request->nama_kategori),
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = KategoriBuku::withCount('buku')->findOrFail($id);

        if ($kategori->buku_count > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki relasi ke ' . $kategori->buku_count . ' buku.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori buku berhasil dihapus.');
    }
}

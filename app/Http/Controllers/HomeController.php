<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalKategori = KategoriBuku::count();
        $totalPeminjam = User::where('role', 'peminjam')->count();
        $totalTransaksi = Peminjaman::whereIn('status_peminjaman', ['dipinjam', 'dikembalikan'])->count();

        // Buku Terpopuler (berdasarkan rating dan jumlah peminjaman)
        $bukuPopuler = Buku::with(['kategori', 'ulasan'])
            ->withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(6)
            ->get();

        // Buku Terbaru
        $bukuTerbaru = Buku::with('kategori')
            ->latest()
            ->take(8)
            ->get();

        // Kategori dengan jumlah buku
        $kategoriList = KategoriBuku::withCount('buku')
            ->orderBy('nama_kategori')
            ->get();

        return view('home.index', compact(
            'totalBuku',
            'totalKategori',
            'totalPeminjam',
            'totalTransaksi',
            'bukuPopuler',
            'bukuTerbaru',
            'kategoriList'
        ));
    }

    public function katalog(Request $request)
    {
        $query = Buku::with(['kategori', 'ulasan']);

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // Pencarian Kata Kunci
        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                  ->orWhere('penulis', 'like', $keyword)
                  ->orWhere('penerbit', 'like', $keyword)
                  ->orWhere('isbn', 'like', $keyword)
                  ->orWhere('deskripsi', 'like', $keyword);
            });
        }

        // Filter Urutan
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'popular') {
            $query->withCount('peminjaman')->orderBy('peminjaman_count', 'desc');
        } elseif ($sort === 'title_asc') {
            $query->orderBy('judul', 'asc');
        } elseif ($sort === 'title_desc') {
            $query->orderBy('judul', 'desc');
        } else {
            $query->latest();
        }

        $buku = $query->paginate(12)->withQueryString();
        $kategoriList = KategoriBuku::withCount('buku')->orderBy('nama_kategori')->get();

        return view('home.katalog', compact('buku', 'kategoriList'));
    }

    public function detailBuku($slug)
    {
        $buku = Buku::with([
            'kategori',
            'ulasan.user',
            'peminjaman'
        ])->where('slug', $slug)->firstOrFail();

        $bukuTerkait = Buku::where('kategori_id', $buku->kategori_id)
            ->where('id', '!=', $buku->id)
            ->take(4)
            ->get();

        $isFavorit = false;
        $sudahPinjamAktif = false;
        $userUlasan = null;

        if (Auth::check()) {
            $userId = Auth::id();
            $isFavorit = $buku->koleksiPribadi()->where('user_id', $userId)->exists();
            $sudahPinjamAktif = $buku->peminjaman()
                ->where('user_id', $userId)
                ->whereIn('status_peminjaman', ['menunggu', 'dipinjam', 'terlambat'])
                ->exists();
            $userUlasan = $buku->ulasan()->where('user_id', $userId)->first();
        }

        return view('home.detail', compact(
            'buku',
            'bukuTerkait',
            'isFavorit',
            'sudahPinjamAktif',
            'userUlasan'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\User;

class PageController extends Controller
{
    /**
     * Halaman Detail Tentang Kami (About Us)
     * Memenuhi kriteria resmi BNSP BookStore
     */
    public function about()
    {
        $totalBuku = Buku::count();
        $totalKategori = KategoriBuku::count();
        $totalUser = User::where('role', 'peminjam')->count();

        return view('pages.about', compact('totalBuku', 'totalKategori', 'totalUser'));
    }
}

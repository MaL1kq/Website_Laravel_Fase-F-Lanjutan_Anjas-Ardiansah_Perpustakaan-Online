<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KoleksiPribadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoleksiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $koleksi = KoleksiPribadi::with('buku.kategori')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(12);

        return view('koleksi.index', compact('koleksi'));
    }

    public function toggle($bukuId)
    {
        $userId = Auth::id();
        $buku = Buku::findOrFail($bukuId);

        $existing = KoleksiPribadi::where('user_id', $userId)
            ->where('buku_id', $buku->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('info', 'Buku "' . $buku->judul . '" dihapus dari koleksi pribadi Anda.');
        } else {
            KoleksiPribadi::create([
                'user_id' => $userId,
                'buku_id' => $buku->id,
            ]);
            return back()->with('success', 'Buku "' . $buku->judul . '" berhasil ditambahkan ke koleksi favorit!');
        }
    }
}

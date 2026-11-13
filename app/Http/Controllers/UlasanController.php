<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\UlasanBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request, $bukuId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:5|max:1000',
        ], [
            'rating.required' => 'Beri rating bintang (1 sampai 5).',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'ulasan.required' => 'Tulis ulasan Anda tentang buku ini.',
            'ulasan.min' => 'Ulasan minimal 5 karakter.',
            'ulasan.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        $buku = Buku::findOrFail($bukuId);
        $userId = Auth::id();

        UlasanBuku::updateOrCreate(
            [
                'user_id' => $userId,
                'buku_id' => $buku->id,
            ],
            [
                'rating' => $request->rating,
                'ulasan' => $request->ulasan,
            ]
        );

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda telah berhasil disimpan.');
    }

    public function destroy($id)
    {
        $ulasan = UlasanBuku::findOrFail($id);

        if (Auth::user()->isAdminOrPetugas() || $ulasan->user_id === Auth::id()) {
            $ulasan->delete();
            return back()->with('info', 'Ulasan berhasil dihapus.');
        }

        abort(403);
    }
}

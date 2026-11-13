<?php

namespace App\Http\Controllers;

use App\Models\PesanKontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Tampilan Contact to Admin (Halaman Hubungi Kami / Kirim Pesan)
     */
    public function index()
    {
        // Admin tidak perlu mengakses form kontak ke dirinya sendiri
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.pesan.index')
                ->with('info', 'Sebagai Administrator, Anda mengelola dan merespon pesan pengguna melalui panel Pesan Masuk.');
        }

        $riwayatPesan = null;

        if (Auth::check()) {
            $riwayatPesan = PesanKontak::where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('pages.contact', compact('riwayatPesan'));
    }

    /**
     * Kirim Pesan ke Admin (Contact to Admin)
     */
    public function send(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.pesan.index');
        }

        $rules = [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'subjek' => 'required|string|max:150',
            'pesan' => 'required|string|min:5|max:2000',
        ];

        $validated = $request->validate($rules);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'belum_dibaca';

        PesanKontak::create($validated);

        return redirect()->route('contact')
            ->with('success', 'Pesan Anda berhasil dikirim ke Administrator! Kami akan segera meninjau dan membalas pesan Anda.');
    }
}

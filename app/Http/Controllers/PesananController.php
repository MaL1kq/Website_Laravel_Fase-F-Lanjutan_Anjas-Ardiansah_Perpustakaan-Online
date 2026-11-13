<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with(['detail.buku'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pesanan.index', compact('pesanan'));
    }

    public function detail($id)
    {
        $pesanan = Pesanan::with(['detail.buku.kategori', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pesanan.detail', compact('pesanan'));
    }

    public function cetakInvoice($id)
    {
        $pesanan = Pesanan::with(['detail.buku', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pesanan.cetak_invoice', compact('pesanan'));
    }
}

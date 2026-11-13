<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = Keranjang::with('buku.kategori')
            ->where('user_id', Auth::id())
            ->get();

        $totalHarga = $items->sum(function ($item) {
            return ($item->buku->harga ?? 0) * $item->jumlah;
        });

        $totalItem = $items->sum('jumlah');

        return view('cart.index', compact('items', 'totalHarga', 'totalItem'));
    }

    public function add(Request $request, ?Buku $buku = null)
    {
        $bukuId = ($buku && $buku->exists) ? $buku->id : $request->input('buku_id');

        $validator = \Illuminate\Support\Facades\Validator::make(['buku_id' => $bukuId], [
            'buku_id' => 'required|exists:buku,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $buku = $buku && $buku->exists ? $buku : Buku::findOrFail($bukuId);
        $jumlah = max(1, (int) ($request->input('jumlah', 1)));

        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku "' . $buku->judul . '" sedang habis.');
        }

        $keranjang = Keranjang::where('user_id', Auth::id())
            ->where('buku_id', $buku->id)
            ->first();

        if ($keranjang) {
            $totalJumlah = $keranjang->jumlah + $jumlah;
            if ($totalJumlah > $buku->stok) {
                return back()->with('error', 'Jumlah dalam keranjang melebihi stok yang tersedia (Maks: ' . $buku->stok . ').');
            }
            $keranjang->update(['jumlah' => $totalJumlah]);
        } else {
            if ($jumlah > $buku->stok) {
                return back()->with('error', 'Jumlah yang ingin dibeli melebihi stok tersedia (Maks: ' . $buku->stok . ').');
            }
            Keranjang::create([
                'user_id' => Auth::id(),
                'buku_id' => $buku->id,
                'jumlah' => $jumlah,
            ]);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Buku "' . $buku->judul . '" berhasil dimasukkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $item = Keranjang::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $buku = $item->buku;
        $aksi = $request->input('action') ?? $request->input('aksi');

        if ($aksi === 'plus' || $aksi === 'tambah') {
            if ($item->jumlah + 1 > $buku->stok) {
                return redirect()->route('cart.index')->with('error', 'Stok buku tidak mencukupi untuk penambahan kuantitas.');
            }
            $item->increment('jumlah');
        } elseif ($aksi === 'minus' || $aksi === 'kurang') {
            if ($item->jumlah > 1) {
                $item->decrement('jumlah');
            } else {
                $item->delete();
                return redirect()->route('cart.index')->with('success', 'Item buku telah dihapus dari keranjang.');
            }
        } elseif ($request->filled('jumlah')) {
            $newJumlah = max(1, (int) $request->jumlah);
            if ($newJumlah > $buku->stok) {
                return redirect()->route('cart.index')->with('error', 'Kuantitas melebihi stok yang tersedia.');
            }
            $item->update(['jumlah' => $newJumlah]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove($id)
    {
        $item = Keranjang::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        Keranjang::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')->with('success', 'Seluruh isi keranjang belanja berhasil dikosongkan.');
    }
}

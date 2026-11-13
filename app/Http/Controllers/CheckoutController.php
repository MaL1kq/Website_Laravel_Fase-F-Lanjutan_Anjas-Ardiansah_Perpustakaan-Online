<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = Keranjang::with('buku.kategori')
            ->where('user_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Keranjang belanja Anda masih kosong. Silakan pilih buku terlebih dahulu.');
        }

        $totalHarga = $items->sum(function ($item) {
            return ($item->buku->harga ?? 0) * $item->jumlah;
        });

        $totalItem = $items->sum('jumlah');

        return view('checkout.index', compact('items', 'totalHarga', 'totalItem', 'user'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:30',
            'alamat_pengiriman' => 'required|string|max:1000',
            'metode_pembayaran' => 'required|string',
            'catatan' => 'nullable|string|max:500',
        ], [
            'nama_penerima.required' => 'Nama penerima pesanan wajib diisi.',
            'no_telepon.required' => 'Nomor telepon/WhatsApp aktif wajib diisi.',
            'alamat_pengiriman.required' => 'Alamat lengkap pengiriman buku wajib diisi.',
            'metode_pembayaran.required' => 'Pilih salah satu metode pembayaran.',
        ]);

        $user = Auth::user();
        $items = Keranjang::with('buku')
            ->where('user_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Cek stok buku sebelum proses
        foreach ($items as $item) {
            if ($item->jumlah > $item->buku->stok) {
                return redirect()->route('cart.index')->with('error', 'Stok buku "' . $item->buku->judul . '" tidak mencukupi (Tersisa: ' . $item->buku->stok . ').');
            }
        }

        $totalHarga = $items->sum(function ($item) {
            return ($item->buku->harga ?? 0) * $item->jumlah;
        });

        DB::beginTransaction();
        try {
            // Generate Kode Pesanan Unik
            $kodePesanan = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $pesanan = Pesanan::create([
                'kode_pesanan' => $kodePesanan,
                'user_id' => $user->id,
                'total_harga' => $totalHarga,
                'status' => 'menunggu_pembayaran',
                'metode_pembayaran' => $request->metode_pembayaran,
                'nama_penerima' => $request->nama_penerima,
                'no_telepon' => $request->no_telepon,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'catatan' => $request->catatan,
            ]);

            foreach ($items as $item) {
                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'buku_id' => $item->buku_id,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->buku->harga ?? 0,
                    'subtotal' => ($item->buku->harga ?? 0) * $item->jumlah,
                ]);

                // Kurangi stok buku
                $item->buku->decrement('stok', $item->jumlah);
            }

            // Hapus isi keranjang setelah sukses
            Keranjang::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('pesanan.detail', $pesanan->id)->with('success', 'Pesanan pembelian buku berhasil dibuat dengan nomor invoice ' . $kodePesanan . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }
}

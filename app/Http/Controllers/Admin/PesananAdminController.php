<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['user', 'detail.buku']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_pesanan', 'like', $keyword)
                  ->orWhere('nama_penerima', 'like', $keyword)
                  ->orWhere('no_telepon', 'like', $keyword);
            });
        }

        $pesanan = $query->latest()->paginate(10)->withQueryString();

        $countMenunggu = Pesanan::where('status', 'menunggu_pembayaran')->count();
        $countDiproses = Pesanan::where('status', 'diproses')->count();
        $countDikirim = Pesanan::where('status', 'dikirim')->count();
        $countSelesai = Pesanan::where('status', 'selesai')->count();

        return view('admin.pesanan.index', compact(
            'pesanan',
            'countMenunggu',
            'countDiproses',
            'countDikirim',
            'countSelesai'
        ));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'detail.buku'])->findOrFail($id);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,diproses,dikirim,selesai,dibatalkan',
            'status_pembayaran' => 'nullable|in:belum_dibayar,sudah_dibayar',
        ]);

        $pesanan = Pesanan::with('detail.buku')->findOrFail($id);
        $oldStatus = $pesanan->status;
        $newStatus = $request->status;

        // Jika dibatalkan dari status aktif, kembalikan stok buku
        if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
            foreach ($pesanan->detail as $item) {
                if ($item->buku) {
                    $item->buku->increment('stok', $item->jumlah);
                }
            }
        }

        // Jika dipulihkan dari dibatalkan kembali ke aktif, kurangi lagi stok
        if ($oldStatus === 'dibatalkan' && $newStatus !== 'dibatalkan') {
            foreach ($pesanan->detail as $item) {
                if ($item->buku) {
                    $item->buku->decrement('stok', $item->jumlah);
                }
            }
        }

        $updateData = ['status' => $newStatus];

        if ($request->filled('status_pembayaran')) {
            $updateData['status_pembayaran'] = $request->status_pembayaran;
            if ($request->status_pembayaran === 'sudah_dibayar' && !$pesanan->tanggal_pembayaran) {
                $updateData['tanggal_pembayaran'] = now();
            } elseif ($request->status_pembayaran === 'belum_dibayar') {
                $updateData['tanggal_pembayaran'] = null;
            }
        }

        $pesanan->update($updateData);

        return back()->with('success', 'Status pesanan ' . $pesanan->kode_pesanan . ' dan status pembayaran berhasil diperbarui.');
    }

    public function cetak($id)
    {
        $pesanan = Pesanan::with(['user', 'detail.buku'])->findOrFail($id);

        return view('pesanan.cetak_invoice', compact('pesanan'));
    }
}

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
        $newPaymentStatus = $request->input('status_pembayaran', $pesanan->status_pembayaran ?? 'belum_dibayar');

        // Proteksi 1: Pesanan tidak dapat dikirim atau diselesaikan jika pembayaran belum diterima (belum lunas)
        if (in_array($newStatus, ['dikirim', 'selesai']) && $newPaymentStatus !== 'sudah_dibayar') {
            return back()->with('error', 'Pesanan tidak dapat diubah ke status ' . ($newStatus === 'dikirim' ? 'Dalam Pengiriman' : 'Selesai') . ' karena pembayaran belum diterima (belum lunas). Silakan verifikasi penerimaan pembayaran terlebih dahulu.');
        }

        // Proteksi 2: Tidak dapat membatalkan status pembayaran (menjadi Belum Lunas) jika pesanan sedang dikirim atau sudah selesai
        if ($newPaymentStatus === 'belum_dibayar' && in_array($newStatus, ['dikirim', 'selesai'])) {
            return back()->with('error', 'Status pembayaran tidak dapat diubah ke Belum Lunas selama status pesanan masih Dalam Pengiriman atau Selesai.');
        }

        // Proteksi 3: Sinkronisasi cerdas jika pembayaran lunas tapi status masih menunggu_pembayaran
        if ($newPaymentStatus === 'sudah_dibayar' && $newStatus === 'menunggu_pembayaran') {
            $newStatus = 'diproses';
        }

        // Proteksi 4: Cek ketersediaan stok saat memulihkan pesanan dari status dibatalkan
        if ($oldStatus === 'dibatalkan' && $newStatus !== 'dibatalkan') {
            foreach ($pesanan->detail as $item) {
                if ($item->buku && $item->buku->stok < $item->jumlah) {
                    return back()->with('error', 'Gagal memulihkan pesanan: Stok buku "' . $item->buku->judul . '" tidak mencukupi (stok tersisa: ' . $item->buku->stok . ', dibutuhkan: ' . $item->jumlah . ').');
                }
            }
            foreach ($pesanan->detail as $item) {
                if ($item->buku) {
                    $item->buku->decrement('stok', $item->jumlah);
                }
            }
        }

        // Jika dibatalkan dari status aktif, kembalikan stok buku
        if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
            foreach ($pesanan->detail as $item) {
                if ($item->buku) {
                    $item->buku->increment('stok', $item->jumlah);
                }
            }
        }

        $updateData = [
            'status' => $newStatus,
            'status_pembayaran' => $newPaymentStatus,
        ];

        if ($newPaymentStatus === 'sudah_dibayar') {
            if (!$pesanan->tanggal_pembayaran) {
                $updateData['tanggal_pembayaran'] = now();
            }
        } else {
            $updateData['tanggal_pembayaran'] = null;
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

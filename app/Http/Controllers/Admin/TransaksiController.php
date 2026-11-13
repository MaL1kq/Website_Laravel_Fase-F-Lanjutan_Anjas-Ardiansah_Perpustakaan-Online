<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku.kategori', 'petugas']);

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status_peminjaman', $request->status);
        }

        // Pencarian
        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_transaksi', 'like', $keyword)
                  ->orWhereHas('user', function ($qu) use ($keyword) {
                      $qu->where('name', 'like', $keyword)
                         ->orWhere('username', 'like', $keyword)
                         ->orWhere('email', 'like', $keyword);
                  })
                  ->orWhereHas('buku', function ($qb) use ($keyword) {
                      $qb->where('judul', 'like', $keyword);
                  });
            });
        }

        $transaksi = $query->latest()->paginate(15)->withQueryString();

        // Update status terlambat & denda untuk peminjaman aktif yang melewati batas waktu
        foreach ($transaksi as $item) {
            if (in_array($item->status_peminjaman, ['dipinjam', 'terlambat'])) {
                $dendaBaru = $item->hitungDenda();
                if ($dendaBaru > 0 && $item->status_peminjaman !== 'terlambat') {
                    $item->update([
                        'status_peminjaman' => 'terlambat',
                        'denda' => $dendaBaru,
                    ]);
                } elseif ($dendaBaru > 0) {
                    $item->update(['denda' => $dendaBaru]);
                }
            }
        }

        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function konfirmasi($id)
    {
        $transaksi = Peminjaman::with('buku')->findOrFail($id);

        if ($transaksi->status_peminjaman !== 'menunggu') {
            return back()->with('error', 'Transaksi ini tidak dalam status menunggu konfirmasi.');
        }

        if ($transaksi->buku->stok <= 0) {
            return back()->with('error', 'Stok buku tidak mencukupi untuk disetujui.');
        }

        // Kurangi stok buku
        $transaksi->buku->decrement('stok');

        // Update status ke dipinjam
        $transaksi->update([
            'status_peminjaman' => 'dipinjam',
            'admin_id' => Auth::id(),
            'tanggal_peminjaman' => Carbon::now()->toDateString(),
            'tanggal_pengembalian' => Carbon::now()->addDays(7)->toDateString(),
        ]);

        return back()->with('success', 'Peminjaman berhasil dikonfirmasi! Buku resmi dipinjamkan kepada ' . $transaksi->user->name);
    }

    public function tolak(Request $request, $id)
    {
        $transaksi = Peminjaman::findOrFail($id);

        if ($transaksi->status_peminjaman !== 'menunggu') {
            return back()->with('error', 'Hanya peminjaman dengan status menunggu yang dapat ditolak.');
        }

        $transaksi->update([
            'status_peminjaman' => 'ditolak',
            'catatan' => $request->input('catatan', 'Pengajuan ditolak oleh administrator perpustakaan.'),
            'admin_id' => Auth::id(),
        ]);

        return back()->with('info', 'Pengajuan peminjaman telah ditolak.');
    }

    public function kembalikan(Request $request, $id)
    {
        $transaksi = Peminjaman::with('buku')->findOrFail($id);

        if (!in_array($transaksi->status_peminjaman, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Transaksi ini sudah selesai atau tidak aktif.');
        }

        $tglSekarang = Carbon::now();
        $batasKembali = Carbon::parse($transaksi->tanggal_pengembalian);
        $denda = 0;

        if ($tglSekarang->greaterThan($batasKembali)) {
            $hariTelat = (int) $batasKembali->diffInDays($tglSekarang, false);
            if ($hariTelat > 0) {
                $denda = $hariTelat * 1000;
            }
        }

        // Kembalikan stok buku
        $transaksi->buku->increment('stok');

        $transaksi->update([
            'status_peminjaman' => 'dikembalikan',
            'tanggal_dikembalikan' => $tglSekarang->toDateString(),
            'denda' => $denda,
            'admin_id' => Auth::id(),
            'catatan' => $request->input('catatan', 'Buku telah dikembalikan dan diverifikasi oleh administrator.'),
        ]);

        $pesan = 'Buku berhasil dikembalikan!';
        if ($denda > 0) {
            $pesan .= ' Denda keterlambatan sebesar Rp ' . number_format($denda, 0, ',', '.') . ' dicatat ke sistem.';
        }

        return back()->with('success', $pesan);
    }
}

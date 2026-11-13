<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PeminjamanController extends Controller
{
    /**
     * Ajukan peminjaman buku.
     *
     * @param Request $request
     * @param int|string $bukuId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ajukanPinjam(Request $request, $bukuId)
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Validasi role
        if (!$user->isPeminjam()) {
            return back()->with('error', 'Hanya anggota/siswa yang dapat meminjam buku.');
        }

        $buku = Buku::findOrFail($bukuId);

        // 2. Cek stok buku
        if ($buku->stok <= 0) {
            return back()->with('error', 'Mohon maaf, stok buku ini sedang habis.');
        }

        // 3. Cek apakah user sedang meminjam buku ini yang belum dikembalikan
        $pinjamAktifBukuIni = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status_peminjaman', ['menunggu', 'dipinjam', 'terlambat'])
            ->exists();

        if ($pinjamAktifBukuIni) {
            return back()->with('error', 'Anda masih memiliki peminjaman aktif untuk buku ini.');
        }

        // 4. Batasi maksimal 3 peminjaman aktif sekaligus per anggota
        $totalPinjamAktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status_peminjaman', ['menunggu', 'dipinjam', 'terlambat'])
            ->count();

        if ($totalPinjamAktif >= 3) {
            return back()->with('error', 'Batas maksimal peminjaman aktif adalah 3 buku. Harap kembalikan buku sebelumnya.');
        }

        // 5. Validasi durasi peminjaman (default 7 hari, max 14 hari)
        $durasiHari = (int) $request->input('durasi_hari', 7);
        if ($durasiHari < 1 || $durasiHari > 14) {
            $durasiHari = 7;
        }

        $tglPinjam = Carbon::now()->toDateString();
        $tglKembali = Carbon::now()->addDays($durasiHari)->toDateString();

        // Generate Kode Transaksi unik: TRX-YYYYMMDD-RANDOM
        $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        Peminjaman::create([
            'kode_transaksi' => $kodeTransaksi,
            'user_id' => $user->id,
            'buku_id' => $buku->id,
            'tanggal_peminjaman' => $tglPinjam,
            'tanggal_pengembalian' => $tglKembali,
            'tanggal_dikembalikan' => null,
            'status_peminjaman' => 'menunggu',
            'denda' => 0,
            'catatan' => $request->input('catatan', 'Pengajuan peminjaman online.'),
            'admin_id' => null,
        ]);

        return redirect()->route('peminjaman.riwayat')
            ->with('success', 'Permintaan peminjaman buku berhasil diajukan! Menunggu verifikasi administrator.');
    }

    public function riwayatPinjam(Request $request)
    {
        $userId = Auth::id();
        $statusFilter = $request->input('status');

        $query = Peminjaman::with(['buku.kategori', 'admin'])
            ->where('user_id', $userId);

        if ($statusFilter && in_array($statusFilter, ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat'])) {
            $query->where('status_peminjaman', $statusFilter);
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        // Hitung denda real-time untuk peminjaman aktif yang terlambat
        foreach ($peminjaman as $item) {
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

        return view('peminjaman.riwayat', compact('peminjaman', 'statusFilter'));
    }

    /**
     * Cetak bukti peminjaman.
     *
     * @param int|string $id
     * @return \Illuminate\View\View
     */
    public function cetakBukti($id)
    {
        $peminjaman = Peminjaman::with(['user', 'buku.kategori', 'admin'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('peminjaman.cetak_bukti', compact('peminjaman'));
    }
}

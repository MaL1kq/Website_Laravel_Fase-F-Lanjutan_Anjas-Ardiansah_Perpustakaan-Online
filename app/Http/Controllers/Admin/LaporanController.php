<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->toDateString());
        $status = $request->input('status');

        $query = Peminjaman::with(['user', 'buku.kategori', 'admin'])
            ->whereBetween('tanggal_peminjaman', [$tglMulai, $tglSelesai]);

        if ($status && in_array($status, ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat'])) {
            $query->where('status_peminjaman', $status);
        }

        $transaksi = $query->latest()->get();

        // Ringkasan Statistik Laporan
        $totalPinjam = $transaksi->count();
        $totalSelesai = $transaksi->where('status_peminjaman', 'dikembalikan')->count();
        $totalAktif = $transaksi->whereIn('status_peminjaman', ['dipinjam', 'terlambat'])->count();
        $totalDenda = $transaksi->sum('denda');

        return view('admin.laporan.index', compact(
            'transaksi',
            'tglMulai',
            'tglSelesai',
            'status',
            'totalPinjam',
            'totalSelesai',
            'totalAktif',
            'totalDenda'
        ));
    }

    public function cetak(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->toDateString());
        $status = $request->input('status');

        $query = Peminjaman::with(['user', 'buku.kategori', 'admin'])
            ->whereBetween('tanggal_peminjaman', [$tglMulai, $tglSelesai]);

        if ($status && in_array($status, ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat'])) {
            $query->where('status_peminjaman', $status);
        }

        $transaksi = $query->oldest('tanggal_peminjaman')->get();

        $totalPinjam = $transaksi->count();
        $totalSelesai = $transaksi->where('status_peminjaman', 'dikembalikan')->count();
        $totalAktif = $transaksi->whereIn('status_peminjaman', ['dipinjam', 'terlambat'])->count();
        $totalDenda = $transaksi->sum('denda');

        return view('admin.laporan.cetak', compact(
            'transaksi',
            'tglMulai',
            'tglSelesai',
            'status',
            'totalPinjam',
            'totalSelesai',
            'totalAktif',
            'totalDenda'
        ));
    }

    public function penjualan(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->toDateString());
        $status = $request->input('status');

        $query = Pesanan::with(['user', 'detail.buku'])
            ->whereBetween('created_at', [$tglMulai . ' 00:00:00', $tglSelesai . ' 23:59:59']);

        if ($status) {
            $query->where('status', $status);
        }

        $pesanan = $query->latest()->get();

        $totalOmset = $pesanan->where('status', '!=', 'dibatalkan')->sum('total_harga');
        $totalBukuTerjual = $pesanan->where('status', '!=', 'dibatalkan')->sum(function ($p) {
            return $p->detail->sum('jumlah');
        });
        $totalTransaksi = $pesanan->count();

        return view('admin.laporan.penjualan', compact(
            'pesanan',
            'tglMulai',
            'tglSelesai',
            'status',
            'totalOmset',
            'totalBukuTerjual',
            'totalTransaksi'
        ));
    }

    public function cetakPenjualan(Request $request)
    {
        $tglMulai = $request->input('tgl_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tglSelesai = $request->input('tgl_selesai', Carbon::now()->toDateString());
        $status = $request->input('status');

        $query = Pesanan::with(['user', 'detail.buku'])
            ->whereBetween('created_at', [$tglMulai . ' 00:00:00', $tglSelesai . ' 23:59:59']);

        if ($status) {
            $query->where('status', $status);
        }

        $pesanan = $query->oldest('created_at')->get();

        $totalOmset = $pesanan->where('status', '!=', 'dibatalkan')->sum('total_harga');
        $totalBukuTerjual = $pesanan->where('status', '!=', 'dibatalkan')->sum(function ($p) {
            return $p->detail->sum('jumlah');
        });
        $totalTransaksi = $pesanan->count();

        return view('admin.laporan.cetak_penjualan', compact(
            'pesanan',
            'tglMulai',
            'tglSelesai',
            'status',
            'totalOmset',
            'totalBukuTerjual',
            'totalTransaksi'
        ));
    }
}

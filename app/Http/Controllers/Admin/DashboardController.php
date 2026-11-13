<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use App\Models\PesanKontak;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalKategori = KategoriBuku::count();
        $totalAnggota = User::where('role', 'peminjam')->count();
        $totalAdmin = User::where('role', 'admin')->count();

        // Metrik Penjualan Buku
        $totalPenjualan = Pesanan::where('status', '!=', 'dibatalkan')->sum('total_harga');
        $totalPesanan = Pesanan::count();
        $pesananMenunggu = Pesanan::where('status', 'menunggu_pembayaran')->count();
        $pesananTerbaru = Pesanan::with(['user', 'detail.buku'])->latest()->take(5)->get();

        // Metrik Peminjaman
        $pinjamMenunggu = Peminjaman::where('status_peminjaman', 'menunggu')->count();
        $pinjamAktif = Peminjaman::where('status_peminjaman', 'dipinjam')->count();
        $pinjamTerlambat = Peminjaman::where('status_peminjaman', 'terlambat')->count();
        $pinjamSelesai = Peminjaman::where('status_peminjaman', 'dikembalikan')->count();
        $totalDenda = Peminjaman::sum('denda');

        // Transaksi peminjaman terbaru
        $transaksiTerbaru = Peminjaman::with(['user', 'buku.kategori', 'admin'])
            ->latest()
            ->take(6)
            ->get();

        // Buku dengan stok menipis (<= 2)
        $bukuStokMenipis = Buku::where('stok', '<=', 2)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        // Metrik Pesan Masuk (Contact to Admin)
        $totalPesanBaru = PesanKontak::where('status', 'belum_dibaca')->count();
        $pesanTerbaru = PesanKontak::latest()->take(4)->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalKategori',
            'totalAnggota',
            'totalAdmin',
            'totalPenjualan',
            'totalPesanan',
            'pesananMenunggu',
            'pesananTerbaru',
            'pinjamMenunggu',
            'pinjamAktif',
            'pinjamTerlambat',
            'pinjamSelesai',
            'totalDenda',
            'transaksiTerbaru',
            'bukuStokMenipis',
            'totalPesanBaru',
            'pesanTerbaru'
        ));
    }
}

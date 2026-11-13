<?php

use App\Http\Controllers\Admin\BukuController as AdminBukuController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PesanAdminController as AdminPesanController;
use App\Http\Controllers\Admin\PesananAdminController as AdminPesananController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/buku/{slug}', [HomeController::class, 'detailBuku'])->name('buku.detail');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Auth Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Shortcut / Alias URL untuk Pengguna / Siswa (Peminjam)
Route::match(['get', 'head'], '/user', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Route::match(['get', 'head'], '/user/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Peminjam / Anggota Routes (Library + Bookstore Cart & Orders)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:peminjam'])->group(function () {
    // Fitur Peminjaman Perpustakaan
    Route::post('/buku/{id}/pinjam', [PeminjamanController::class, 'ajukanPinjam'])->name('peminjaman.ajukan');
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatPinjam'])->name('peminjaman.riwayat');
    Route::get('/peminjaman/{id}/cetak-bukti', [PeminjamanController::class, 'cetakBukti'])->name('peminjaman.cetak');

    // Koleksi Pribadi / Favorit & Ulasan
    Route::get('/koleksi-pribadi', [KoleksiController::class, 'index'])->name('koleksi.index');
    Route::post('/koleksi/{bukuId}/toggle', [KoleksiController::class, 'toggle'])->name('koleksi.toggle');
    Route::post('/buku/{bukuId}/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

    // Fitur Keranjang Belanja (Cart)
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{buku?}', [CartController::class, 'add'])->name('cart.add');
    Route::match(['post', 'patch'], '/keranjang/{id}/update', [CartController::class, 'update'])->name('cart.update');
    Route::match(['delete', 'post'], '/keranjang/{id}/hapus', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/keranjang/{id}', [CartController::class, 'remove'])->name('cart.destroy');
    Route::post('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

    // Fitur Checkout Pembelian Buku
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/proses', [CheckoutController::class, 'process'])->name('checkout.proses');

    // Riwayat & Invoice Pesanan Anggota
    Route::get('/pesanan-saya', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [PesananController::class, 'detail'])->name('pesanan.detail');
    Route::get('/pesanan/{id}/cetak-invoice', [PesananController::class, 'cetakInvoice'])->name('pesanan.cetak');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Murni 1 Role Administrator)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kategori
    Route::resource('kategori', AdminKategoriController::class)->except(['create', 'edit', 'show']);

    // Manajemen Buku (CRUD dengan Harga)
    Route::resource('buku', AdminBukuController::class)->except(['show']);

    // Manajemen Transaksi Peminjaman Perpustakaan
    Route::get('/transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{id}/konfirmasi', [AdminTransaksiController::class, 'konfirmasi'])->name('transaksi.konfirmasi');
    Route::post('/transaksi/{id}/tolak', [AdminTransaksiController::class, 'tolak'])->name('transaksi.tolak');
    Route::post('/transaksi/{id}/kembalikan', [AdminTransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');

    // Manajemen Pesanan Pembelian Buku
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{id}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::match(['post', 'patch'], '/pesanan/{id}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.status');
    Route::patch('/pesanan/{id}/update-status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.update-status');
    Route::get('/pesanan/{id}/cetak', [AdminPesananController::class, 'cetak'])->name('pesanan.cetak');

    // Laporan Peminjaman
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [AdminLaporanController::class, 'cetak'])->name('laporan.cetak');

    // Laporan Penjualan Buku
    Route::get('/laporan-penjualan', [AdminLaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan-penjualan/cetak', [AdminLaporanController::class, 'cetakPenjualan'])->name('laporan.penjualan.cetak');

    // Manajemen Pengguna (Admin & Anggota)
    Route::resource('users', AdminUserController::class)->except(['create', 'edit', 'show']);

    // Manajemen Pesan Masuk (Contact to Admin)
    Route::get('/pesan', [AdminPesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/{id}', [AdminPesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan/{id}/balas', [AdminPesanController::class, 'reply'])->name('pesan.reply');
    Route::delete('/pesan/{id}', [AdminPesanController::class, 'destroy'])->name('pesan.destroy');
});

/*
|--------------------------------------------------------------------------
| Global Fallback Route (Proteksi Seluruh URL Not Found / 404)
| Mencegah halaman 404 muncul saat diuji atau salah ketik URL
|--------------------------------------------------------------------------
*/
Route::fallback(function (\Illuminate\Http\Request $request) {
    $path = trim($request->path(), '/');

    // 1. Jika URL diawali dengan kata kunci admin / petugas / staff
    if (str_starts_with($path, 'admin') || str_starts_with($path, 'petugas') || str_starts_with($path, 'staff')) {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('info', 'Alamat tidak ditemukan. Anda telah dialihkan kembali ke Dashboard Admin.');
        }
        return redirect()->route('login')->with('info', 'Silakan masuk ke akun Administrator terlebih dahulu.');
    }

    // 2. Jika URL terkait katalog atau buku
    if (in_array($path, ['buku', 'books', 'book', 'koleksi', 'catalog', 'katalog-buku'])) {
        return redirect()->route('katalog');
    }

    // 3. Jika URL terkait keranjang atau pesanan
    if (in_array($path, ['cart', 'keranjang', 'keranjang-belanja'])) {
        return redirect()->route('cart.index');
    }
    if (in_array($path, ['pesanan', 'order', 'orders', 'pesanan-saya'])) {
        return redirect()->route('pesanan.index');
    }

    // 4. Jika URL terkait kontak / help / support
    if (in_array($path, ['contact-us', 'kontak', 'hubungi-kami', 'bantuan', 'support'])) {
        return redirect()->route('contact');
    }

    // 5. Jika URL terkait tentang / profil
    if (in_array($path, ['tentang', 'tentang-kami', 'about-us', 'profil'])) {
        return redirect()->route('about');
    }

    // 6. Jika user sedang login
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('info', 'Halaman tidak ditemukan. Anda telah dialihkan kembali ke Dashboard Admin.');
        }
        return redirect()->route('home')->with('info', 'Halaman tidak ditemukan. Anda telah dialihkan kembali ke Beranda.');
    }

    // 7. Pengunjung Umum (Guest)
    return redirect()->route('home')->with('info', 'Halaman yang Anda tuju tidak ditemukan. Anda telah dialihkan ke Beranda.');
});

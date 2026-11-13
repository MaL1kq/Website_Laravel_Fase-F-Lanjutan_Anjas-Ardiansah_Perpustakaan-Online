<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Keranjang;
use App\Models\Peminjaman;
use App\Models\PesanKontak;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $peminjam;
    private KategoriBuku $kategori;
    private Buku $buku;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->peminjam = User::create([
            'name' => 'Peminjam Test',
            'username' => 'peminjamtest',
            'email' => 'peminjam@test.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
        ]);

        $this->kategori = KategoriBuku::create([
            'nama_kategori' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        $this->buku = Buku::create([
            'judul' => 'Belajar Laravel Modern',
            'slug' => 'belajar-laravel-modern',
            'penulis' => 'Penulis Hebat',
            'penerbit' => 'Penerbit Pustaka',
            'tahun_terbit' => 2025,
            'kategori_id' => $this->kategori->id,
            'stok' => 5,
            'harga' => 85000,
        ]);
    }

    public function test_halaman_beranda_dan_katalog_dapat_diakses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('PerpusDigital');

        $responseKatalog = $this->get('/katalog');
        $responseKatalog->assertStatus(200);
        $responseKatalog->assertSee('Belajar Laravel Modern');
    }

    public function test_login_pengguna_berhasil_dan_redirect_sesuai_role(): void
    {
        // 1. Login Admin -> redirect ke admin.dashboard
        $resAdmin = $this->post('/login', [
            'login' => 'admintest',
            'password' => 'password',
        ]);
        $resAdmin->assertRedirect(route('admin.dashboard'));

        $this->post('/logout');

        // 2. Login Peminjam (Siswa) -> redirect ke beranda
        $resUser = $this->post('/login', [
            'login' => 'peminjamtest',
            'password' => 'password',
        ]);
        $resUser->assertRedirect(route('home'));
    }

    public function test_anggota_dapat_mengajukan_pinjaman_buku(): void
    {
        $this->actingAs($this->peminjam);

        $response = $this->post(route('peminjaman.ajukan', $this->buku->id), [
            'durasi_hari' => 7,
            'catatan' => 'Untuk bahan ujian',
        ]);

        $response->assertRedirect(route('peminjaman.riwayat'));
        $this->assertDatabaseHas('peminjaman', [
            'user_id' => $this->peminjam->id,
            'buku_id' => $this->buku->id,
            'status_peminjaman' => 'menunggu',
        ]);
    }

    public function test_admin_dapat_menyetujui_dan_memproses_pengembalian(): void
    {
        // 1. Buat peminjaman status menunggu
        $peminjaman = Peminjaman::create([
            'kode_transaksi' => 'TRX-TEST-001',
            'user_id' => $this->peminjam->id,
            'buku_id' => $this->buku->id,
            'tanggal_peminjaman' => Carbon::now()->toDateString(),
            'tanggal_pengembalian' => Carbon::now()->addDays(7)->toDateString(),
            'status_peminjaman' => 'menunggu',
        ]);

        $this->actingAs($this->admin);

        // 2. Setujui peminjaman (stok buku berkurang dari 5 jadi 4)
        $resKonfirmasi = $this->post(route('admin.transaksi.konfirmasi', $peminjaman->id));
        $resKonfirmasi->assertSessionHas('success');

        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status_peminjaman' => 'dipinjam',
            'admin_id' => $this->admin->id,
        ]);
        $this->assertEquals(4, $this->buku->fresh()->stok);

        // 3. Proses pengembalian buku (stok kembali menjadi 5)
        $resKembalikan = $this->post(route('admin.transaksi.kembalikan', $peminjaman->id));
        $resKembalikan->assertSessionHas('success');

        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status_peminjaman' => 'dikembalikan',
        ]);
        $this->assertEquals(5, $this->buku->fresh()->stok);
    }

    public function test_anggota_dapat_menambah_dan_mengubah_keranjang_buku(): void
    {
        $this->actingAs($this->peminjam);

        // Tambah buku ke keranjang
        $response = $this->post(route('cart.add', $this->buku->id), [
            'buku_id' => $this->buku->id,
            'jumlah' => 2,
        ]);
        $response->assertRedirect(route('cart.index'));

        $this->assertDatabaseHas('keranjang', [
            'user_id' => $this->peminjam->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 2,
        ]);

        $itemKeranjang = Keranjang::where('user_id', $this->peminjam->id)->first();

        // Update jumlah item keranjang (+1)
        $resUpdate = $this->patch(route('cart.update', $itemKeranjang->id), [
            'aksi' => 'tambah',
        ]);
        $resUpdate->assertRedirect(route('cart.index'));
        $this->assertEquals(3, $itemKeranjang->fresh()->jumlah);

        // Hapus item dari keranjang
        $resHapus = $this->delete(route('cart.destroy', $itemKeranjang->id));
        $resHapus->assertRedirect(route('cart.index'));
        $this->assertDatabaseMissing('keranjang', ['id' => $itemKeranjang->id]);
    }

    public function test_anggota_dapat_checkout_dan_membuat_pesanan_buku(): void
    {
        $this->actingAs($this->peminjam);

        // Isi keranjang 2 pcs
        Keranjang::create([
            'user_id' => $this->peminjam->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 2,
        ]);

        // Proses checkout
        $response = $this->post(route('checkout.proses'), [
            'nama_penerima' => 'Budi Siswa',
            'no_telepon' => '081234567890',
            'alamat_pengiriman' => 'Jl. Pendidikan No. 12, Jakarta',
            'metode_pembayaran' => 'qris',
            'catatan' => 'Buku untuk persiapan ujian',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseEmpty('keranjang');

        // Pastikan pesanan dan detail tercipta dengan harga Rp 170.000 (2 x Rp 85.000)
        $this->assertDatabaseHas('pesanan', [
            'user_id' => $this->peminjam->id,
            'nama_penerima' => 'Budi Siswa',
            'metode_pembayaran' => 'qris',
            'total_harga' => 170000,
        ]);

        // Pastikan stok berkurang dari 5 menjadi 3
        $this->assertEquals(3, $this->buku->fresh()->stok);
    }

    public function test_admin_dapat_memperbarui_status_pesanan(): void
    {
        $pesanan = Pesanan::create([
            'kode_pesanan' => 'INV-TEST-0001',
            'user_id' => $this->peminjam->id,
            'nama_penerima' => 'Budi Siswa',
            'no_telepon' => '081234567890',
            'alamat_pengiriman' => 'Jl. Raya No. 10',
            'metode_pembayaran' => 'transfer_bca',
            'total_harga' => 85000,
            'status' => 'menunggu_pembayaran',
        ]);

        $this->actingAs($this->admin);

        // Ubah status jadi diproses
        $response = $this->patch(route('admin.pesanan.update-status', $pesanan->id), [
            'status' => 'diproses',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pesanan', [
            'id' => $pesanan->id,
            'status' => 'diproses',
        ]);
    }

    public function test_user_dapat_melihat_detail_halaman_about_us(): void
    {
        $response = $this->get(route('about'));
        $response->assertStatus(200);
        $response->assertSee('Detail Halaman About Us');
        $response->assertSee('PerpusDigital BookStore');
    }

    public function test_user_dapat_mengirim_pesan_contact_to_admin(): void
    {
        $this->actingAs($this->peminjam);

        $response = $this->post(route('contact.send'), [
            'nama' => 'Budi Siswa',
            'email' => 'budi@test.com',
            'no_telepon' => '08123456789',
            'subjek' => 'Konfirmasi Pembayaran (Payment at Delivery)',
            'pesan' => 'Saya sudah bayar pesanan buku, mohon diproses segera ya admin.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pesan_kontak', [
            'user_id' => $this->peminjam->id,
            'email' => 'budi@test.com',
            'subjek' => 'Konfirmasi Pembayaran (Payment at Delivery)',
            'status' => 'belum_dibaca',
        ]);
    }

    public function test_admin_dapat_melihat_dan_membalas_pesan_contact_to_admin(): void
    {
        $pesan = PesanKontak::create([
            'user_id' => $this->peminjam->id,
            'nama' => 'Budi Siswa',
            'email' => 'budi@test.com',
            'subjek' => 'Pertanyaan Pengiriman',
            'pesan' => 'Apakah buku bisa dikirim hari ini?',
            'status' => 'belum_dibaca',
        ]);

        $this->actingAs($this->admin);

        // 1. Admin membuka detail pesan (otomatis status menjadi 'dibaca')
        $resShow = $this->get(route('admin.pesan.show', $pesan->id));
        $resShow->assertStatus(200);
        $this->assertEquals('dibaca', $pesan->fresh()->status);

        // 2. Admin membalas pesan
        $resReply = $this->post(route('admin.pesan.reply', $pesan->id), [
            'balasan_admin' => 'Tentu, pesanan buku akan dikirimkan siang ini melalui kurir ekspres.',
        ]);

        $resReply->assertRedirect(route('admin.pesan.show', $pesan->id));
        $resReply->assertSessionHas('success');

        $this->assertDatabaseHas('pesan_kontak', [
            'id' => $pesan->id,
            'status' => 'dibalas',
            'balasan_admin' => 'Tentu, pesanan buku akan dikirimkan siang ini melalui kurir ekspres.',
        ]);
    }

    public function test_user_dapat_checkout_dengan_payment_at_delivery(): void
    {
        $this->actingAs($this->peminjam);

        Keranjang::create([
            'user_id' => $this->peminjam->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 1,
        ]);

        $response = $this->post(route('checkout.proses'), [
            'nama_penerima' => 'Budi Siswa',
            'no_telepon' => '081234567890',
            'alamat_pengiriman' => 'Jl. Mawar No. 5, Jakarta',
            'metode_pembayaran' => 'Payment at Delivery (Pembayaran sebelum dikirim)',
            'catatan' => 'Mohon segera diproses.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pesanan', [
            'user_id' => $this->peminjam->id,
            'metode_pembayaran' => 'Payment at Delivery (Pembayaran sebelum dikirim)',
            'total_harga' => 85000,
        ]);
    }

    public function test_admin_tidak_dapat_membuat_kategori_duplikat(): void
    {
        $this->actingAs($this->admin);

        // Kategori 'Teknologi' sudah ada di setUp()
        $response = $this->post(route('admin.kategori.store'), [
            'nama_kategori' => 'Teknologi',
            'deskripsi' => 'Duplikasi kategori',
        ]);

        $response->assertSessionHasErrors('nama_kategori');
        $this->assertEquals(1, KategoriBuku::where('nama_kategori', 'Teknologi')->count());
    }

    public function test_admin_tidak_dapat_membuat_buku_duplikat_judul_atau_isbn(): void
    {
        $this->actingAs($this->admin);

        // 1. Coba buat buku dengan judul yang sama persis ('Belajar Laravel Modern')
        $responseJudul = $this->post(route('admin.buku.store'), [
            'judul' => 'Belajar Laravel Modern',
            'penulis' => 'Penulis Lain',
            'penerbit' => 'Penerbit Lain',
            'tahun_terbit' => 2025,
            'kategori_id' => $this->kategori->id,
            'stok' => 10,
            'harga' => 50000,
        ]);

        $responseJudul->assertSessionHasErrors('judul');
        $this->assertEquals(1, Buku::where('judul', 'Belajar Laravel Modern')->count());

        // 2. Beri ISBN ke buku yang ada
        $this->buku->update(['isbn' => '978-602-9999-01']);

        // 3. Coba buat buku lain dengan ISBN yang sama
        $responseIsbn = $this->post(route('admin.buku.store'), [
            'judul' => 'Buku Baru Berbeda',
            'penulis' => 'Penulis Berbeda',
            'penerbit' => 'Penerbit Berbeda',
            'tahun_terbit' => 2025,
            'isbn' => '978-602-9999-01',
            'kategori_id' => $this->kategori->id,
            'stok' => 5,
            'harga' => 60000,
        ]);

        $responseIsbn->assertSessionHasErrors('isbn');
        $this->assertEquals(0, Buku::where('judul', 'Buku Baru Berbeda')->count());
    }

    public function test_url_tidak_ditemukan_terproteksi_dan_tidak_menampilkan_404(): void
    {
        // 1. URL acak saat belum login -> redirect ke home
        $resGuest = $this->get('/halaman-asal-asalan-12345');
        $resGuest->assertRedirect(route('home'));

        // 2. URL admin acak saat login admin -> redirect ke dashboard admin
        $this->actingAs($this->admin);
        $resAdmin = $this->get('/admin/halaman-tidak-ada');
        $resAdmin->assertRedirect(route('admin.dashboard'));

        // 3. URL acak saat login peminjam -> redirect ke home
        $this->actingAs($this->peminjam);
        $resUser = $this->get('/halaman-acak-user');
        $resUser->assertRedirect(route('home'));
    }

    public function test_buku_tidak_dapat_dihapus_jika_memiliki_pesanan_aktif(): void
    {
        $this->actingAs($this->admin);

        // Buat pesanan aktif untuk buku
        $pesanan = Pesanan::create([
            'kode_pesanan' => 'INV-TEST-001',
            'user_id' => $this->peminjam->id,
            'total_harga' => 85000,
            'status' => 'menunggu_pembayaran',
            'metode_pembayaran' => 'Transfer Bank',
            'nama_penerima' => 'Budi',
            'no_telepon' => '08123456789',
            'alamat_pengiriman' => 'Jakarta',
        ]);

        \App\Models\PesananDetail::create([
            'pesanan_id' => $pesanan->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 1,
            'harga_satuan' => 85000,
            'subtotal' => 85000,
        ]);

        // Coba hapus buku
        $response = $this->delete(route('admin.buku.destroy', $this->buku->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('buku', ['id' => $this->buku->id]);
    }

    public function test_pengguna_tidak_dapat_dihapus_jika_memiliki_pesanan_aktif(): void
    {
        $this->actingAs($this->admin);

        // Buat pesanan aktif untuk peminjam
        Pesanan::create([
            'kode_pesanan' => 'INV-TEST-002',
            'user_id' => $this->peminjam->id,
            'total_harga' => 85000,
            'status' => 'diproses',
            'metode_pembayaran' => 'Payment at Delivery',
            'nama_penerima' => 'Budi',
            'no_telepon' => '08123456789',
            'alamat_pengiriman' => 'Jakarta',
        ]);

        // Coba hapus pengguna peminjam
        $response = $this->delete(route('admin.users.destroy', $this->peminjam->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->peminjam->id]);
    }
}


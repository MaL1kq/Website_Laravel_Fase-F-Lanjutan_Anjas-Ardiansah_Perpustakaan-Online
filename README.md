# 📚 PerpusDigital - Web Perpustakaan Online & Toko Buku (Laravel 12)

Sistem Informasi Perpustakaan Digital Berbasis Web modern yang dirancang khusus untuk keperluan **Ujian Sertifikasi Kompetensi (Pra-USK / UKK) Rekayasa Perangkat Lunak (RPL)** maupun sistem perpustakaan sekolah/kampus. Dilengkapi dengan sistem sirkulasi peminjaman, toko buku online terpadu, dan portal layanan pesan.

> 🌐 **Live Demo Website (Versi Statis / Showcase):**  
> **[https://mal1kq.github.io/Website_Laravel_Fase-F-Lanjutan_Anjas-Ardiansah_Perpustakaan-Online/](https://mal1kq.github.io/Website_Laravel_Fase-F-Lanjutan_Anjas-Ardiansah_Perpustakaan-Online/)**

---

## 🌟 Fitur Utama

### 1. Multi-Role Authentication
- **Administrator**:
  - Dashboard statistik sirkulasi (total buku, anggota, peminjaman aktif, keterlambatan, kas denda).
  - Manajemen Kategori Buku (CRUD dengan validasi duplikasi).
  - Manajemen Buku & Harga Jual (CRUD dengan cover upload, proteksi transaksi aktif).
  - Manajemen Transaksi Peminjaman (Konfirmasi pinjam, Pengembalian, Tolak Pengajuan).
  - Manajemen Pesanan Toko Buku (Verifikasi status pembayaran, update resi pengiriman, cetak invoice).
  - Laporan & Rekapitulasi (Filter tanggal & status, Cetak Laporan PDF / Print Dokumen Resmi dengan tanda tangan).
  - Manajemen Kotak Pesan Masuk (Pesan kontak dari pengguna, form balas pesan).
  - Manajemen Pengguna (Kelola akun Admin dan Anggota/Peminjam).
- **Peminjam (Siswa / Anggota)**:
  - Registrasi mandiri & Login akun.
  - Eksplorasi katalog buku dengan pencarian instan dan filter kategori.
  - Detail buku, ketersediaan stok fisik, lokasi rak, sinopsis, dan harga beli.
  - Pengajuan peminjaman online (pilih durasi 1 - 14 hari).
  - Riwayat peminjaman dengan status real-time & kalkulasi denda otomatis jika telat.
  - Cetak Bukti Peminjaman (struk sirkulasi dengan nomor transaksi).
  - Toko Buku: Tambah ke Keranjang Belanja, Checkout Pesanan, Pilih Metode Bayar (Transfer Bank / Payment at Delivery).
  - Riwayat Pesanan Pembelian & Cetak Invoice Pembelian Resmi.
  - Simpan buku ke Koleksi Favorit (Bookmark).
  - Berikan rating bintang (1-5) dan ulasan pembaca.
  - Hubungi Admin melalui formulir pesan resmi.

### 2. Sirkulasi Cerdas & Denda Otomatis
- Batas maksimal peminjaman aktif per anggota (3 buku).
- Pengurangan dan penambahan stok buku secara otomatis saat transaksi diproses.
- Penghitungan denda otomatis: **Rp 1.000 / hari** keterlambatan.

---

## 🔑 Akun Demo Siap Pakai (Untuk Presentasi / Uji USK)

| Role | Username | Email | Password | Hak Akses |
|---|---|---|---|---|
| **Administrator** | `admin` | `admin@perpus.test` | `admin123` | Akses penuh seluruh sistem, verifikasi, & kelola data |
| **Peminjam (Siswa)** | `budi` | `budi@perpus.test` | `peminjam123` | Peminjaman buku, beli buku, koleksi, dan ulasan |
| **Peminjam (Siswa)** | `siti` | `siti@perpus.test` | `peminjam123` | Peminjaman buku, beli buku, koleksi, dan ulasan |

*(Tersedia tombol satu klik "Gunakan" pada halaman login untuk memudahkan pengujian).*

---

## 🛠️ Struktur Database

1. **`users`**: Data identitas pengguna (nama, username, email, password, role: `admin`, `peminjam`, no_telepon, alamat).
2. **`kategori_buku`**: Data kategori/genre buku.
3. **`buku`**: Data pustaka (judul, penulis, penerbit, tahun, isbn, stok, harga, cover, lokasi_rak, deskripsi, kategori_id).
4. **`kategori_buku_relasi`**: Tabel relasi pivot buku dan kategori sesuai standar format USK.
5. **`peminjaman`**: Transaksi peminjaman (kode_transaksi unik, tanggal_peminjaman, tanggal_pengembalian, tanggal_dikembalikan, status: `menunggu`, `dipinjam`, `dikembalikan`, `ditolak`, `terlambat`, denda, catatan, admin_id).
6. **`ulasan_buku`**: Komentar dan rating bintang (1-5) dari pembaca.
7. **`koleksi_pribadi`**: Penanda buku favorit siswa.
8. **`keranjang` & `pesanan`**: Keranjang belanja dan order pesanan pembelian buku online.
9. **`pesan_kontak`**: Pesan pengaduan dan bantuan dari anggota ke administrator.

---

## 🚀 Cara Menjalankan Aplikasi

Aplikasi ini telah dikonfigurasi dengan **SQLite** secara bawaan sehingga siap berjalan langsung tanpa konfigurasi server database tambahan:

```bash
# 1. Jalankan server lokal Laravel
php artisan serve
```

Akses web di browser:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)** atau **[http://localhost:8000](http://localhost:8000)**

Jika menggunakan Laragon dengan Apache/Nginx virtual host:
👉 **http://perpustakaanprausk.test** (sesuai nama folder di `c:\laragon\www`).

### Perintah Bermanfaat
```bash
# Migrasi ulang & seeder data lengkap
php artisan migrate:fresh --seed

# Menjalankan automated test suite
php artisan test
```

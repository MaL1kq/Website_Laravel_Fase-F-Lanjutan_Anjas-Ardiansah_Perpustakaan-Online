<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Keranjang;
use App\Models\KoleksiPribadi;
use App\Models\Peminjaman;
use App\Models\PesanKontak;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\UlasanBuku;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Hanya 2 Role: Admin dan Peminjam)
        $admin = User::create([
            'name' => 'Administrator Utama',
            'username' => 'admin',
            'email' => 'admin@perpus.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'no_telepon' => '081234567890',
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@perpus.test',
            'password' => Hash::make('peminjam123'),
            'role' => 'peminjam',
            'alamat' => 'Jl. Cempaka Putih No. 12, Jakarta',
            'no_telepon' => '085678901234',
        ]);

        $siti = User::create([
            'name' => 'Siti Nurhaliza',
            'username' => 'siti',
            'email' => 'siti@perpus.test',
            'password' => Hash::make('peminjam123'),
            'role' => 'peminjam',
            'alamat' => 'Jl. Kebon Jeruk No. 88, Jakarta Barat',
            'no_telepon' => '087812345678',
        ]);

        $andi = User::create([
            'name' => 'Andi Pratama',
            'username' => 'andi',
            'email' => 'andi@perpus.test',
            'password' => Hash::make('peminjam123'),
            'role' => 'peminjam',
            'alamat' => 'Jl. Tebet Timur No. 25, Jakarta Selatan',
            'no_telepon' => '089912345678',
        ]);

        // 2. Seed Kategori Buku
        $kategoriList = [
            [
                'nama_kategori' => 'Teknologi & Pemrograman',
                'slug' => 'teknologi-pemrograman',
                'deskripsi' => 'Buku-buku seputar rekayasa perangkat lunak, coding, arsitektur sistem, dan teknologi terkini.'
            ],
            [
                'nama_kategori' => 'Sastra & Fiksi',
                'slug' => 'sastra-fiksi',
                'deskripsi' => 'Novel sastra, kisah fiksi inspiratif, cerpen, puisi, dan karya prosa memikat.'
            ],
            [
                'nama_kategori' => 'Sains & Matematika',
                'slug' => 'sains-matematika',
                'deskripsi' => 'Eksplorasi ilmu alam, fisika, biologi, astronomi, dan logika matematika.'
            ],
            [
                'nama_kategori' => 'Bisnis & Manajemen',
                'slug' => 'bisnis-manajemen',
                'deskripsi' => 'Panduan wirausaha, kepemimpinan modern, strategi keuangan, dan pemasaran.'
            ],
            [
                'nama_kategori' => 'Sejarah & Budaya',
                'slug' => 'sejarah-budaya',
                'deskripsi' => 'Rekam jejak peradaban bangsa, sejarah dunia, arsip pahlawan, dan antropologi.'
            ],
            [
                'nama_kategori' => 'Pengembangan Diri',
                'slug' => 'pengembangan-diri',
                'deskripsi' => 'Buku motivasi, produktivitas, psikologi praktis, dan kebiasaan hidup sehat.'
            ],
        ];

        $kategoriModels = [];
        foreach ($kategoriList as $k) {
            $kategoriModels[$k['slug']] = KategoriBuku::create($k);
        }

        // 3. Seed Buku (Lengkap dengan Harga dan Stok)
        $booksData = [
            [
                'judul' => 'Clean Code: Panduan Praktis Software Craftsmanship',
                'slug' => 'clean-code-panduan-praktis',
                'penulis' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun_terbit' => 2021,
                'isbn' => '978-0132350884',
                'kategori_slug' => 'teknologi-pemrograman',
                'harga' => 125000,
                'stok' => 8,
                'cover' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3edd4?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak A-01',
                'deskripsi' => 'Bahkan kode buruk bisa berfungsi. Tetapi jika kode tidak bersih, sistem bisa hancur. Buku ini membahas prinsip menulis kode yang bersih, mudah di-maintain, dan profesional.'
            ],
            [
                'judul' => 'Mastering Laravel 12 & Modern Web Development',
                'slug' => 'mastering-laravel-12-modern-web',
                'penulis' => 'Taylor Otwell & Komunitas',
                'penerbit' => 'O\'Reilly Media',
                'tahun_terbit' => 2025,
                'isbn' => '978-1491954249',
                'kategori_slug' => 'teknologi-pemrograman',
                'harga' => 145000,
                'stok' => 12,
                'cover' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak A-02',
                'deskripsi' => 'Panduan komprehensif membangun aplikasi web skala industri dengan framework PHP terpopuler di dunia: Laravel.'
            ],
            [
                'judul' => 'Laskar Pelangi',
                'slug' => 'laskar-pelangi',
                'penulis' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2018,
                'isbn' => '978-9791227179',
                'kategori_slug' => 'sastra-fiksi',
                'harga' => 85000,
                'stok' => 6,
                'cover' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak B-01',
                'deskripsi' => 'Kisah epik sepuluh anak Belitong yang berjuang mengejar impian dan pendidikan di tengah keterbatasan fasilitas sekolah.'
            ],
            [
                'judul' => 'Bumi Manusia',
                'slug' => 'bumi-manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Lentera Dipantara',
                'tahun_terbit' => 2020,
                'isbn' => '978-9799731234',
                'kategori_slug' => 'sastra-fiksi',
                'harga' => 98000,
                'stok' => 5,
                'cover' => 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak B-02',
                'deskripsi' => 'Karya sastra legendaris Tetralogi Buru yang mengisahkan perlawanan Minke dan cinta tragisnya bersama Annelies Mellema.'
            ],
            [
                'judul' => 'Kosmos: Menjelajahi Misteri Alam Semesta',
                'slug' => 'kosmos-misteri-alam-semesta',
                'penulis' => 'Carl Sagan',
                'penerbit' => 'Kepustakaan Populer Gramedia',
                'tahun_terbit' => 2022,
                'isbn' => '978-6024810012',
                'kategori_slug' => 'sains-matematika',
                'harga' => 135000,
                'stok' => 7,
                'cover' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak C-01',
                'deskripsi' => 'Petualangan intelektual menjelajahi miliaran galaksi, asal mula kehidupan, dan masa depan manusia di jagat raya.'
            ],
            [
                'judul' => 'Fisika Kuantum untuk Pemula',
                'slug' => 'fisika-kuantum-pemula',
                'penulis' => 'Dr. Richard Feynman',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2023,
                'isbn' => '978-6022415523',
                'kategori_slug' => 'sains-matematika',
                'harga' => 75000,
                'stok' => 4,
                'cover' => 'https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak C-02',
                'deskripsi' => 'Membongkar rahasia dunia partikel subatomik dengan bahasa sederhana, analogi cerdas, dan ilustrasi intuitif.'
            ],
            [
                'judul' => 'The Lean Startup: Inovasi Bisnis Cepat',
                'slug' => 'the-lean-startup',
                'penulis' => 'Eric Ries',
                'penerbit' => 'Crown Business',
                'tahun_terbit' => 2021,
                'isbn' => '978-0307887894',
                'kategori_slug' => 'bisnis-manajemen',
                'harga' => 110000,
                'stok' => 10,
                'cover' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak D-01',
                'deskripsi' => 'Metode revolusioner membangun produk dan validasi pasar tanpa membuang modal dan waktu.'
            ],
            [
                'judul' => 'Filosofi Teras: Stoisisme untuk Mental Tangguh',
                'slug' => 'filosofi-teras',
                'penulis' => 'Henry Manampiring',
                'penerbit' => 'Kompas Gramedia',
                'tahun_terbit' => 2023,
                'isbn' => '978-6024125189',
                'kategori_slug' => 'pengembangan-diri',
                'harga' => 98000,
                'stok' => 15,
                'cover' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak E-01',
                'deskripsi' => 'Filosofi Yunani-Romawi kuno yang relevan untuk mengatasi cemas, emosi negatif, dan membangun ketenangan batin.'
            ],
            [
                'judul' => 'Atomic Habits: Perubahan Kecil Berdampak Luar Biasa',
                'slug' => 'atomic-habits-perubahan-kecil',
                'penulis' => 'James Clear',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2023,
                'isbn' => '978-6020633176',
                'kategori_slug' => 'pengembangan-diri',
                'harga' => 108000,
                'stok' => 14,
                'cover' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak E-02',
                'deskripsi' => 'Panduan terbukti bagaimana kebiasaan kecil 1% setiap hari berlipat ganda menjadi hasil luar biasa seumur hidup.'
            ],
            [
                'judul' => 'Sejarah Nusantara: Dari Sriwijaya hingga Kemerdekaan',
                'slug' => 'sejarah-nusantara',
                'penulis' => 'Prof. Dr. Sartono Kartodirdjo',
                'penerbit' => 'Balai Pustaka',
                'tahun_terbit' => 2019,
                'isbn' => '978-9794073345',
                'kategori_slug' => 'sejarah-budaya',
                'harga' => 88000,
                'stok' => 5,
                'cover' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak F-01',
                'deskripsi' => 'Ulasan mendalam mengenai jalur rempah, kerajaan maritim, masa kolonialisme hingga perjuangan bangsa Indonesia.'
            ],
            [
                'judul' => 'Desain Grafis & UI/UX untuk Pemula',
                'slug' => 'desain-grafis-ui-ux-pemula',
                'penulis' => 'Don Norman',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2024,
                'isbn' => '978-6026232987',
                'kategori_slug' => 'teknologi-pemrograman',
                'harga' => 95000,
                'stok' => 9,
                'cover' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak A-03',
                'deskripsi' => 'Prinsip desain berpusat pada pengguna, hierarki visual, psikologi warna, dan prototyping wireframe modern.'
            ],
            [
                'judul' => 'Negeri 5 Menara',
                'slug' => 'negeri-5-menara',
                'penulis' => 'Ahmad Fuadi',
                'penerbit' => 'Gramedia Pustaka Utama',
                'tahun_terbit' => 2021,
                'isbn' => '978-9792248616',
                'kategori_slug' => 'sastra-fiksi',
                'harga' => 82000,
                'stok' => 7,
                'cover' => 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=600&q=80',
                'lokasi_rak' => 'Rak B-03',
                'deskripsi' => 'Man Jadda Wajada: Siapa yang bersungguh-sungguh akan berhasil. Kisah enam sahabat santri di Pondok Madani.'
            ],
        ];

        $bookModels = [];
        foreach ($booksData as $bData) {
            $catSlug = $bData['kategori_slug'];
            $kategoriId = $kategoriModels[$catSlug]->id;
            unset($bData['kategori_slug']);
            $bData['kategori_id'] = $kategoriId;

            $buku = Buku::create($bData);
            // Hubungkan juga ke pivot kategori_buku_relasi
            $buku->kategoriRelasi()->attach($kategoriId);
            $bookModels[] = $buku;
        }

        // 4. Seed Peminjaman Perpustakaan (Validasi oleh Admin)
        Peminjaman::create([
            'kode_transaksi' => 'TRX-' . date('Ymd') . '-001',
            'user_id' => $budi->id,
            'buku_id' => $bookModels[0]->id,
            'tanggal_peminjaman' => Carbon::now()->subDays(3)->toDateString(),
            'tanggal_pengembalian' => Carbon::now()->addDays(4)->toDateString(),
            'tanggal_dikembalikan' => null,
            'status_peminjaman' => 'dipinjam',
            'denda' => 0,
            'catatan' => 'Dipinjam untuk tugas akhir RPL.',
            'admin_id' => $admin->id,
        ]);

        Peminjaman::create([
            'kode_transaksi' => 'TRX-' . date('Ymd') . '-002',
            'user_id' => $siti->id,
            'buku_id' => $bookModels[7]->id,
            'tanggal_peminjaman' => Carbon::now()->subDays(10)->toDateString(),
            'tanggal_pengembalian' => Carbon::now()->subDays(3)->toDateString(),
            'tanggal_dikembalikan' => Carbon::now()->subDays(4)->toDateString(),
            'status_peminjaman' => 'dikembalikan',
            'denda' => 0,
            'catatan' => 'Dikembalikan dalam kondisi rapi dan bersih.',
            'admin_id' => $admin->id,
        ]);

        $tglPinjamAndi = Carbon::now()->subDays(12)->toDateString();
        $tglBatasAndi = Carbon::now()->subDays(5)->toDateString();
        $hariTelat = Carbon::parse($tglBatasAndi)->diffInDays(Carbon::now());
        Peminjaman::create([
            'kode_transaksi' => 'TRX-' . date('Ymd') . '-003',
            'user_id' => $andi->id,
            'buku_id' => $bookModels[3]->id,
            'tanggal_peminjaman' => $tglPinjamAndi,
            'tanggal_pengembalian' => $tglBatasAndi,
            'tanggal_dikembalikan' => null,
            'status_peminjaman' => 'terlambat',
            'denda' => $hariTelat * 1000,
            'catatan' => 'Peminjam melewati batas waktu 5 hari.',
            'admin_id' => $admin->id,
        ]);

        Peminjaman::create([
            'kode_transaksi' => 'TRX-' . date('Ymd') . '-004',
            'user_id' => $budi->id,
            'buku_id' => $bookModels[8]->id,
            'tanggal_peminjaman' => Carbon::now()->toDateString(),
            'tanggal_pengembalian' => Carbon::now()->addDays(7)->toDateString(),
            'tanggal_dikembalikan' => null,
            'status_peminjaman' => 'menunggu',
            'denda' => 0,
            'catatan' => 'Permintaan pinjam online baru diajukan.',
            'admin_id' => null,
        ]);

        // 5. Seed Ulasan & Rating
        UlasanBuku::create([
            'user_id' => $siti->id,
            'buku_id' => $bookModels[7]->id, // Filosofi Teras
            'rating' => 5,
            'ulasan' => 'Buku yang sangat membuka pikiran dan menenangkan hati. Sangat cocok dibaca saat sedang banyak pikiran.',
        ]);

        UlasanBuku::create([
            'user_id' => $budi->id,
            'buku_id' => $bookModels[0]->id, // Clean Code
            'rating' => 5,
            'ulasan' => 'Buku wajib untuk semua programmer! Menjelaskan cara memberi nama variabel dan merapikan fungsi dengan sangat gamblang.',
        ]);

        UlasanBuku::create([
            'user_id' => $andi->id,
            'buku_id' => $bookModels[2]->id, // Laskar Pelangi
            'rating' => 5,
            'ulasan' => 'Kisah inspiratif yang membuat terharu. Membakar semangat belajar dan pantang menyerah.',
        ]);

        // 6. Seed Koleksi Pribadi (Favorit)
        KoleksiPribadi::create([
            'user_id' => $budi->id,
            'buku_id' => $bookModels[0]->id,
        ]);
        KoleksiPribadi::create([
            'user_id' => $budi->id,
            'buku_id' => $bookModels[1]->id,
        ]);
        KoleksiPribadi::create([
            'user_id' => $siti->id,
            'buku_id' => $bookModels[7]->id,
        ]);

        // 7. Seed Keranjang Belanja (Sample Cart untuk Budi)
        Keranjang::create([
            'user_id' => $budi->id,
            'buku_id' => $bookModels[1]->id, // Mastering Laravel
            'jumlah' => 1,
        ]);

        // 8. Seed Pesanan Pembelian Buku (Sample Orders)
        $pesanan1 = Pesanan::create([
            'kode_pesanan' => 'INV-' . date('Ymd') . '-0001',
            'user_id' => $siti->id,
            'total_harga' => 196000,
            'status' => 'diproses',
            'metode_pembayaran' => 'Transfer Bank BCA',
            'nama_penerima' => 'Siti Nurhaliza',
            'no_telepon' => '087812345678',
            'alamat_pengiriman' => 'Jl. Kebon Jeruk No. 88, RT 02/RW 05, Jakarta Barat',
            'catatan' => 'Mohon dikemas dengan bubble wrap tebal ya kak.',
        ]);

        PesananDetail::create([
            'pesanan_id' => $pesanan1->id,
            'buku_id' => $bookModels[7]->id, // Filosofi Teras (98.000)
            'jumlah' => 2,
            'harga_satuan' => 98000,
            'subtotal' => 196000,
        ]);

        $pesanan2 = Pesanan::create([
            'kode_pesanan' => 'INV-' . date('Ymd') . '-0002',
            'user_id' => $andi->id,
            'total_harga' => 85000,
            'status' => 'selesai',
            'metode_pembayaran' => 'QRIS',
            'nama_penerima' => 'Andi Pratama',
            'no_telepon' => '089912345678',
            'alamat_pengiriman' => 'Jl. Tebet Timur No. 25, Jakarta Selatan',
            'catatan' => 'Dikirim siang hari saat ada orang di rumah.',
        ]);

        PesananDetail::create([
            'pesanan_id' => $pesanan2->id,
            'buku_id' => $bookModels[2]->id, // Laskar Pelangi (85.000)
            'jumlah' => 1,
            'harga_satuan' => 85000,
            'subtotal' => 85000,
        ]);

        // 9. Seed Pesan Masuk (Contact to Admin)
        PesanKontak::create([
            'user_id' => $budi->id,
            'nama' => $budi->name,
            'email' => $budi->email,
            'no_telepon' => $budi->no_telepon,
            'subjek' => 'Konfirmasi Pembayaran (Payment at Delivery)',
            'pesan' => 'Halo Admin, saya telah melakukan pembayaran transfer bank untuk pesanan buku saya. Mohon diverifikasi sebelum pengiriman ya, terima kasih!',
            'status' => 'belum_dibaca',
        ]);

        PesanKontak::create([
            'user_id' => $siti->id,
            'nama' => $siti->name,
            'email' => $siti->email,
            'no_telepon' => $siti->no_telepon,
            'subjek' => 'Pertanyaan Stok Buku',
            'pesan' => 'Selamat siang admin, apakah buku Pemrograman Web Modern masih tersedia stoknya untuk dibeli?',
            'balasan_admin' => 'Halo Siti, stok buku Pemrograman Web Modern masih tersedia 5 eksemplar di gudang BookStore. Silakan langsung melakukan pemesanan via website!',
            'status' => 'dibalas',
            'dibaca_pada' => Carbon::now()->subHours(2),
            'dibalas_pada' => Carbon::now()->subHour(),
        ]);
    }
}

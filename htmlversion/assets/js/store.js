/**
 * PerpusDigital - Local Storage Data Store & Helper Library
 * Pure HTML/JS Version with persistent storage and role simulation
 */

const STORAGE_KEYS = {
    USER: 'perpus_current_user',
    BOOKS: 'perpus_books',
    CATEGORIES: 'perpus_categories',
    CART: 'perpus_cart',
    ORDERS: 'perpus_orders',
    BORROWINGS: 'perpus_borrowings',
    MESSAGES: 'perpus_messages',
    FAVORITES: 'perpus_favorites',
    USERS: 'perpus_users'
};

// Default seed data matching Laravel seeder
const DEFAULT_CATEGORIES = [
    { id: 1, nama_kategori: 'Teknologi & Pemrograman', slug: 'teknologi-pemrograman', icon: 'fa-code', deskripsi: 'Buku-buku seputar rekayasa perangkat lunak, coding, arsitektur sistem, dan teknologi terkini.' },
    { id: 2, nama_kategori: 'Sastra & Fiksi', slug: 'sastra-fiksi', icon: 'fa-feather-pointed', deskripsi: 'Novel sastra, kisah fiksi inspiratif, cerpen, puisi, dan karya prosa memikat.' },
    { id: 3, nama_kategori: 'Sains & Matematika', slug: 'sains-matematika', icon: 'fa-atom', deskripsi: 'Eksplorasi ilmu alam, fisika, biologi, astronomi, dan logika matematika.' },
    { id: 4, nama_kategori: 'Bisnis & Manajemen', slug: 'bisnis-manajemen', icon: 'fa-chart-pie', deskripsi: 'Panduan wirausaha, kepemimpinan modern, strategi keuangan, dan pemasaran.' },
    { id: 5, nama_kategori: 'Sejarah & Budaya', slug: 'sejarah-budaya', icon: 'fa-landmark', deskripsi: 'Rekam jejak peradaban bangsa, sejarah dunia, arsip pahlawan, dan antropologi.' },
    { id: 6, nama_kategori: 'Pengembangan Diri', slug: 'pengembangan-diri', icon: 'fa-seedling', deskripsi: 'Buku motivasi, produktivitas, psikologi praktis, dan kebiasaan hidup sehat.' }
];

const DEFAULT_BOOKS = [
    {
        id: 1,
        judul: 'Clean Code: Panduan Praktis Software Craftsmanship',
        slug: 'clean-code-panduan-praktis',
        penulis: 'Robert C. Martin',
        penerbit: 'Prentice Hall',
        tahun_terbit: 2021,
        isbn: '978-0132350884',
        kategori_id: 1,
        kategori_slug: 'teknologi-pemrograman',
        kategori_nama: 'Teknologi & Pemrograman',
        harga: 125000,
        stok: 8,
        cover: 'assets/images/CleanCode.jpg',
        lokasi_rak: 'Rak A-01',
        deskripsi: 'Bahkan kode buruk bisa berfungsi. Tetapi jika kode tidak bersih, sistem bisa hancur. Buku ini membahas prinsip menulis kode yang bersih, mudah di-maintain, dan profesional.',
        rating: 5.0,
        total_ulasan: 1
    },
    {
        id: 2,
        judul: 'Mastering Laravel 12 & Modern Web Development',
        slug: 'mastering-laravel-12-modern-web',
        penulis: 'Taylor Otwell & Komunitas',
        penerbit: 'O\'Reilly Media',
        tahun_terbit: 2025,
        isbn: '978-1491954249',
        kategori_id: 1,
        kategori_slug: 'teknologi-pemrograman',
        kategori_nama: 'Teknologi & Pemrograman',
        harga: 145000,
        stok: 12,
        cover: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak A-02',
        deskripsi: 'Panduan komprehensif membangun aplikasi web skala industri dengan framework PHP terpopuler di dunia: Laravel.',
        rating: 4.8,
        total_ulasan: 2
    },
    {
        id: 3,
        judul: 'Laskar Pelangi',
        slug: 'laskar-pelangi',
        penulis: 'Andrea Hirata',
        penerbit: 'Bentang Pustaka',
        tahun_terbit: 2018,
        isbn: '978-9791227179',
        kategori_id: 2,
        kategori_slug: 'sastra-fiksi',
        kategori_nama: 'Sastra & Fiksi',
        harga: 85000,
        stok: 6,
        cover: 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak B-01',
        deskripsi: 'Kisah epik sepuluh anak Belitong yang berjuang mengejar impian dan pendidikan di tengah keterbatasan fasilitas sekolah.',
        rating: 5.0,
        total_ulasan: 3
    },
    {
        id: 4,
        judul: 'Bumi Manusia',
        slug: 'bumi-manusia',
        penulis: 'Pramoedya Ananta Toer',
        penerbit: 'Lentera Dipantara',
        tahun_terbit: 2020,
        isbn: '978-9799731234',
        kategori_id: 2,
        kategori_slug: 'sastra-fiksi',
        kategori_nama: 'Sastra & Fiksi',
        harga: 98000,
        stok: 5,
        cover: 'https://images.unsplash.com/photo-1476275466078-4007374efbbe?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak B-02',
        deskripsi: 'Karya sastra legendaris Tetralogi Buru yang mengisahkan perlawanan Minke dan cinta tragisnya bersama Annelies Mellema.',
        rating: 4.9,
        total_ulasan: 2
    },
    {
        id: 5,
        judul: 'Kosmos: Menjelajahi Misteri Alam Semesta',
        slug: 'kosmos-misteri-alam-semesta',
        penulis: 'Carl Sagan',
        penerbit: 'Kepustakaan Populer Gramedia',
        tahun_terbit: 2022,
        isbn: '978-6024810012',
        kategori_id: 3,
        kategori_slug: 'sains-matematika',
        kategori_nama: 'Sains & Matematika',
        harga: 135000,
        stok: 7,
        cover: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak C-01',
        deskripsi: 'Petualangan intelektual menjelajahi miliaran galaksi, asal mula kehidupan, dan masa depan manusia di jagat raya.',
        rating: 4.9,
        total_ulasan: 1
    },
    {
        id: 6,
        judul: 'Fisika Kuantum untuk Pemula',
        slug: 'fisika-kuantum-pemula',
        penulis: 'Dr. Richard Feynman',
        penerbit: 'Erlangga',
        tahun_terbit: 2023,
        isbn: '978-6022415523',
        kategori_id: 3,
        kategori_slug: 'sains-matematika',
        kategori_nama: 'Sains & Matematika',
        harga: 75000,
        stok: 4,
        cover: 'https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak C-02',
        deskripsi: 'Membongkar rahasia dunia partikel subatomik dengan bahasa sederhana, analogi cerdas, dan ilustrasi intuitif.',
        rating: 4.7,
        total_ulasan: 1
    },
    {
        id: 7,
        judul: 'The Lean Startup: Inovasi Bisnis Cepat',
        slug: 'the-lean-startup',
        penulis: 'Eric Ries',
        penerbit: 'Crown Business',
        tahun_terbit: 2021,
        isbn: '978-0307887894',
        kategori_id: 4,
        kategori_slug: 'bisnis-manajemen',
        kategori_nama: 'Bisnis & Manajemen',
        harga: 110000,
        stok: 10,
        cover: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak D-01',
        deskripsi: 'Metode revolusioner membangun produk dan validasi pasar tanpa membuang modal dan waktu.',
        rating: 4.8,
        total_ulasan: 1
    },
    {
        id: 8,
        judul: 'Filosofi Teras: Stoisisme untuk Mental Tangguh',
        slug: 'filosofi-teras',
        penulis: 'Henry Manampiring',
        penerbit: 'Kompas Gramedia',
        tahun_terbit: 2023,
        isbn: '978-6024125189',
        kategori_id: 6,
        kategori_slug: 'pengembangan-diri',
        kategori_nama: 'Pengembangan Diri',
        harga: 98000,
        stok: 15,
        cover: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak E-01',
        deskripsi: 'Filosofi Yunani-Romawi kuno yang relevan untuk mengatasi cemas, emosi negatif, dan membangun ketenangan batin.',
        rating: 5.0,
        total_ulasan: 4
    },
    {
        id: 9,
        judul: 'Atomic Habits: Perubahan Kecil Berdampak Luar Biasa',
        slug: 'atomic-habits-perubahan-kecil',
        penulis: 'James Clear',
        penerbit: 'Gramedia Pustaka Utama',
        tahun_terbit: 2023,
        isbn: '978-6020633176',
        kategori_id: 6,
        kategori_slug: 'pengembangan-diri',
        kategori_nama: 'Pengembangan Diri',
        harga: 108000,
        stok: 14,
        cover: 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
        lokasi_rak: 'Rak E-02',
        deskripsi: 'Panduan terbukti bagaimana kebiasaan kecil 1% setiap hari berlipat ganda menjadi hasil luar biasa seumur hidup.',
        rating: 4.9,
        total_ulasan: 3
    },
    {
        id: 10,
        judul: 'Laut Bercerita',
        slug: 'laut-bercerita',
        penulis: 'Leila S. Chudori',
        penerbit: 'Kepustakaan Populer Gramedia',
        tahun_terbit: 2017,
        isbn: '978-6024246945',
        kategori_id: 2,
        kategori_slug: 'sastra-fiksi',
        kategori_nama: 'Sastra & Fiksi',
        harga: 105000,
        stok: 9,
        cover: 'assets/images/LautBercerita.webp',
        lokasi_rak: 'Rak B-03',
        deskripsi: 'Novel tentang kehilangan, persahabatan, kekeluargaan, cinta, dan pengkhianatan di masa kelam aktivis mahasiswa 1998.',
        rating: 4.9,
        total_ulasan: 2
    }
];

const DEFAULT_USERS = [
    { id: 1, name: 'Administrator Utama', username: 'admin', email: 'admin@perpus.test', role: 'admin', alamat: 'Jl. Merdeka No. 10, Jakarta Pusat', no_telepon: '081234567890' },
    { id: 2, name: 'Budi Santoso', username: 'budi', email: 'budi@perpus.test', role: 'peminjam', alamat: 'Jl. Cempaka Putih No. 12, Jakarta', no_telepon: '085678901234' },
    { id: 3, name: 'Siti Nurhaliza', username: 'siti', email: 'siti@perpus.test', role: 'peminjam', alamat: 'Jl. Kebon Jeruk No. 88, Jakarta Barat', no_telepon: '087812345678' },
    { id: 4, name: 'Andi Pratama', username: 'andi', email: 'andi@perpus.test', role: 'peminjam', alamat: 'Jl. Tebet Timur No. 25, Jakarta Selatan', no_telepon: '089912345678' }
];

const DEFAULT_ORDERS = [
    {
        id: 1,
        kode_pesanan: 'INV-20261106-0001',
        user_id: 3,
        user_name: 'Siti Nurhaliza',
        total_harga: 196000,
        status: 'diproses',
        metode_pembayaran: 'Payment at Delivery (Transfer Bank BCA)',
        nama_penerima: 'Siti Nurhaliza',
        no_telepon: '087812345678',
        alamat_pengiriman: 'Jl. Kebon Jeruk No. 88, RT 02/RW 05, Jakarta Barat',
        catatan: 'Mohon dikemas dengan bubble wrap tebal ya kak.',
        created_at: '2026-11-06 08:30:00',
        items: [
            { buku_id: 8, judul: 'Filosofi Teras: Stoisisme untuk Mental Tangguh', cover: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80', jumlah: 2, harga_satuan: 98000, subtotal: 196000 }
        ]
    },
    {
        id: 2,
        kode_pesanan: 'INV-20261106-0002',
        user_id: 4,
        user_name: 'Andi Pratama',
        total_harga: 85000,
        status: 'selesai',
        metode_pembayaran: 'Payment at Delivery (QRIS)',
        nama_penerima: 'Andi Pratama',
        no_telepon: '089912345678',
        alamat_pengiriman: 'Jl. Tebet Timur No. 25, Jakarta Selatan',
        catatan: 'Dikirim siang hari saat ada orang di rumah.',
        created_at: '2026-11-05 14:15:00',
        items: [
            { buku_id: 3, judul: 'Laskar Pelangi', cover: 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=600&q=80', jumlah: 1, harga_satuan: 85000, subtotal: 85000 }
        ]
    }
];

const DEFAULT_BORROWINGS = [
    {
        id: 1,
        user_id: 2,
        user_name: 'Budi Santoso',
        buku_id: 1,
        judul_buku: 'Clean Code: Panduan Praktis Software Craftsmanship',
        cover_buku: 'assets/images/CleanCode.jpg',
        tanggal_peminjaman: '2026-11-01',
        tanggal_pengembalian: '2026-11-08',
        tanggal_dikembalikan: null,
        status: 'dipinjam',
        denda: 0,
        catatan: 'Untuk referensi tugas akhir sekolah.'
    },
    {
        id: 2,
        user_id: 2,
        user_name: 'Budi Santoso',
        buku_id: 10,
        judul_buku: 'Laut Bercerita',
        cover_buku: 'assets/images/LautBercerita.webp',
        tanggal_peminjaman: '2026-11-06',
        tanggal_pengembalian: '2026-11-13',
        tanggal_dikembalikan: null,
        status: 'menunggu',
        denda: 0,
        catatan: 'Permintaan pinjam online baru diajukan.'
    },
    {
        id: 3,
        user_id: 3,
        user_name: 'Siti Nurhaliza',
        buku_id: 8,
        judul_buku: 'Filosofi Teras: Stoisisme untuk Mental Tangguh',
        cover_buku: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
        tanggal_peminjaman: '2026-10-20',
        tanggal_pengembalian: '2026-10-27',
        tanggal_dikembalikan: '2026-10-26',
        status: 'selesai',
        denda: 0,
        catatan: 'Tepat waktu.'
    }
];

const DEFAULT_MESSAGES = [
    {
        id: 1,
        user_id: 2,
        nama: 'Budi Santoso',
        email: 'budi@perpus.test',
        no_telepon: '085678901234',
        subjek: 'Konfirmasi Pembayaran (Payment at Delivery)',
        pesan: 'Halo Admin, saya telah melakukan pembayaran transfer bank untuk pesanan buku saya. Mohon diverifikasi sebelum pengiriman ya, terima kasih!',
        status: 'belum_dibaca',
        balasan_admin: null,
        created_at: '2026-11-06 08:15:00',
        dibalas_at: null
    },
    {
        id: 2,
        user_id: 3,
        nama: 'Siti Nurhaliza',
        email: 'siti@perpus.test',
        no_telepon: '087812345678',
        subjek: 'Pertanyaan Stok Buku',
        pesan: 'Selamat siang admin, apakah buku Pemrograman Web Modern masih tersedia stoknya untuk dibeli?',
        status: 'dibalas',
        balasan_admin: 'Halo Siti, stok buku Pemrograman Web Modern masih tersedia 5 eksemplar di gudang BookStore. Silakan langsung melakukan pemesanan via website!',
        created_at: '2026-11-05 11:20:00',
        dibalas_at: '2026-11-05 12:45:00'
    }
];

const DEFAULT_CART = [
    { buku_id: 2, jumlah: 1 }
];

const DEFAULT_FAVORITES = [1, 2, 8];

// Store API
const Store = {
    init() {
        if (!localStorage.getItem(STORAGE_KEYS.BOOKS)) {
            localStorage.setItem(STORAGE_KEYS.BOOKS, JSON.stringify(DEFAULT_BOOKS));
        }
        if (!localStorage.getItem(STORAGE_KEYS.CATEGORIES)) {
            localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(DEFAULT_CATEGORIES));
        }
        if (!localStorage.getItem(STORAGE_KEYS.USERS)) {
            localStorage.setItem(STORAGE_KEYS.USERS, JSON.stringify(DEFAULT_USERS));
        }
        if (!localStorage.getItem(STORAGE_KEYS.USER)) {
            // Default logged in as Budi (peminjam)
            localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(DEFAULT_USERS[1]));
        }
        if (!localStorage.getItem(STORAGE_KEYS.ORDERS)) {
            localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(DEFAULT_ORDERS));
        }
        if (!localStorage.getItem(STORAGE_KEYS.BORROWINGS)) {
            localStorage.setItem(STORAGE_KEYS.BORROWINGS, JSON.stringify(DEFAULT_BORROWINGS));
        }
        if (!localStorage.getItem(STORAGE_KEYS.MESSAGES)) {
            localStorage.setItem(STORAGE_KEYS.MESSAGES, JSON.stringify(DEFAULT_MESSAGES));
        }
        if (!localStorage.getItem(STORAGE_KEYS.CART)) {
            localStorage.setItem(STORAGE_KEYS.CART, JSON.stringify(DEFAULT_CART));
        }
        if (!localStorage.getItem(STORAGE_KEYS.FAVORITES)) {
            localStorage.setItem(STORAGE_KEYS.FAVORITES, JSON.stringify(DEFAULT_FAVORITES));
        }
    },

    getCurrentUser() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.USER) || 'null');
    },

    setCurrentUser(user) {
        localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(user));
        window.dispatchEvent(new Event('perpus_user_changed'));
    },

    logout() {
        localStorage.removeItem(STORAGE_KEYS.USER);
        window.dispatchEvent(new Event('perpus_user_changed'));
    },

    getBooks() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.BOOKS) || '[]');
    },

    getBookById(id) {
        return this.getBooks().find(b => b.id == id);
    },

    saveBooks(books) {
        localStorage.setItem(STORAGE_KEYS.BOOKS, JSON.stringify(books));
        window.dispatchEvent(new Event('perpus_books_changed'));
    },

    getCategories() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.CATEGORIES) || '[]');
    },

    saveCategories(categories) {
        localStorage.setItem(STORAGE_KEYS.CATEGORIES, JSON.stringify(categories));
    },

    getCart() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.CART) || '[]');
    },

    saveCart(cart) {
        localStorage.setItem(STORAGE_KEYS.CART, JSON.stringify(cart));
        window.dispatchEvent(new Event('perpus_cart_updated'));
    },

    addToCart(bookId, qty = 1) {
        let cart = this.getCart();
        const existing = cart.find(item => item.buku_id == bookId);
        const book = this.getBookById(bookId);

        if (!book) return false;
        if (existing) {
            existing.jumlah += qty;
        } else {
            cart.push({ buku_id: Number(bookId), jumlah: qty });
        }
        this.saveCart(cart);
        this.toast(`"${book.judul}" berhasil ditambahkan ke keranjang!`, 'success');
        return true;
    },

    updateCartQty(bookId, qty) {
        let cart = this.getCart();
        const item = cart.find(i => i.buku_id == bookId);
        if (item) {
            if (qty <= 0) {
                cart = cart.filter(i => i.buku_id != bookId);
            } else {
                item.jumlah = qty;
            }
            this.saveCart(cart);
        }
    },

    removeFromCart(bookId) {
        let cart = this.getCart().filter(i => i.buku_id != bookId);
        this.saveCart(cart);
        this.toast('Buku dihapus dari keranjang', 'info');
    },

    clearCart() {
        this.saveCart([]);
    },

    getCartCount() {
        return this.getCart().reduce((sum, item) => sum + item.jumlah, 0);
    },

    getCartDetailed() {
        const cart = this.getCart();
        const books = this.getBooks();
        return cart.map(item => {
            const b = books.find(book => book.id == item.buku_id) || {};
            return {
                ...item,
                buku: b,
                subtotal: (b.harga || 0) * item.jumlah
            };
        });
    },

    getCartTotal() {
        return this.getCartDetailed().reduce((sum, item) => sum + item.subtotal, 0);
    },

    getOrders() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.ORDERS) || '[]');
    },

    saveOrders(orders) {
        localStorage.setItem(STORAGE_KEYS.ORDERS, JSON.stringify(orders));
        window.dispatchEvent(new Event('perpus_orders_changed'));
    },

    createOrder(orderData) {
        const orders = this.getOrders();
        const now = new Date();
        const dateStr = now.toISOString().slice(0, 10).replace(/-/g, '');
        const randomNum = String(orders.length + 1).padStart(4, '0');
        const kodePesanan = `INV-${dateStr}-${randomNum}`;

        const newOrder = {
            id: Date.now(),
            kode_pesanan: kodePesanan,
            user_id: orderData.user_id,
            user_name: orderData.nama_penerima,
            total_harga: orderData.total_harga,
            status: 'menunggu', // Menunggu Konfirmasi (BNSP)
            metode_pembayaran: orderData.metode_pembayaran || 'Payment at Delivery (Pembayaran sebelum dikirim)',
            nama_penerima: orderData.nama_penerima,
            no_telepon: orderData.no_telepon,
            alamat_pengiriman: orderData.alamat_pengiriman,
            catatan: orderData.catatan || '',
            created_at: now.toISOString().slice(0, 19).replace('T', ' '),
            items: orderData.items
        };

        orders.unshift(newOrder);
        this.saveOrders(orders);
        this.clearCart();
        return newOrder;
    },

    getBorrowings() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.BORROWINGS) || '[]');
    },

    saveBorrowings(borrowings) {
        localStorage.setItem(STORAGE_KEYS.BORROWINGS, JSON.stringify(borrowings));
        window.dispatchEvent(new Event('perpus_borrowings_changed'));
    },

    borrowBook(bookId, durationDays = 7, catatan = '') {
        const book = this.getBookById(bookId);
        const user = this.getCurrentUser();
        if (!book || !user) return null;

        const borrowings = this.getBorrowings();
        const now = new Date();
        const returnDate = new Date();
        returnDate.setDate(now.getDate() + Number(durationDays));

        const newBorrow = {
            id: Date.now(),
            user_id: user.id,
            user_name: user.name,
            buku_id: book.id,
            judul_buku: book.judul,
            cover_buku: book.cover,
            tanggal_peminjaman: now.toISOString().slice(0, 10),
            tanggal_pengembalian: returnDate.toISOString().slice(0, 10),
            tanggal_dikembalikan: null,
            status: 'menunggu', // menunggu persetujuan admin
            denda: 0,
            catatan: catatan || 'Peminjaman online via website'
        };

        borrowings.unshift(newBorrow);
        this.saveBorrowings(borrowings);
        this.toast(`Permintaan peminjaman "${book.judul}" berhasil diajukan!`, 'success');
        return newBorrow;
    },

    getMessages() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.MESSAGES) || '[]');
    },

    saveMessages(msgs) {
        localStorage.setItem(STORAGE_KEYS.MESSAGES, JSON.stringify(msgs));
        window.dispatchEvent(new Event('perpus_messages_changed'));
    },

    sendMessage(msgData) {
        const msgs = this.getMessages();
        const user = this.getCurrentUser();
        const newMsg = {
            id: Date.now(),
            user_id: user ? user.id : null,
            nama: msgData.nama,
            email: msgData.email,
            no_telepon: msgData.no_telepon || '',
            subjek: msgData.subjek,
            pesan: msgData.pesan,
            status: 'belum_dibaca',
            balasan_admin: null,
            created_at: new Date().toISOString().slice(0, 19).replace('T', ' '),
            dibalas_at: null
        };
        msgs.unshift(newMsg);
        this.saveMessages(msgs);
        this.toast('Pesan berhasil dikirimkan ke Admin!', 'success');
        return newMsg;
    },

    replyMessage(msgId, replyText) {
        const msgs = this.getMessages();
        const m = msgs.find(item => item.id == msgId);
        if (m) {
            m.balasan_admin = replyText;
            m.status = 'dibalas';
            m.dibalas_at = new Date().toISOString().slice(0, 19).replace('T', ' ');
            this.saveMessages(msgs);
            this.toast('Balasan admin berhasil disimpan & terkirim ke user!', 'success');
            return true;
        }
        return false;
    },

    getFavorites() {
        return JSON.parse(localStorage.getItem(STORAGE_KEYS.FAVORITES) || '[]');
    },

    toggleFavorite(bookId) {
        let favs = this.getFavorites();
        const id = Number(bookId);
        if (favs.includes(id)) {
            favs = favs.filter(f => f !== id);
            this.toast('Dihapus dari koleksi favorit', 'info');
        } else {
            favs.push(id);
            this.toast('Ditambahkan ke koleksi favorit!', 'success');
        }
        localStorage.setItem(STORAGE_KEYS.FAVORITES, JSON.stringify(favs));
        window.dispatchEvent(new Event('perpus_favorites_changed'));
        return favs.includes(id);
    },

    isFavorite(bookId) {
        return this.getFavorites().includes(Number(bookId));
    },

    formatRupiah(num) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num || 0);
    },

    toast(message, type = 'info') {
        const container = document.getElementById('toast-container') || this.createToastContainer();
        const toast = document.createElement('div');
        const bg = type === 'success' ? 'bg-emerald-600' : (type === 'error' ? 'bg-rose-600' : 'bg-indigo-600');
        const icon = type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-info');

        toast.className = `${bg} text-white px-4 py-3 rounded-xl shadow-xl flex items-center space-x-3 text-sm transform transition-all duration-300 translate-y-2 opacity-0 z-50 max-w-md`;
        toast.innerHTML = `
            <i class="fa-solid ${icon} text-base flex-shrink-0"></i>
            <span class="flex-1 font-medium">${message}</span>
            <button class="text-white/80 hover:text-white" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        });

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    },

    createToastContainer() {
        const el = document.createElement('div');
        el.id = 'toast-container';
        el.className = 'fixed bottom-5 right-5 z-[9999] flex flex-col space-y-2.5';
        document.body.appendChild(el);
        return el;
    }
};

// Initialize Store automatically
Store.init();
window.Store = Store;

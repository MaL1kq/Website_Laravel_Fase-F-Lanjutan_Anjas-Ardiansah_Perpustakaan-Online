/**
 * PerpusDigital - Global Layout & Component Renderer
 * Provides unified navigation, user switching, cart badges, and footer
 */

function renderHeader(activePage = '') {
    const user = Store.getCurrentUser();
    const cartCount = Store.getCartCount();

    const isHome = activePage === 'home';
    const isKatalog = activePage === 'katalog';
    const isAbout = activePage === 'about';
    const isContact = activePage === 'contact';
    const isPinjam = activePage === 'pinjam';
    const isPesanan = activePage === 'pesanan';
    const isFavorit = activePage === 'favorit';

    const headerHtml = `
    <!-- Main Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm" x-data="{ mobileMenu: false, userDropdown: false }">
        <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16">
            <div class="flex justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="index.html" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-indigo-100 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-book-bookmark text-lg"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-700 to-indigo-500 bg-clip-text text-transparent">PerpusDigital</span>
                            <span class="hidden sm:inline-block text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full font-medium ml-1">Pra-USK</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex md:space-x-1 md:ml-8">
                        <a href="index.html" class="px-3 py-2 rounded-lg text-sm font-medium transition ${isHome ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50'}">
                            <i class="fa-solid fa-house mr-1.5 text-xs"></i> Beranda
                        </a>
                        <a href="katalog.html" class="px-3 py-2 rounded-lg text-sm font-medium transition ${isKatalog ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50'}">
                            <i class="fa-solid fa-book-open mr-1.5 text-xs"></i> Katalog Buku
                        </a>
                        <a href="about.html" class="px-3 py-2 rounded-lg text-sm font-medium transition ${isAbout ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50'}">
                            <i class="fa-solid fa-circle-info mr-1.5 text-xs"></i> About Us
                        </a>
                        ${!user || user.role !== 'admin' ? `
                        <a href="contact.html" class="px-3 py-2 rounded-lg text-sm font-medium transition ${isContact ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50'}">
                            <i class="fa-solid fa-envelope mr-1.5 text-xs"></i> Contact to Admin
                        </a>
                        ` : ''}
                        ${user && user.role === 'peminjam' ? `
                            <a href="riwayat-peminjaman.html" class="px-3 py-2 rounded-lg text-sm font-medium transition ${isPinjam ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50'}">
                                <i class="fa-solid fa-clock-rotate-left mr-1.5 text-xs"></i> Riwayat Pinjam
                            </a>
                            <a href="riwayat-pesanan.html" class="px-3 py-2 rounded-lg text-sm font-medium transition ${isPesanan ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50'}">
                                <i class="fa-solid fa-bag-shopping mr-1.5 text-xs"></i> Pesanan Saya
                            </a>
                            <a href="koleksi-favorit.html" class="px-3 py-2 rounded-lg text-sm font-medium transition ${isFavorit ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:text-indigo-600 hover:bg-slate-50'}">
                                <i class="fa-solid fa-bookmark mr-1.5 text-xs"></i> Koleksi Favorit
                            </a>
                        ` : ''}
                    </div>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center space-x-3">
                    ${user && user.role === 'peminjam' ? `
                        <a href="cart.html" class="relative p-2.5 rounded-full border border-slate-200 hover:border-indigo-400 bg-white text-slate-700 hover:text-indigo-600 transition shadow-sm" title="Keranjang Belanja">
                            <i class="fa-solid fa-cart-shopping text-sm"></i>
                            <span id="nav-cart-badge" class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-extrabold flex items-center justify-center shadow-md ${cartCount > 0 ? '' : 'hidden'}">
                                ${cartCount > 99 ? '99+' : cartCount}
                            </span>
                        </a>
                    ` : ''}

                    ${!user ? `
                        <a href="login.html" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="register.html" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition">
                            Daftar Anggota
                        </a>
                    ` : `
                        <!-- User Menu Dropdown -->
                        <div class="relative" @click.outside="userDropdown = false">
                            <button @click="userDropdown = !userDropdown" type="button" class="flex items-center space-x-2.5 p-1.5 pl-3 rounded-full border border-slate-200 hover:border-slate-300 bg-white transition focus:outline-none">
                                <div class="text-left hidden sm:block">
                                    <p class="text-xs font-bold text-slate-800 leading-tight">${user.name}</p>
                                    <p class="text-[10px] uppercase font-semibold text-indigo-600 tracking-wider">
                                        ${user.role === 'admin' ? 'Administrator' : 'Anggota'}
                                    </p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                    ${user.name ? user.name.charAt(0) : 'U'}
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 pr-1"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="userDropdown" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 z-50">
                                <div class="px-4 py-2.5 border-b border-slate-100">
                                    <p class="text-xs text-slate-500">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">${user.email}</p>
                                    <span class="inline-block mt-1 text-[11px] font-semibold px-2 py-0.5 rounded-full ${user.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700'}">
                                        Role: ${user.role === 'admin' ? 'Administrator' : 'Anggota'}
                                    </span>
                                </div>

                                ${user.role === 'admin' ? `
                                    <a href="admin/index.html" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-chart-line w-5 text-indigo-500"></i> Panel Administrator
                                    </a>
                                ` : ''}

                                ${user.role === 'peminjam' ? `
                                    <a href="cart.html" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-cart-shopping w-5 text-indigo-500"></i> Keranjang Belanja
                                    </a>
                                    <a href="riwayat-pesanan.html" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-bag-shopping w-5 text-indigo-500"></i> Pesanan Saya
                                    </a>
                                    <a href="riwayat-peminjaman.html" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-book-reader w-5 text-indigo-500"></i> Riwayat Pinjam
                                    </a>
                                    <a href="koleksi-favorit.html" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                        <i class="fa-solid fa-heart w-5 text-pink-500"></i> Koleksi Favorit
                                    </a>
                                ` : ''}

                                <div class="border-t border-slate-100 my-1"></div>

                                <button onclick="logoutDemo()" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Keluar
                                </button>
                            </div>
                        </div>
                    `}

                    <!-- Mobile Menu Toggle Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <i :class="mobileMenu ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'" class="text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenu" x-transition class="md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1">
            <a href="index.html" class="block px-3 py-2 rounded-lg text-base font-medium ${isHome ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600'}">
                <i class="fa-solid fa-house mr-2"></i> Beranda
            </a>
            <a href="katalog.html" class="block px-3 py-2 rounded-lg text-base font-medium ${isKatalog ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600'}">
                <i class="fa-solid fa-book-open mr-2"></i> Katalog Buku
            </a>
            <a href="about.html" class="block px-3 py-2 rounded-lg text-base font-medium ${isAbout ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600'}">
                <i class="fa-solid fa-circle-info mr-2"></i> About Us (Tentang Kami)
            </a>
            ${!user || user.role !== 'admin' ? `
            <a href="contact.html" class="block px-3 py-2 rounded-lg text-base font-medium ${isContact ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600'}">
                <i class="fa-solid fa-envelope mr-2"></i> Contact to Admin
            </a>
            ` : ''}
            ${user && user.role === 'peminjam' ? `
                <a href="cart.html" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-600">
                    <i class="fa-solid fa-cart-shopping mr-2"></i> Keranjang Belanja
                </a>
                <a href="riwayat-pesanan.html" class="block px-3 py-2 rounded-lg text-base font-medium ${isPesanan ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600'}">
                    <i class="fa-solid fa-bag-shopping mr-2"></i> Pesanan Saya
                </a>
                <a href="riwayat-peminjaman.html" class="block px-3 py-2 rounded-lg text-base font-medium ${isPinjam ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600'}">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Riwayat Pinjam
                </a>
                <a href="koleksi-favorit.html" class="block px-3 py-2 rounded-lg text-base font-medium ${isFavorit ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600'}">
                    <i class="fa-solid fa-bookmark mr-2"></i> Koleksi Favorit
                </a>
            ` : ''}
            ${user && user.role === 'admin' ? `
                <a href="admin/index.html" class="block px-3 py-2 rounded-lg text-base font-semibold text-indigo-600 bg-indigo-50">
                    <i class="fa-solid fa-chart-line mr-2"></i> Panel Administrator
                </a>
            ` : ''}
        </div>
    </nav>
    `;

    const el = document.getElementById('global-header');
    if (el) el.innerHTML = headerHtml;
}

function renderFooter() {
    const user = Store.getCurrentUser();
    const footerHtml = `
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-16">
        <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 lg:px-12 2xl:px-16 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: About -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-book-bookmark text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-white">PerpusDigital</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Sistem Perpustakaan Digital Berbasis Web modern untuk mendukung literasi sekolah dan kemudahan peminjaman buku serta pembelian online (BNSP BookStore & Pra-USK RPL).
                    </p>
                    <div class="flex flex-wrap gap-2 text-slate-400">
                        <span class="inline-flex items-center text-xs bg-slate-800 px-3 py-1 rounded-full"><i class="fa-solid fa-shield-halved mr-1 text-emerald-400"></i> Standar Uji Kompetensi BNSP</span>
                        <span class="inline-flex items-center text-xs bg-slate-800 px-3 py-1 rounded-full"><i class="fa-solid fa-code mr-1 text-indigo-400"></i> Pure HTML Edition</span>
                    </div>
                </div>

                <!-- Col 2: Navigasi -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigasi Cepat</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="index.html" class="hover:text-indigo-400 transition">Beranda</a></li>
                        <li><a href="katalog.html" class="hover:text-indigo-400 transition">Katalog Buku</a></li>
                        <li><a href="about.html" class="hover:text-indigo-400 transition">About Us (Tentang Kami)</a></li>
                        ${!user || user.role !== 'admin' ? `
                        <li><a href="contact.html" class="hover:text-indigo-400 transition">Contact to Admin</a></li>
                        ` : ''}
                        <li><a href="login.html" class="hover:text-indigo-400 transition">Masuk Akun</a></li>
                        <li><a href="register.html" class="hover:text-indigo-400 transition">Pendaftaran Anggota</a></li>
                    </ul>
                </div>

                <!-- Col 3: Jam Layanan & Kontak -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Layanan Sirkulasi</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex items-center space-x-2">
                            <i class="fa-regular fa-clock text-indigo-400"></i>
                            <span>Senin - Jumat: 07.30 - 15.30 WIB</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-location-dot text-indigo-400"></i>
                            <span>Gedung Perpustakaan Lantai 2</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fa-solid fa-phone text-indigo-400"></i>
                            <span>(021) 555-0199</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500">
                <p>&copy; 2026 PerpusDigital - Projek Ujian Sertifikasi Kompetensi (Pra-USK RPL & BNSP).</p>
                <p class="mt-2 sm:mt-0">Dibuat dengan dedikasi untuk kemajuan literasi digital.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Quick Contact / Chat Admin Button -->
    ${!user || user.role !== 'admin' ? `
    <div class="fixed bottom-6 right-6 z-40 group">
        <a href="contact.html" class="flex items-center space-x-2.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-full shadow-xl shadow-indigo-600/30 hover:scale-105 transition-all">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            <i class="fa-solid fa-headset text-base"></i>
            <span class="text-xs font-bold hidden sm:inline-block">Contact Admin</span>
        </a>
    </div>
    ` : ''}
    `;

    const el = document.getElementById('global-footer');
    if (el) el.innerHTML = footerHtml;
}

// User demo switcher helpers
function setDemoUser(role) {
    const users = JSON.parse(localStorage.getItem(STORAGE_KEYS.USERS) || '[]');
    let target = null;
    if (role === 'admin') {
        target = users.find(u => u.role === 'admin') || { id: 1, name: 'Administrator Utama', username: 'admin', email: 'admin@perpus.test', role: 'admin' };
    } else {
        target = users.find(u => u.role === 'peminjam') || { id: 2, name: 'Budi Santoso', username: 'budi', email: 'budi@perpus.test', role: 'peminjam' };
    }
    Store.setCurrentUser(target);
    Store.toast(`Beralih akun ke: ${target.name} (${target.role.toUpperCase()})`, 'info');
    setTimeout(() => location.reload(), 300);
}

function logoutDemo() {
    Store.logout();
    Store.toast('Berhasil keluar dari akun.', 'info');
    setTimeout(() => {
        window.location.href = 'login.html';
    }, 400);
}

// Listen to cart and user updates
window.addEventListener('perpus_cart_updated', () => {
    const badge = document.getElementById('nav-cart-badge');
    if (badge) {
        const count = Store.getCartCount();
        badge.innerText = count > 99 ? '99+' : count;
        if (count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }
});

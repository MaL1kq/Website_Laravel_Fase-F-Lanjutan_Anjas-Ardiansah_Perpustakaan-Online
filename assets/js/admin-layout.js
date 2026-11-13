/**
 * PerpusDigital - Admin Panel Global Layout & Sidebar Renderer
 * Features zero scrollbar (clean, sleek, auto-fitted) and reactive badges
 */

function renderAdminSidebar(activePage = 'dashboard') {
    const user = Store.getCurrentUser() || { name: 'Administrator Utama', email: 'admin@perpus.test', role: 'admin' };
    const messages = Store.getMessages();
    const pesanBaruCount = messages.filter(m => m.status === 'belum_dibaca').length;

    const isDash = activePage === 'dashboard';
    const isKat = activePage === 'kategori';
    const isBuku = activePage === 'buku';
    const isPesanan = activePage === 'pesanan';
    const isLapJual = activePage === 'laporan-penjualan';
    const isTrans = activePage === 'transaksi';
    const isLapPinjam = activePage === 'laporan-peminjaman';
    const isPesan = activePage === 'pesan';
    const isUsers = activePage === 'users';

    const sidebarHtml = `
    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-xl">
        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-6 bg-slate-950/40 border-b border-slate-800/80">
            <a href="index.html" class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-900/50">
                    <i class="fa-solid fa-book-bookmark text-base"></i>
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-wide">PerpusDigital</h1>
                    <span class="text-[10px] text-indigo-400 font-semibold tracking-wider uppercase">
                        Administrator
                    </span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu with .no-scrollbar (Scrollbar vertikal dihilangkan total) -->
        <div class="flex-1 overflow-y-auto no-scrollbar px-4 py-3 space-y-3">
            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Utama</p>
                <nav class="space-y-0.5">
                    <a href="index.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isDash ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-gauge-high w-6 text-base"></i> Dashboard
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Master Data</p>
                <nav class="space-y-0.5">
                    <a href="kategori.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isKat ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-layer-group w-6 text-base"></i> Kategori Buku
                    </a>
                    <a href="buku.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isBuku ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-book w-6 text-base"></i> Data Buku & Harga
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Toko Buku Online</p>
                <nav class="space-y-0.5">
                    <a href="pesanan.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isPesanan ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-cart-flatbed w-6 text-base"></i> Pesanan Masuk
                    </a>
                    <a href="laporan-penjualan.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isLapJual ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-chart-line w-6 text-base"></i> Laporan Penjualan
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Perpustakaan</p>
                <nav class="space-y-0.5">
                    <a href="transaksi.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isTrans ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-arrow-right-arrow-left w-6 text-base"></i> Peminjaman Buku
                    </a>
                    <a href="laporan-peminjaman.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isLapPinjam ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-file-invoice w-6 text-base"></i> Laporan Peminjaman
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Layanan & Komunikasi</p>
                <nav class="space-y-0.5">
                    <a href="pesan.html" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium transition ${isPesan ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <span class="flex items-center">
                            <i class="fa-solid fa-comments w-6 text-base"></i> Pesan Masuk
                        </span>
                        ${pesanBaruCount > 0 ? `
                            <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full animate-pulse">
                                ${pesanBaruCount}
                            </span>
                        ` : ''}
                    </a>
                </nav>
            </div>

            <div>
                <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Manajemen Sistem</p>
                <nav class="space-y-0.5">
                    <a href="users.html" class="flex items-center px-3 py-2 rounded-xl text-sm font-medium transition ${isUsers ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white'}">
                        <i class="fa-solid fa-users-gear w-6 text-base"></i> Kelola Pengguna
                    </a>
                </nav>
            </div>
        </div>

        <!-- User Profile & Logout in Sidebar Footer -->
        <div class="p-4 bg-slate-950/60 border-t border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex-shrink-0 flex items-center justify-center font-bold text-sm">
                        ${user.name ? user.name.charAt(0) : 'A'}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">${user.name || 'Administrator'}</p>
                        <p class="text-[11px] text-slate-400 truncate">${user.email || 'admin@perpus.test'}</p>
                    </div>
                </div>
                <button onclick="adminLogout()" title="Keluar" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </div>
        </div>
    </aside>
    `;

    const el = document.getElementById('admin-sidebar-container');
    if (el) el.innerHTML = sidebarHtml;
}

function renderAdminTopBar(title = 'Dashboard') {
    const user = Store.getCurrentUser() || { name: 'Administrator Utama', role: 'admin' };
    const topBarHtml = `
    <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between z-10">
        <div class="flex items-center space-x-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <h2 class="text-base sm:text-lg font-bold text-slate-800">${title}</h2>
        </div>

        <div class="flex items-center space-x-3">
            <a href="../index.html" target="_blank" class="px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition flex items-center">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-[10px]"></i> Lihat Web Publik
            </a>
            <div class="h-6 w-px bg-slate-200"></div>
            <div class="flex items-center space-x-2 text-right">
                <div class="hidden sm:block">
                    <p class="text-xs font-bold text-slate-800 leading-tight">${user.name}</p>
                    <span class="text-[10px] uppercase font-bold text-indigo-600">Administrator</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                    ${user.name ? user.name.charAt(0) : 'A'}
                </div>
            </div>
        </div>
    </header>
    `;

    const el = document.getElementById('admin-topbar-container');
    if (el) el.innerHTML = topBarHtml;
}

function adminLogout() {
    Store.logout();
    Store.toast('Berhasil keluar dari sesi Administrator.', 'info');
    setTimeout(() => {
        window.location.href = '../login.html';
    }, 400);
}

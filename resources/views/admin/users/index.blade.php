@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - PerpusDigital')

@section('content')
<div class="space-y-6" x-data="{ modalTambah: false, modalEdit: false, editUser: { id: '', name: '', username: '', email: '', role: 'peminjam', no_telepon: '', alamat: '' } }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data akun Administrator dan Siswa/Anggota perpustakaan (2 Peran).</p>
        </div>
        <button @click="modalTambah = true" type="button" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-user-plus mr-1.5"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="relative w-full sm:w-72">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, username, email..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>

            <select name="role" onchange="this.form.submit()" class="w-full sm:w-auto px-3 py-2 rounded-xl border border-slate-300 text-xs bg-slate-50 text-slate-700 focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Peran (Role)</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                <option value="peminjam" {{ request('role') === 'peminjam' ? 'selected' : '' }}>Siswa / Peminjam</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold hover:bg-indigo-700 transition">
                Filter
            </button>

            @if(request()->anyFilled(['q', 'role']))
                <a href="{{ route('admin.users.index') }}" class="text-xs text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <span class="text-xs text-slate-500">Total: <strong>{{ $users->total() }}</strong> Pengguna</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4">Nama Lengkap</th>
                        <th class="p-4">Username & Email</th>
                        <th class="p-4">Peran (Role)</th>
                        <th class="p-4">No. Telepon / Alamat</th>
                        <th class="p-4 text-center">Pinjaman</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 text-center font-bold text-slate-400">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-indigo-700 font-bold text-xs flex items-center justify-center uppercase">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-xs">{{ $user->name }}</h4>
                                        @if($user->id === Auth::id())
                                            <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">(Akun Anda)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="font-mono font-semibold text-slate-800 block text-xs">{{ $user->username ?? '-' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $user->email }}</span>
                            </td>
                            <td class="p-4">
                                @if($user->role === 'admin')
                                    <span class="bg-purple-100 text-purple-800 font-bold px-2.5 py-0.5 rounded-full text-[10px] uppercase">Administrator</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-800 font-bold px-2.5 py-0.5 rounded-full text-[10px] uppercase">Peminjam</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <p class="text-slate-700 font-medium">{{ $user->no_telepon ?? '-' }}</p>
                                <span class="text-[10px] text-slate-400 line-clamp-1 max-w-[180px]">{{ $user->alamat ?? '-' }}</span>
                            </td>
                            <td class="p-4 text-center font-bold text-slate-700">
                                {{ $user->peminjaman_count }}x
                            </td>
                            <td class="p-4 text-right space-x-1.5 whitespace-nowrap">
                                <button type="button"
                                    @click="editUser = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', username: '{{ addslashes($user->username) }}', email: '{{ addslashes($user->email) }}', role: '{{ $user->role }}', no_telepon: '{{ addslashes($user->no_telepon) }}', alamat: '{{ addslashes($user->alamat) }}' }; modalEdit = true"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Pengguna">
                                    <i class="fa-solid fa-user-pen"></i>
                                </button>
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Pengguna">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Belum ada pengguna ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Tambah Pengguna -->
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="modalTambah = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-900 text-sm">Tambah Pengguna Baru</h3>
                    <button @click="modalTambah = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Ahmad Fauzi"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Username</label>
                            <input type="text" name="username" required placeholder="ahmad123"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Peran (Role)</label>
                            <select name="role" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                                <option value="admin">Administrator</option>
                                <option value="peminjam" selected>Siswa / Peminjam</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required placeholder="ahmad@gmail.com"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Kata Sandi</label>
                            <input type="password" name="password" required placeholder="Min 6 karakter"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">No. WhatsApp / HP</label>
                            <input type="text" name="no_telepon" placeholder="08123456789"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Alamat Rumah</label>
                        <textarea name="alamat" rows="2" placeholder="Jl. Melati No. 5..."
                            class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="modalTambah = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pengguna -->
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div @click="modalEdit = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <h3 class="font-bold text-slate-900 text-sm">Edit Data Pengguna</h3>
                    <button @click="modalEdit = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form :action="'{{ url('admin/users') }}/' + editUser.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="editUser.name" required
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Username</label>
                            <input type="text" name="username" x-model="editUser.username" required
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Peran (Role)</label>
                            <select name="role" x-model="editUser.role" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-white focus:ring-2 focus:ring-indigo-500">
                                <option value="admin">Administrator</option>
                                <option value="peminjam">Siswa / Peminjam</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editUser.email" required
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Kata Sandi Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tak diubah"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-700 mb-1">No. WhatsApp / HP</label>
                            <input type="text" name="no_telepon" x-model="editUser.no_telepon"
                                class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Alamat</label>
                        <textarea name="alamat" x-model="editUser.alamat" rows="2"
                            class="w-full p-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="modalEdit = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Pendaftaran Anggota Baru - PerpusDigital')

@section('content')
<div class="max-w-lg mx-auto my-10 px-4 sm:px-6">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-8">
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-indigo-500 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto shadow-lg shadow-indigo-200 mb-3">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Daftar Anggota</h2>
            <p class="text-sm text-slate-500 mt-1">Buat akun untuk meminjam buku secara online</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('name') border-rose-500 @enderror"
                    placeholder="Contoh: Ahmad Fauzi">
                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('username') border-rose-500 @enderror"
                        placeholder="ahmad123">
                    @error('username') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('email') border-rose-500 @enderror"
                        placeholder="ahmad@gmail.com">
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kata Sandi</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('password') border-rose-500 @enderror"
                        placeholder="Min. 6 karakter">
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Ulangi Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                        placeholder="Ulangi kata sandi">
                </div>
            </div>

            <div>
                <label for="no_telepon" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">No. WhatsApp / HP</label>
                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                    placeholder="081234567890">
            </div>

            <div>
                <label for="alamat" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Alamat Rumah</label>
                <textarea name="alamat" id="alamat" rows="2"
                    class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                    placeholder="Jl. Melati No. 5...">{{ old('alamat') }}</textarea>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-200 transition duration-150 flex items-center justify-center mt-2">
                <span>Daftar Akun Sekarang</span>
                <i class="fa-solid fa-user-check ml-2 text-xs"></i>
            </button>
        </form>

        <p class="text-center text-xs text-slate-500 mt-6">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection

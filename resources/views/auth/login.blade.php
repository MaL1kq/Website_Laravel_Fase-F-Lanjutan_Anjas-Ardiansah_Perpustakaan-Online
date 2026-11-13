@extends('layouts.app')

@section('title', 'Masuk ke PerpusDigital')

@section('content')
<div class="max-w-md mx-auto my-12 px-4 sm:px-6">
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-8">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-indigo-500 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto shadow-lg shadow-indigo-200 mb-3">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Selamat Datang</h2>
            <p class="text-sm text-slate-500 mt-1">Masuk ke akun Perpustakaan Digital Anda</p>
        </div>

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4" autocomplete="off">
            @csrf
            <div>
                <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email atau Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('login') border-rose-500 @enderror"
                        placeholder="Contoh: admin atau budi@perpus.test">
                </div>
                @error('login')
                    <p class="text-xs text-rose-600 mt-1.5 flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition @error('password') border-rose-500 @enderror"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-xs text-rose-600 mt-1.5 flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm pt-1">
                <label class="flex items-center text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-xs font-medium">Ingat saya <span class="text-slate-400 font-normal">(tetap login di web)</span></span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-200 transition duration-150 flex items-center justify-center">
                <span>Masuk Sekarang</span>
                <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
            </button>
        </form>

        <!-- Footer Link -->
        <p class="text-center text-xs text-slate-500 mt-6">
            Belum memiliki akun anggota?
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email atau Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $loginInput,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            return $this->redirectBasedOnRole($user)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->withInput($request->only('login'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'alamat' => 'nullable|string|max:500',
            'no_telepon' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, tanda hubung, atau garis bawah.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Pendaftaran akun berhasil! Selamat datang di Perpustakaan Digital.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdminOrPetugas()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    }
}

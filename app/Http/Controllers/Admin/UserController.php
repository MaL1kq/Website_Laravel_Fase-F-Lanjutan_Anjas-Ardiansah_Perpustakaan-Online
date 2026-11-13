<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('peminjaman');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', $keyword)
                  ->orWhere('username', 'like', $keyword)
                  ->orWhere('email', 'like', $keyword)
                  ->orWhere('no_telepon', 'like', $keyword);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,peminjam',
            'alamat' => 'nullable|string|max:500',
            'no_telepon' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,peminjam',
            'alamat' => 'nullable|string|max:500',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => strtolower($request->email),
            'role' => $request->role,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        // 1. Cek jika pengguna masih memiliki transaksi peminjaman aktif
        if ($user->peminjaman()->whereIn('status_peminjaman', ['menunggu', 'dipinjam', 'terlambat'])->exists()) {
            return back()->with('error', 'Pengguna ini tidak dapat dihapus karena masih memiliki transaksi peminjaman buku aktif (menunggu, dipinjam, atau terlambat).');
        }

        // 2. Cek jika pengguna masih memiliki transaksi pesanan pembelian buku yang aktif
        if ($user->pesanan()->whereIn('status', ['menunggu_pembayaran', 'diproses', 'dikirim'])->exists()) {
            return back()->with('error', 'Pengguna ini tidak dapat dihapus karena masih memiliki transaksi pesanan pembelian buku yang sedang aktif (menunggu pembayaran, diproses, atau dikirim).');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

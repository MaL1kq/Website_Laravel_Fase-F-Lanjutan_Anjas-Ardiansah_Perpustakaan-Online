<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanKontak;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PesanAdminController extends Controller
{
    /**
     * Daftar Pesan Masuk dari Pengguna (Contact to Admin)
     */
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $query = PesanKontak::with('user');

        if ($statusFilter && in_array($statusFilter, ['belum_dibaca', 'dibaca', 'dibalas'])) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%")
                  ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        $pesanList = $query->latest()->paginate(10)->withQueryString();

        $totalBelumDibaca = PesanKontak::where('status', 'belum_dibaca')->count();
        $totalDibalas = PesanKontak::where('status', 'dibalas')->count();
        $totalSemua = PesanKontak::count();

        return view('admin.pesan.index', compact('pesanList', 'totalBelumDibaca', 'totalDibalas', 'totalSemua', 'statusFilter', 'search'));
    }

    /**
     * Detail Pesan Masuk & Form Balasan
     */
    public function show($id)
    {
        $pesan = PesanKontak::with('user')->findOrFail($id);

        if ($pesan->status === 'belum_dibaca') {
            $pesan->update([
                'status' => 'dibaca',
                'dibaca_pada' => Carbon::now(),
            ]);
        }

        return view('admin.pesan.show', compact('pesan'));
    }

    /**
     * Balas Pesan Pengguna
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan_admin' => 'required|string|min:3|max:3000',
        ]);

        $pesan = PesanKontak::findOrFail($id);

        $pesan->update([
            'balasan_admin' => $request->balasan_admin,
            'status' => 'dibalas',
            'dibalas_pada' => Carbon::now(),
        ]);

        return redirect()->route('admin.pesan.show', $pesan->id)
            ->with('success', 'Balasan berhasil dikirimkan dan disimpan!');
    }

    /**
     * Hapus Pesan
     */
    public function destroy($id)
    {
        $pesan = PesanKontak::findOrFail($id);
        $pesan->delete();

        return redirect()->route('admin.pesan.index')
            ->with('success', 'Pesan berhasil dihapus dari sistem.');
    }
}

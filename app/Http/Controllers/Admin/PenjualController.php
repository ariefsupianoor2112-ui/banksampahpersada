<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenjualController extends Controller
{
    public function index()
    {
        $penjual = User::where('role', 'penjual')->latest()->get();
        return view('admin.penjual.index', compact('penjual'));
    }

    public function destroy($id)
    {
        $user = User::where('role', 'penjual')->findOrFail($id);

        // Hapus transaksi terkait jika ada
        if (method_exists($user, 'transaksis')) {
            $user->transaksis()->delete();
        }

        $user->delete();

        return redirect()->back()->with('status', 'Data nasabah/penjual berhasil dihapus!');
    }
}

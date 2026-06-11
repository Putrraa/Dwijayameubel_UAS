<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\CustomOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil dan riwayat pesanan
     */
    public function index()
    {
        $user = auth()->user();

        // Jika Role adalah Customer
        if ($user->role == 'customer') {

            // Riwayat pesanan biasa
            $riwayat_pesanan = Pesanan::where('user_id', $user->id)
                ->where('status', '!=', 0)
                ->orderBy('tanggal', 'desc')
                ->get();

            // Riwayat custom order
            $custom_orders = CustomOrder::where('user_id', $user->id)
                ->latest()
                ->get();

            // Kirim ke view
            return view('profile_c', compact(
                'riwayat_pesanan',
                'custom_orders'
            ));
        }

        // Jika Role adalah Admin
        elseif ($user->role == 'admin') {

            return view('profile');
        }

        // Jika Role adalah Kasir
        elseif ($user->role == 'kasir') {

            return view('profile_k');
        }

        return redirect('/');
    }

    /**
     * Update Password User
     */
    public function updatePassword(Request $request)
    {
        // Validasi Input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $user = User::findOrFail(Auth::id());

        // Verifikasi Password Lama
        if (!Hash::check($request->current_password, $user->password)) {

            return back()->withErrors([
                'current_password' => 'Password lama salah!'
            ]);
        }

        // Update Password
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with(
            'success',
            'Password berhasil diupdate!'
        );
    }

   public function getDetail($id)
{
    try {
        $pesanan = \DB::table('pesanan')
            ->where('id', $id)
            ->where('user_id', \Auth::id())
            ->where('status', '>', 0)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'error' => 'Data pesanan tidak ditemukan'
            ], 404);
        }

        $details = \DB::table('detail_pesanan')
            ->leftJoin('barang', 'barang.id', '=', 'detail_pesanan.barang_id')
            ->where('detail_pesanan.pesanan_id', $id)
            ->select(
                'barang.nama_barang',
                'barang.harga',
                'detail_pesanan.jumlah',
                'detail_pesanan.jumlah_harga'
            )
            ->get();

        return response()->json([
            'pesanan' => [
                'kode' => $pesanan->kode,
                'tanggal' => \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y H:i'),
                'nama_penerima' => $pesanan->nama_penerima,
                'no_telepon' => $pesanan->no_telepon,
                'alamat' => $pesanan->alamat,
                'kota' => $pesanan->kota,
                'kode_pos' => $pesanan->kode_pos,
                'metode_pembayaran' => $pesanan->metode_pembayaran
                    ? strtoupper(str_replace('_', ' ', $pesanan->metode_pembayaran))
                    : 'BELUM ADA',
                'catatan' => $pesanan->catatan,
            ],
            'details' => $details,
            'total' => number_format($pesanan->jumlah_harga, 0, ',', '.')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
}
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
     * Update alamat/profil pengiriman User (Customer)
     * Supaya saat checkout/custom order tidak perlu isi alamat berulang.
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            'no_telepon' => 'required|string|max:20',
            'alamat'     => 'required|string',
            'kota'       => 'required|string|max:255',
            'kode_pos'   => 'required|string|max:10',
        ]);

        $user = Auth::user();

        $user->update([
            'no_telepon' => $request->no_telepon,
            'alamat'     => $request->alamat,
            'kota'       => $request->kota,
            'kode_pos'   => $request->kode_pos,
        ]);

        return back()->with('success', 'Alamat berhasil disimpan!');
    }

    /**
     * Customer mengonfirmasi pesanan reguler sudah sampai/diterima.
     * Status hanya bisa diubah jadi "Selesai" oleh customer sendiri,
     * bukan oleh kasir.
     */
    public function terimaPesanan($id)
    {
        $pesanan = Pesanan::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 2) // hanya bisa dikonfirmasi jika sudah "Dikirim"
            ->first();

        if (!$pesanan) {
            return back()->with('error', 'Pesanan tidak ditemukan atau belum bisa dikonfirmasi selesai.');
        }

        $pesanan->status = 3; // Selesai
        $pesanan->save();

        return back()->with('success', 'Terima kasih! Pesanan dikonfirmasi selesai.');
    }

    /**
     * Customer mengonfirmasi custom order sudah sampai/diterima.
     */
    public function terimaCustomOrder($id)
    {
        $custom = CustomOrder::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'diproses') // hanya bisa dikonfirmasi jika sedang diproses/dikirim
            ->first();

        if (!$custom) {
            return back()->with('error', 'Custom order tidak ditemukan atau belum bisa dikonfirmasi selesai.');
        }

        $custom->status = 'selesai';
        $custom->save();

        return back()->with('success', 'Terima kasih! Custom order dikonfirmasi selesai.');
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

        $metodePembayaran = $pesanan->metode_pembayaran;

        if ($pesanan->payment_status === 'paid' && (!$metodePembayaran || $metodePembayaran === 'midtrans')) {
            $metodePembayaran = $this->syncMetodePembayaranFromMidtrans($pesanan) ?: $metodePembayaran ?: 'midtrans';
        }

        return response()->json([
            'pesanan' => [
                'kode' => $pesanan->kode,
                'tanggal' => \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y H:i'),
                'nama_penerima' => $pesanan->nama_penerima,
                'no_telepon' => $pesanan->no_telepon,
                'alamat' => $pesanan->alamat,
                'kota' => $pesanan->kota,
                'kode_pos' => $pesanan->kode_pos,
                'payment_status' => $pesanan->payment_status,
                'metode_pembayaran' => $metodePembayaran
                    ? $this->formatMetodeBayar($metodePembayaran)
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

private function syncMetodePembayaranFromMidtrans($pesanan)
{
    try {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');

        $status = \Midtrans\Transaction::status($pesanan->kode);
        $metode = $this->resolveMidtransPaymentMethod($status, $status->payment_type ?? null);

        if ($metode && $metode !== 'midtrans') {
            \DB::table('pesanan')
                ->where('id', $pesanan->id)
                ->update([
                    'metode_pembayaran' => $metode,
                    'transaction_id' => $status->transaction_id ?? $pesanan->transaction_id,
                    'updated_at' => now(),
                ]);

            return $metode;
        }
    } catch (\Exception $e) {
        return null;
    }

    return null;
}

private function resolveMidtransPaymentMethod($source, $fallback = null)
{
    $paymentType = $source->payment_type ?? $fallback;

    if ($paymentType === 'bank_transfer') {
        $bank = null;

        if (!empty($source->va_numbers) && isset($source->va_numbers[0]->bank)) {
            $bank = $source->va_numbers[0]->bank;
        } elseif (!empty($source->permata_va_number)) {
            $bank = 'permata';
        }

        if ($bank) {
            return strtolower($bank) . '_va';
        }
    }

    return $paymentType ?: 'midtrans';
}

private function formatMetodeBayar($metode)
{
    return match ($metode) {
        'qris' => 'QRIS',
        'gopay' => 'GoPay',
        'bank_transfer' => 'Bank Transfer',
        'bca_va' => 'BCA Virtual Account',
        'bni_va' => 'BNI Virtual Account',
        'bri_va' => 'BRI Virtual Account',
        'permata_va' => 'Permata Virtual Account',
        'midtrans' => 'Midtrans',
        default => strtoupper(str_replace('_', ' ', $metode)),
    };
}
}
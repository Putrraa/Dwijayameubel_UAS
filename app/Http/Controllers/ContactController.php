<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'message' => 'required|string|max:2000',
        ]);

        $isiPesan =
            "Pesan baru dari halaman Contact Dwijaya Meubel\n\n" .
            "Nama: " . $data['name'] . "\n" .
            "Email: " . $data['email'] . "\n\n" .
            "Pesan:\n" . $data['message'];

        Mail::raw($isiPesan, function ($message) use ($data) {
            $message->to('dwijayameubel@gmail.com')
                    ->subject('Pesan Contact dari ' . $data['name'])
                    ->replyTo($data['email'], $data['name']);
        });

        return back()->with('success', 'Pesan berhasil dikirim. Terima kasih sudah menghubungi kami!');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
class detail_barang extends Controller
{
        public function detail($id)
    {
        $data = barang::findOrFail($id);
        return view('customer.detail', compact('data'));
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class customercontroller extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function shop()
    {
        return view('customer.shop');
    }

    public function about()
    {
        return view('customer.about');
    }

    public function contact()
    {
        return view('customer.contact');
    }

    public function chart()
    {
        return view('customer.chart');
    }
}

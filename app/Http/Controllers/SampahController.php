<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;

class SampahController extends Controller
{
    public function index()
    {
        $data_sampah = JenisSampah::orderBy('nama_sampah')->get();
        return view('index', compact('data_sampah'));
    }
}

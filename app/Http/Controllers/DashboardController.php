<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Koleksi;
use App\Kunjungan;
use App\Pinjaman;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('chart');
    }

    public function koleksi()
    {
        return view('koleksi');
    }

    public function pinjaman()
    {
        return view('pinjaman');
    }

    public function kunjungan()
    {
        return view('kunjungan');
    }

}

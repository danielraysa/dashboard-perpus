<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Koleksi;
use App\Kunjungan;
use App\Pinjaman;
use App\Http\Controllers\Api\ChartController;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $tgl_sekarang = date('Y-m-d H:i:s');
        $koleksi = Koleksi::select('judul', DB::raw('COUNT(*) AS jumlah'))->groupBy('judul')->get();
        $items = Koleksi::all();
        $pinjaman = Pinjaman::where('tanggal_kembali', '>', $tgl_sekarang)->get();
        // $tersedia = Pinjaman::where('tgl_pinjam', '<', $tgl_sekarang)->get();
        $tersedia = Koleksi::all();
        return view('dashboard', compact('koleksi', 'items', 'pinjaman', 'tersedia'));
    }

    public function koleksi()
    {
        return view('koleksi');
    }

    public function pinjaman(Request $request)
    {
        // $chart = new ChartController;
        // $chart_data = $chart->graph_pinjaman_baru($request);
        // dd($chart_data);
        // return view('pinjaman', compact('chart_data'));
        return view('pinjaman');
    }

    public function kunjungan()
    {
        return view('kunjungan');
    }

}

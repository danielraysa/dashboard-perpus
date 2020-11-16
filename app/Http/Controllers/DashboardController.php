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

    }

    public function graph_data(Request $request)
    {
        $thn_skrg = 2018;
        if(isset($request->pilih_tahun)){
            $thn_skrg = $request->pilih_tahun;
        }
        if(isset($request->per_prodi)){
            $koleksi = Koleksi::select('prodi', DB::raw('COUNT(*) AS total'))->groupBy('prodi')->whereRaw("YEAR(tgl_cetak) = '".$thn_skrg."'")->get();
        }
        if(isset($request->per_bulan)){
            $koleksi = Koleksi::select(DB::raw('MONTH(tgl_cetak) AS bln'), DB::raw('MONTHNAME(tgl_cetak) AS bulan'), DB::raw('COUNT(*) AS total'))->groupBy(DB::raw('MONTH(tgl_cetak)'), DB::raw('MONTHNAME(tgl_cetak)'))->whereRaw("YEAR(tgl_cetak) = '".$thn_skrg."'")->orderBy(DB::raw('MONTH(tgl_cetak)'))->get();
        }
        // dd($koleksi);
        // $pinjaman = Pinjaman::all();
        // $kunjungan = Kunjungan::all();
        return response()->json($koleksi);
    }

    public function detail_graph()
    {

    }
}

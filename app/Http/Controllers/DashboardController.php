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

        $koleksi = Koleksi::select(DB::raw("YEAR(tgl_cetak) AS tahun"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_cetak)"))->orderBy(DB::raw("YEAR(tgl_cetak)"))->get();
        if(isset($request->per_prodi)){
            $koleksi = Koleksi::select('prodi', DB::raw('COUNT(*) AS total'))->groupBy('prodi')->get();
        }
        if(isset($request->pilih_tahun)){
            $thn_skrg = $request->pilih_tahun;
            $koleksi = Koleksi::select(DB::raw("MONTH(tgl_cetak) AS bln"), DB::raw("DATE_FORMAT(tgl_cetak, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(tgl_cetak)"), DB::raw("DATE_FORMAT(tgl_cetak, '%b')"))->whereRaw("YEAR(tgl_cetak) = '".$thn_skrg."'")->orderBy(DB::raw("MONTH(tgl_cetak)"))->get();
        }
        // dd($koleksi);
        // $pinjaman = Pinjaman::all();
        // $kunjungan = Kunjungan::all();
        return response()->json($koleksi);
    }

    public function graph_kunjungan(Request $request)
    {
        $kunjungan = Kunjungan::select(DB::raw("YEAR(waktu_masuk) AS tahun"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(waktu_masuk)"))->orderBy(DB::raw("YEAR(waktu_masuk)"))->get();
        if(isset($request->pilih_tahun)){
            $thn = $request->pilih_tahun;
            $kunjungan = Kunjungan::select(DB::raw("MONTH(waktu_masuk) AS bln"), DB::raw("DATE_FORMAT(waktu_masuk, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(waktu_masuk)"), DB::raw("DATE_FORMAT(waktu_masuk, '%b')"))->whereRaw("YEAR(waktu_masuk) = '".$thn."'")->orderBy(DB::raw("MONTH(waktu_masuk)"))->get();
        }
        // $kunjungan = Kunjungan::all();
        return response()->json($kunjungan);
    }

    public function detail_graph(Request $request)
    {

    }
}

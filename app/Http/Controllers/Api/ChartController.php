<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Koleksi;
use App\Kunjungan;
use App\Pinjaman;

class ChartController extends Controller
{
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
        if(isset($request->tgl_awal)){
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $koleksi = Koleksi::select(DB::raw("YEAR(tgl_cetak) AS tahun"), DB::raw("COUNT(*) AS total"))->whereBetween("tgl_cetak", [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(tgl_cetak)"))->orderBy(DB::raw("YEAR(tgl_cetak)"))->get();
            /* if(isset($request->pilih_tahun) && $request->pilih_tahun != null){
                $thn_skrg = $request->pilih_tahun;
                $koleksi = Koleksi::select(DB::raw("YEAR(tgl_cetak) AS tahun"), DB::raw("COUNT(*) AS total"))->whereBetween("tgl_cetak", [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(tgl_cetak)"))->orderBy(DB::raw("YEAR(tgl_cetak)"))->get();
            } */
        }
        // dd($koleksi);
        return response()->json($koleksi);
    }

    public function graph_kunjungan(Request $request)
    {
        $kunjungan = Kunjungan::select(DB::raw("YEAR(waktu_masuk) AS tahun"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(waktu_masuk)"))->orderBy(DB::raw("YEAR(waktu_masuk)"))->get();
        if(isset($request->pilih_tahun)){
            $thn = $request->pilih_tahun;
            $kunjungan = Kunjungan::select(DB::raw("MONTH(waktu_masuk) AS bln"), DB::raw("DATE_FORMAT(waktu_masuk, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(waktu_masuk)"), DB::raw("DATE_FORMAT(waktu_masuk, '%b')"))->whereRaw("YEAR(waktu_masuk) = '".$thn."'")->orderBy(DB::raw("MONTH(waktu_masuk)"))->get();
        }
        if(isset($request->tgl_awal)){
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $kunjungan = Kunjungan::select(DB::raw("YEAR(waktu_masuk) AS tahun"), DB::raw("COUNT(*) AS total"))->whereBetween("waktu_masuk", [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(waktu_masuk)"))->orderBy(DB::raw("YEAR(waktu_masuk)"))->get();
        }
        // $kunjungan = Kunjungan::all();
        return response()->json($kunjungan);
    }

    public function graph_pinjaman(Request $request)
    {
        $temp = array();
        $pinjaman = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_pinjam)"))->orderBy(DB::raw("YEAR(tgl_pinjam)"))->get();
        $pinjaman_tahun = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"))->groupBy(DB::raw("YEAR(tgl_pinjam)"))->orderBy(DB::raw("YEAR(tgl_pinjam)"))->pluck('tahun');
        // $pinjaman_total = Pinjaman::select(DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_pinjam)"))->orderBy(DB::raw("YEAR(tgl_pinjam)"))->pluck('total');
        // array_push($temp, array('tahun' => $pinjaman_tahun));
        // array_push($temp, array('total' => $pinjaman_total));
        // return response()->json($temp);
        foreach($pinjaman as $pinjam){
            // $pinjaman2 = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"), "id", DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_pinjam)"), "id")->orderBy(DB::raw("YEAR(tgl_pinjam)"))->get();
            $pinjaman2 = Pinjaman::select("id", DB::raw("CASE WHEN id = 1 THEN 'Buku' WHEN id = 2 THEN 'Majalah' WHEN id = 3 THEN 'Software' ELSE 'Karya Ilmiah' END AS jenis"), DB::raw("COUNT(*) AS total"))->groupBy("id")->whereRaw("YEAR(tgl_pinjam) = '".$pinjam->tahun."'")->orderBy("id")->get();
            // array_push($temp, array('tahun' => $pinjam->tahun, 'data' => $pinjaman2));
            array_push($temp, array('tahun' => $pinjam->tahun, 'data' => $pinjaman2));
        }
        if(isset($request->pilih_tahun)){
            $thn = $request->pilih_tahun;
            $pinjaman = Pinjaman::select(DB::raw("MONTH(tgl_pinjam) AS bln"), DB::raw("DATE_FORMAT(tgl_pinjam, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(tgl_pinjam)"), DB::raw("DATE_FORMAT(tgl_pinjam, '%b')"))->whereRaw("YEAR(tgl_pinjam) = '".$thn."'")->orderBy(DB::raw("MONTH(tgl_pinjam)"))->get();
        }
        if(isset($request->tgl_awal)){
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;

        }
        // dd($pinjaman);
        return response()->json($temp);
    }
}

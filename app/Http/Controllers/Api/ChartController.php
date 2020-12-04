<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Koleksi;
use App\Kunjungan;
use App\Pinjaman;

setlocale(LC_TIME, 'id_ID');
class ChartController extends Controller
{
    public function graph_data(Request $request)
    {
        $koleksi = Koleksi::select(DB::raw("YEAR(tgl_cetak) AS tahun"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_cetak)"))->orderBy(DB::raw("YEAR(tgl_cetak)"))->get();
        if($request->per_prodi){
            $koleksi = Koleksi::select('prodi', DB::raw('COUNT(*) AS total'))->groupBy('prodi')->get();
        }
        if($request->pilih_tahun){
            $thn_skrg = $request->pilih_tahun;
            $koleksi = Koleksi::select(DB::raw("MONTH(tgl_cetak) AS bln"), DB::raw("DATE_FORMAT(tgl_cetak, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(tgl_cetak)"), DB::raw("DATE_FORMAT(tgl_cetak, '%b')"))->whereRaw("YEAR(tgl_cetak) = '".$thn_skrg."'")->orderBy(DB::raw("MONTH(tgl_cetak)"))->get();
        }
        if($request->tgl_awal){
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $koleksi = Koleksi::select(DB::raw("YEAR(tgl_cetak) AS tahun"), DB::raw("COUNT(*) AS total"))->whereBetween("tgl_cetak", [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(tgl_cetak)"))->orderBy(DB::raw("YEAR(tgl_cetak)"))->get();
            /* if(isset($request->pilih_tahun) && $request->pilih_tahun != null){
                $thn_skrg = $request->pilih_tahun;
                $koleksi = Koleksi::select(DB::raw("YEAR(tgl_cetak) AS tahun"), DB::raw("COUNT(*) AS total"))->whereBetween("tgl_cetak", [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(tgl_cetak)"))->orderBy(DB::raw("YEAR(tgl_cetak)"))->get();
            } */
        }
        if($request->pilih_tahun && $request->tgl_awal){
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $thn_skrg = $request->pilih_tahun;
            $koleksi = Koleksi::select(DB::raw("MONTH(tgl_cetak) AS bln"), DB::raw("DATE_FORMAT(tgl_cetak, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(tgl_cetak)"), DB::raw("DATE_FORMAT(tgl_cetak, '%b')"))->whereRaw("YEAR(tgl_cetak) = '".$thn_skrg."'")->whereBetween("tgl_cetak", [$tgl_awal, $tgl_akhir])->orderBy(DB::raw("MONTH(tgl_cetak)"))->get();
        }
        // dd($koleksi);
        return response()->json($koleksi);
    }

    public function graph_kunjungan(Request $request)
    {
        $kunjungan = Kunjungan::select(DB::raw("YEAR(waktu_masuk) AS tahun"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(waktu_masuk)"))->orderBy(DB::raw("YEAR(waktu_masuk)"))->get();
        if($request->pilih_tahun){
            $thn = $request->pilih_tahun;
            $kunjungan = Kunjungan::select(DB::raw("MONTH(waktu_masuk) AS bln"), DB::raw("DATE_FORMAT(waktu_masuk, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(waktu_masuk)"), DB::raw("DATE_FORMAT(waktu_masuk, '%b')"))->whereRaw("YEAR(waktu_masuk) = '".$thn."'")->orderBy(DB::raw("MONTH(waktu_masuk)"))->get();
        }
        if($request->tgl_awal){
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $kunjungan = Kunjungan::select(DB::raw("YEAR(waktu_masuk) AS tahun"), DB::raw("COUNT(*) AS total"))->whereBetween("waktu_masuk", [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(waktu_masuk)"))->orderBy(DB::raw("YEAR(waktu_masuk)"))->get();
        }
        if($request->pilih_tahun && $request->tgl_awal){
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $thn_skrg = $request->pilih_tahun;
            $kunjungan = Kunjungan::select(DB::raw("MONTH(waktu_masuk) AS bln"), DB::raw("DATE_FORMAT(waktu_masuk, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("MONTH(waktu_masuk)"), DB::raw("DATE_FORMAT(waktu_masuk, '%b')"))->whereRaw("YEAR(waktu_masuk) = '".$thn."'")->whereBetween("waktu_masuk", [$tgl_awal, $tgl_akhir])->orderBy(DB::raw("MONTH(waktu_masuk)"))->get();
        }
        // $kunjungan = Kunjungan::all();
        return response()->json($kunjungan);
    }

    public function graph_pinjaman(Request $request)
    {
        $pinjaman = array();
        $temp = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"), DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_pinjam)"))->orderBy(DB::raw("YEAR(tgl_pinjam)"))->get();
        $pinjaman_tahun = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"))->groupBy(DB::raw("YEAR(tgl_pinjam)"))->orderBy(DB::raw("YEAR(tgl_pinjam)"))->pluck('tahun');
        
        foreach($temp as $pinjam){
            // $pinjaman2 = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"), "id", DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_pinjam)"), "id")->orderBy(DB::raw("YEAR(tgl_pinjam)"))->get();
            $pinjaman2 = Pinjaman::select("id", DB::raw("CASE WHEN id = 1 THEN 'Buku' WHEN id = 2 THEN 'Majalah' WHEN id = 3 THEN 'Software' ELSE 'Karya Ilmiah' END AS jenis"), DB::raw("COUNT(*) AS total"))->whereRaw("YEAR(tgl_pinjam) = '".$pinjam->tahun."'")->groupBy("id")->orderBy("id")->get();
            array_push($pinjaman, array('tahun' => $pinjam->tahun, 'data' => $pinjaman2));
        }
        if($request->pilih_tahun){
            $thn = $request->pilih_tahun;
            $dataset = $request->dataset;
            $pinjaman = Pinjaman::select(DB::raw("MONTH(tgl_pinjam) AS bln"), DB::raw("DATE_FORMAT(tgl_pinjam, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->whereRaw("YEAR(tgl_pinjam) = '".$thn."'")->where('id', $dataset)->groupBy(DB::raw("MONTH(tgl_pinjam)"), DB::raw("DATE_FORMAT(tgl_pinjam, '%b')"))->orderBy(DB::raw("MONTH(tgl_pinjam)"))->get();
            // dd($pinjaman);
        }
        if($request->tgl_awal){
            $pinjaman = array();
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $temp = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"), DB::raw("COUNT(*) AS total"))->whereBetween('tgl_pinjam', [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(tgl_pinjam)"))->orderBy(DB::raw("YEAR(tgl_pinjam)"))->get();
            $pinjaman_tahun = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"))->whereBetween('tgl_pinjam', [$tgl_awal, $tgl_akhir])->groupBy(DB::raw("YEAR(tgl_pinjam)"))->orderBy(DB::raw("YEAR(tgl_pinjam)"))->pluck('tahun');
            foreach($temp as $pinjam){
                // $pinjaman2 = Pinjaman::select(DB::raw("YEAR(tgl_pinjam) AS tahun"), "id", DB::raw("COUNT(*) AS total"))->groupBy(DB::raw("YEAR(tgl_pinjam)"), "id")->orderBy(DB::raw("YEAR(tgl_pinjam)"))->get();
                $pinjaman2 = Pinjaman::select("id", DB::raw("CASE WHEN id = 1 THEN 'Buku' WHEN id = 2 THEN 'Majalah' WHEN id = 3 THEN 'Software' ELSE 'Karya Ilmiah' END AS jenis"), DB::raw("COUNT(*) AS total"))->whereBetween('tgl_pinjam', [$tgl_awal, $tgl_akhir])->whereRaw("YEAR(tgl_pinjam) = '".$pinjam->tahun."'")->groupBy("id")->orderBy("id")->get();
                array_push($pinjaman, array('tahun' => $pinjam->tahun, 'data' => $pinjaman2));
            }
            
        }
        if($request->tgl_awal && $request->dataset){
            $pinjaman = array();
            $tgl_awal = $request->tgl_awal;
            $tgl_akhir = $request->tgl_akhir;
            $dataset = $request->dataset;
            $pinjaman = Pinjaman::select(DB::raw("MONTH(tgl_pinjam) AS bln"), DB::raw("DATE_FORMAT(tgl_pinjam, '%b') AS bulan"), DB::raw("COUNT(*) AS total"))->whereBetween('tgl_pinjam', [$tgl_awal, $tgl_akhir])->where('id', $dataset)->groupBy(DB::raw("MONTH(tgl_pinjam)"), DB::raw("DATE_FORMAT(tgl_pinjam, '%b')"))->orderBy(DB::raw("MONTH(tgl_pinjam)"))->get();
            
        }
        // dd($pinjaman);
        return response()->json($pinjaman);
    }
    
    public function graph_pinjaman_baru(Request $request)
    {
        // dd($request->all());
        // $tahun = date('Y');
        $tahun = '2018';
        if($request->tahun){
            $tahun = $request->tahun;
        }

        $jenis_koleksi = collect([
            ['id'=>1,'label'=> 'Buku','color' => '#f56954',], 
            ['id'=>2,'label'=> 'Majalah','color' => '#f39c12',],
            ['id'=>3,'label'=> 'Software','color' => '#00a65a',], 
            ['id'=>4,'label'=> 'Karya Ilmiah','color' => '#00c0ef',],
        ]);

        $bulan = array();
        for($m = 1; $m <= 12; $m++){
            array_push($bulan, strftime("%B", mktime(0, 0, 0, $m, 12)));
        }
        $arr_temp = array();
        if(isset($request->jenis_koleksi) && $request->jenis_koleksi != 'all'){
            if(isset($request->prodi) && $request->prodi != 'all'){
                for($i = 1; $i <= count($bulan); $i++){
                    $pinjaman_temp = Pinjaman::select(DB::raw("COUNT(*) AS total"))->whereRaw("SUBSTR(nim, 3, 5) = '".$request->prodi."'")->whereRaw("MONTH(tgl_pinjam) = '".$i."'")->whereRaw("YEAR(tgl_pinjam) = '".$tahun."'")->where('id', $request->jenis_koleksi)->get()->first();
                    array_push($arr_temp, $pinjaman_temp->total);
                }
            }else{
                for($i = 1; $i <= count($bulan); $i++){
                    $pinjaman_temp = Pinjaman::select(DB::raw("COUNT(*) AS total"))->whereRaw("MONTH(tgl_pinjam) = '".$i."'")->whereRaw("YEAR(tgl_pinjam) = '".$tahun."'")->where('id', $request->jenis_koleksi)->get()->first();
                    array_push($arr_temp, $pinjaman_temp->total);
                }
            }
            $dataset = [
                'label' => 'Jumlah',
                'data' => $arr_temp,
                'backgroundColor' => '#00c0ef',
            ];
            $pinjaman = [
                'bulan' => $bulan,
                'dataset' => [
                    $dataset
                ],
            ];
            return response()->json($pinjaman);
        }
        if(isset($request->prodi) && $request->prodi != 'all'){
            $new_dataset = $jenis_koleksi->map(function ($item) use ($request, $bulan, $tahun) {
                $arr_temp = array();
                for($i = 1; $i <= count($bulan); $i++){
                    $pinjaman_temp = Pinjaman::select(DB::raw("COUNT(*) AS total"))->whereRaw("SUBSTR(nim, 3, 5) = '".$request->prodi."'")->whereRaw("MONTH(tgl_pinjam) = '".$i."'")->whereRaw("YEAR(tgl_pinjam) = '".$tahun."'")->where('id', $item['id'])->get()->first();
                    array_push($arr_temp, $pinjaman_temp->total);
                }
                $jenis = [
                    'id' => $item['id'],
                    'label' => $item['label'],
                    'data' => $arr_temp,
                    'backgroundColor' => $item['color'],
                ];
                return $jenis;
            });
            // dd($new_dataset);
        }else{
            $new_dataset = $jenis_koleksi->map(function ($item, $key) use ($bulan, $tahun) {
                $arr_temp = array();
                for($i = 1; $i <= count($bulan); $i++){
                    $pinjaman_temp = Pinjaman::select(DB::raw("COUNT(*) AS total"))->whereRaw("MONTH(tgl_pinjam) = '".$i."'")->whereRaw("YEAR(tgl_pinjam) = '".$tahun."'")->where('id', $item['id'])->get()->first();
                    array_push($arr_temp, $pinjaman_temp->total);
                }
                $jenis = [
                    'id' => $item['id'],
                    'label' => $item['label'],
                    'data' => $arr_temp,
                    'backgroundColor' => $item['color'],
                ];
                return $jenis;
            });
        }
        // dd($new_dataset);
        $pinjaman = [
            'bulan' => $bulan,
            'dataset' => $new_dataset,
            /* 'dataset' => [
                $dataset_buku,
                $dataset_majalah,
                $dataset_software,
                $dataset_karya_ilmiah
            ], */
        ];
        return response()->json($pinjaman);
    }
}

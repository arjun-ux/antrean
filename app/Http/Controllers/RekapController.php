<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RekapController extends Controller
{
    // index admin
    public function rekap_per_pkm(){
        return view('admin_page.rekap_per_pkm');
    }
    // data rekap admin
    public function data_rekap(){

        $data = DB::connection('mysql2')
            ->table('pasien_onsite as po')
            ->join('ref_users as us', 'po.kode_puskesmas', '=', 'us.username')
            ->select(
                'po.kode_puskesmas',
                'us.name as namafaskes',
                'po.tanggalperiksa as TanggalPeriksa',
                DB::raw('COUNT(po.kode_puskesmas) as Onsite')
            )
            ->where('po.flag', '1')
            ->groupBy('po.kode_puskesmas','us.name', 'po.tanggalperiksa')
            ->orderBy('us.name', 'asc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->toJson();
    }

    // data grafik rekap in dashboard admin
    public function getPasienPerPKM(){
        $data = DB::connection('mysql2')
                ->table('pasien_onsite as po')
                ->join('ref_users as us', 'po.kode_puskesmas', '=', 'us.username')
                ->select(
                    'po.kode_puskesmas',
                    'us.name as namafaskes',
                    DB::raw('COUNT(*) as jumlah_pasien_onsite')
                )
                ->groupBy('po.kode_puskesmas', 'us.name')
                ->orderBy('us.name', 'asc')
                ->get();
        return response()->json($data);
    }

    // index rekap client
    public function rekap_pkm(){
        return view('client_page.index');
    }

    // grafik pkm pasien
    public function getPasienOnPKM(){
        $data = DB::connection('mysql2')
                ->table('pasien_onsite as po')
                ->join('ref_users as us', 'po.kode_puskesmas', '=', 'us.username')
                ->join('poli', 'po.kodepoli', '=', 'poli.id_poli_bpjs')
                ->select(
                    'po.kode_puskesmas',
                    'poli.nama_poli as poli',
                    DB::raw('COUNT(*) as jumlah_poli')
                )
                ->where('us.username', Auth::user()->username)
                ->groupBy('po.kode_puskesmas', 'poli.nama_poli')
                ->orderBy('poli.nama_poli', 'asc')
                ->get();
        return response()->json($data);
    }
}

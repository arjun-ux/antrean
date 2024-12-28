<?php

namespace App\Http\Controllers;

use App\Models\PasienOnsiteLaporan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PasienOnsiteLaporanController extends Controller
{
    // data pasien hari sebelumnya
    public function data_pasien_old(Request $request){
        echo json_encode($request->all());
        exit();
        if ($request->ajax()) {
            $data = PasienOnsiteLaporan::query();
            return DataTables::of($data)
                    ->addIndexColumns()
                    ->toJson();
        }
    }
}

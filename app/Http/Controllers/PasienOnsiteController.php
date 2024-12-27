<?php

namespace App\Http\Controllers;

use App\Models\PasienOnsite;
use Yajra\DataTables\Facades\DataTables;

class PasienOnsiteController extends Controller
{
    // data pasien hari ini
    public function data_pasien_today(){
        $data = PasienOnsite::query();
        return DataTables::of($data)
                ->addIndexColumn()
                ->toJson();
    }
    // data pasien hari sebelumnya
}

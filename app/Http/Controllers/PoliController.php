<?php

namespace App\Http\Controllers;

use App\Models\PasienOnsite;
use App\Models\PasienOnsiteLaporan;
use App\Models\Poli;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PoliController extends Controller
{
    // cari data poli
    public function getPoli(Request $request){
        $query = $request->get('q');
        $namapoli = Poli::where('nama_poli', 'LIKE', "%{$query}%")
                    ->paginate(5);
        return response()->json($namapoli);
    }
    // cara berdasarkan poli
    public function selected_poli(Request $request){
        if ($request->ajax()) {
            $startDate = Carbon::parse($request->start)->format('Y-m-d');
            $endDate = Carbon::parse($request->end)->format('Y-m-d');
            // Query untuk mengambil data berdasarkan tanggal dan kode poli
            $data = PasienOnsiteLaporan::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('kodepoli', $request->poli);
            if (Auth::user()->ref_group_id == "2") {
                $data = PasienOnsiteLaporan::query()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('kodepoli', $request->poli)
                    ->where('kode_puskesmas', Auth::user()->username);
            }
            return DataTables::of($data)
                ->addColumn('nama_pkm', function($row) {
                    return $row->user->name;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-M-Y'); // Format tanggal
                })
                ->addIndexColumn()
                ->toJson();
        }
        // Jika bukan AJAX, bisa mengembalikan response error atau redirect
        return response()->json(['error' => 'Invalid request'], 400);
    }

    // cara berdasarkan poli
    public function selected_poli_pasien(Request $request){
        if ($request->ajax()) {
            // mengambil data berdasarkan kode poli
            $data = PasienOnsite::query()
                ->where('kodepoli', $request->poli);
            // ditambah jika authnya sebagai client
            if (Auth::user()->ref_group_id == '2') {
                // ditambah berdasarkan auth username
                $data = PasienOnsite::query()
                    ->where('kodepoli', $request->poli)
                    ->where('kode_puskesmas', Auth::user()->username);
            }
            return DataTables::of($data)
                ->addColumn('nama_pkm', function($row) {
                    return $row->user->name;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-M-Y'); // Format tanggal
                })
                ->addIndexColumn()
                ->toJson();
        }
        // Jika bukan AJAX, bisa mengembalikan response error atau redirect
        return response()->json(['error' => 'Invalid request'], 400);
    }

    // cari data pkm
    public function getPkm(Request $request){
        $query = $request->get('q');
        $namapkm = User::where('name', 'LIKE', "%{$query}%")
                    ->paginate(5);
        return response()->json($namapkm);
    }
    // cara berdasarkan pkm
    public function selected_pkm(Request $request){
        if ($request->ajax()) {
            $startDate = Carbon::parse($request->start)->format('Y-m-d');
            $endDate = Carbon::parse($request->end)->format('Y-m-d');
            // Query untuk mengambil data berdasarkan tanggal dan kode pkm
            $data = PasienOnsiteLaporan::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('kode_puskesmas', $request->username);

            return DataTables::of($data)
                ->addColumn('nama_pkm', function($row) {
                    return $row->user->name;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-M-Y'); // Format tanggal
                })
                ->addIndexColumn()
                ->toJson();
        }
        // Jika bukan AJAX, bisa mengembalikan response error atau redirect
        return response()->json(['error' => 'Invalid request'], 400);
    }

    // cara berdasarkan pkm
    public function selected_pkm_pasien(Request $request){
        if ($request->ajax()) {
            // mengambil data berdasarkan kodepkm dan join dengan table pasien
            $data = PasienOnsite::query()
                ->where('kode_puskesmas', $request->username);

            return DataTables::of($data)
                ->addColumn('nama_pkm', function($row) {
                    return $row->user->name;
                })
                ->editColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->format('d-M-Y'); // Format tanggal
                })
                ->addIndexColumn()
                ->toJson();
        }
        // Jika bukan AJAX, bisa mengembalikan response error atau redirect
        return response()->json(['error' => 'Invalid request'], 400);
    }
}

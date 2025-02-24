<?php

namespace App\Http\Controllers;

use App\Models\PasienOnsiteLaporan;
use App\Models\Poli;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PasienOnsiteLaporanController extends Controller
{
    // data pasien old
    public function data_pasien_old(Request $request) {
        // Memastikan request adalah AJAX
        if ($request->ajax()) {
            $startDate = Carbon::parse($request->start)->startOfDay();
            $endDate = Carbon::parse($request->end)->endOfDay();
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $data = DB::connection('mysql2') // Ganti dengan nama koneksi database lain
                    ->table('pasien_onsite_laporan')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->offset($start)
                    ->limit($length)
                    ->get();

            return DataTables::of($data)
                ->addColumn('nama_pkm', function($row) {
                    // Ambil data dari database utama (mysql) dengan DB::connection
                    $user = DB::connection('mysql')->table('users')
                        ->where('username', $row->kode_puskesmas)
                        ->first();
                    // Jika ditemukan, kembalikan nama, jika tidak kembalikan '-'
                    return $user ? $user->name : '-';
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


    // data pasien old
    public function data_pasien_old_client(Request $request) {
        // Memastikan request adalah AJAX
        if ($request->ajax()) {
            $startDate = Carbon::parse($request->start)->startOfDay();
            $endDate = Carbon::parse($request->end)->endOfDay();
            $data = DB::connection('mysql2') // Ganti dengan nama koneksi database lain
                    ->table('pasien_onsite_laporan')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where('kode_puskesmas', Auth::user()->username)
                    ->get();
            // dd($data);
            return DataTables::of($data)
                ->addColumn('nama_pkm', function($row) {
                    // Ambil data user berdasarkan kode_puskesmas yang merujuk ke username
                    $user = DB::connection('mysql')->table('users')
                    ->where('username', $row->kode_puskesmas)
                    ->first();

                // Jika data user ditemukan, kembalikan nama, jika tidak kembalikan '-'
                return $user ? $user->name : '-';
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

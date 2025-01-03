<?php

namespace App\Http\Controllers;

use App\Models\PasienOnsite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PasienOnsiteController extends Controller
{
    // data pasien hari ini admin
    public function data_pasien_today(Request $request){
        if ($request->ajax()) {
            $data = PasienOnsite::query();
            return DataTables::eloquent($data)
                    ->addColumn('nama_pkm', function($row){
                        // return $row->user->name;
                        // Ambil data dari database utama (mysql) dengan DB::connection
                            $user = DB::connection('mysql')->table('users')
                                ->where('username', $row->kode_puskesmas)
                                ->first();

                        // Jika ditemukan, kembalikan nama, jika tidak kembalikan '-'
                        return $user ? $user->name : '-';
                    })
                    ->editColumn('created_at', function ($data) {
                        return Carbon::parse($data->created_at)->format('d-M-Y');
                    })
                    ->addIndexColumn()
                    ->toJson();
        }
    }

    // data pasien hari ini by client
    public function data_pasien_today_client(Request $request){
        if ($request->ajax()) {
            $data = PasienOnsite::query()
                    ->where('kode_puskesmas', Auth::user()->username);
            return DataTables::eloquent($data)
                    ->addColumn('nama_pkm', function($row){
                        // return $row->user->name;
                        // Ambil data user berdasarkan kode_puskesmas yang merujuk ke username
                        $user = DB::connection('mysql')->table('users')
                            ->where('username', $row->kode_puskesmas)
                            ->first();

                        // Jika data user ditemukan, kembalikan nama, jika tidak kembalikan '-'
                        return $user ? $user->name : '-';

                    })
                    ->editColumn('created_at', function ($data) {
                        return Carbon::parse($data->created_at)->format('d-M-Y');
                    })
                    ->addIndexColumn()
                    ->toJson();
        }
    }

    // function untuk generate data dari json pasien onsite
    public function generate_user(){
        // Lokasi file JSON (misalnya di storage/app/public)
        $file_user = storage_path('app/public/user.json');

        if (!file_exists($file_user)) {
            return response()->json(['message' => 'File JSON tidak ditemukan.'], 404);
        }
        // Membaca konten file JSON
        $user_data = file_get_contents($file_user);

        $users = json_decode($user_data, true);

        if (!$users) {
            return response()->json(['message' => 'Data JSON tidak valid.'], 400);
        }
        try {
            DB::beginTransaction();
            foreach ($users as $key) {
                User::create([
                    'ref_group_id' => $key['ref_group_id'],
                    'name' => $key['name'],
                    'username' => $key['username'],
                    'password' => "$2y$12$7Zpf82aQ2Pq3G/CexNf8g.cU9O4GY0oQn36BiVON774quwCmZR0qm",
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return response()->json(['message' => 'Data gagal diproses.']);
        }
        return response()->json(['message' => 'Data pasien berhasil diproses.']);
    }

}

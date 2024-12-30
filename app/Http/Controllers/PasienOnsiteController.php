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
                        return $row->user->name;
                    })
                    ->editColumn('created_at', function ($data) {
                        return Carbon::parse($data->created_at)->format('d-M-Y');
                    })
                    ->addIndexColumn()
                    ->toJson();
        }
    }
    // cara berdasarkan poli
    public function selected_poli_pasien(Request $request){
        // dd($request->all());

        if ($request->ajax()) {
            // Query untuk mengambil data berdasarkan tanggal
            $data = PasienOnsite::query()
                ->where('kodepoli', $request->poli);

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

    // data pasien hari ini by client
    public function data_pasien_today_client(Request $request){
        if ($request->ajax()) {
            $data = PasienOnsite::query()
                    ->where('kode_puskesmas', Auth::user()->username);

            return DataTables::eloquent($data)
                    ->addColumn('nama_pkm', function($row){
                        return $row->user->name;
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
        $file_pasien = storage_path('app/public/pasien_onsite.json');

        // Cek apakah file ada
        if (!file_exists($file_user)) {
            return response()->json(['message' => 'File JSON tidak ditemukan.'], 404);
        }

        // Membaca konten file JSON
        $user_data = file_get_contents($file_user);
        $pasien_data = file_get_contents($file_pasien);

        // Decode JSON ke array
        $users = json_decode($user_data, true);
        $pasiens = json_decode($pasien_data, true);

        // Cek apakah data JSON valid
        if (!$users) {
            return response()->json(['message' => 'Data JSON tidak valid.'], 400);
        }

        // $json = json_decode($pasiens[0]['response'], true);
        // $mass = $json['metadata']['message'];
        // dd($mass);

        // Proses data pasien, misalnya simpan ke database atau tampilkan
        try {
            DB::beginTransaction();

            User::create([
                'ref_group_id' => '1',
                'username' => 'admin',
                'password' => "$2y$12$7Zpf82aQ2Pq3G/CexNf8g.cU9O4GY0oQn36BiVON774quwCmZR0qm", //pass
                'name' => 'admin',
                'email' => 'admin@gmail.com',
            ]);

            foreach ($users as $key) {
                User::create([
                    'ref_group_id' => $key['ref_group_id'],
                    'name' => $key['name'],
                    'username' => $key['username'],
                    'password' => "$2y$12$7Zpf82aQ2Pq3G/CexNf8g.cU9O4GY0oQn36BiVON774quwCmZR0qm", // pass
                ]);
            }


            foreach ($pasiens as $key) {
                // Decode response JSON dari $key
                $json = json_decode($key['response'], true); // Mengonversi string JSON menjadi array

                // Pastikan metadata dan message ada sebelum mengaksesnya
                $message = $json['metadata']['message'] ?? 'Pesan tidak tersedia'; // Menggunakan null coalescing operator

                // Buat entri baru di database
                PasienOnsite::create([
                    // 'id' => $key['id'],
                    'kode_puskesmas' => $key['kode_puskesmas'],
                    'nomorkartu' => $key['nomorkartu'],
                    'kodepoli' => $key['kodepoli'],
                    'namapoli' => $key['namapoli'],
                    'nomorantrean' => $key['nomorantrean'],
                    'response' => $message, // Simpan pesan yang diambil dari JSON
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

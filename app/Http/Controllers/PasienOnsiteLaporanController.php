<?php

namespace App\Http\Controllers;

use App\Models\PasienOnsiteLaporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PasienOnsiteLaporanController extends Controller
{
    // cari data poli
    public function getPoli(Request $request){
        $query = $request->get('q');
        $namapoli = PasienOnsiteLaporan::where('namapoli', 'LIKE', "%{$query}%")
                     ->select('namapoli') // hanya pilih kolom namapoli
                     ->distinct()         // pastikan hanya nama poli yang unik
                     ->paginate(10);

        return response()->json([
            'data' => $namapoli->map(function($poli) {
                return [
                    'id' => $poli->namapoli,  // id dapat menggunakan nama poli sebagai id
                    'text' => $poli->namapoli, // text untuk ditampilkan di dropdown
                ];
            })
        ]);
    }
    // cara berdasarkan poli
    public function selected_poli(Request $request){

        if ($request->ajax()) {

            $startDate = Carbon::parse($request->start)->format('Y-m-d');
            $endDate = Carbon::parse($request->end)->format('Y-m-d');

            // Query untuk mengambil data berdasarkan tanggal
            $data = PasienOnsiteLaporan::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('namapoli', $request->poli);

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
    // data pasien old
    public function data_pasien_old(Request $request) {
        // Memastikan request adalah AJAX
        if ($request->ajax()) {

            $startDate = Carbon::parse($request->start)->format('Y-m-d');
            $endDate = Carbon::parse($request->end)->format('Y-m-d');

            // Query untuk mengambil data berdasarkan tanggal
            $data = PasienOnsiteLaporan::query()
                    ->whereBetween('created_at', [$startDate, $endDate])    ;

            // dd($data);
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


    // function untuk generate data dari json pasien onsite
    public function generate_onsite_laporan(){
        // Lokasi file JSON (misalnya di storage/app/public)
        $file_user = storage_path('app/public/pasien_onsite_laporan.json');
        // $file_pasien = storage_path('app/public/pasien_onsite.json');

        // Cek apakah file ada
        if (!file_exists($file_user)) {
            return response()->json(['message' => 'File JSON tidak ditemukan.'], 404);
        }

        // Membaca konten file JSON
        $user_data = file_get_contents($file_user);
        // $pasien_data = file_get_contents($file_pasien);

        // Decode JSON ke array
        $users = json_decode($user_data, true);
        // $pasiens = json_decode($pasien_data, true);

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

            foreach ($users as $key) {
                // Decode response JSON dari $key
                $json = json_decode($key['response'], true); // Mengonversi string JSON menjadi array

                // Pastikan metadata dan message ada sebelum mengaksesnya
                $message = $json['metadata']['message'] ?? 'Pesan tidak tersedia'; // Menggunakan null coalescing operator

                // Buat entri baru di database
                PasienOnsiteLaporan::UpdateOrCreate([
                    'id' => $key['id'],
                    'kode_puskesmas' => $key['kode_puskesmas'],
                    'nomorkartu' => $key['nomorkartu'],
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

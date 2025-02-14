<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiPasienController extends Controller
{
    public function getDataPasienOnsite(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'kode_puskesmas' => 'required',
                'tanggalperiksa' => 'required|date',
            ],[
                'kode_puskesmas.required' => 'Kode Puskesmas wajib diisi!',
                'tanggalperiksa.required' => 'Tanggal Periksa wajib diisi!',
                'tanggalperiksa.date' => 'Tanggal Periksa harus berupa format tanggal yang valid!',
            ]);

            // Ambil data dari database
            $data = DB::connection('mysql2')
                        ->table('pasien_onsite as po')
                        ->select('*')
                        ->where('po.kode_puskesmas', $request->kode_puskesmas)
                        ->where('po.tanggalperiksa', $request->tanggalperiksa)
                        ->get();

            // Cek apakah data ditemukan
            if ($data->isEmpty()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan!',
                ], 404);
            }

            // Mengembalikan data jika ditemukan
            return response()->json([
                'message' => 'Data pasien onsite berhasil diambil!',
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            // Menangkap error jika ada masalah pada query atau database
            return response()->json([
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}

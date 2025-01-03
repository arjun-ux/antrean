<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // index
    public function index(){
        return view('user.index');
    }
    // data user
    public function data_user(Request $request){
        if ($request->ajax()) {
            $data = User::query()->latest();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->toJson();
        }
        // Jika bukan AJAX, bisa mengembalikan response error atau redirect
        return response()->json(['error' => 'Invalid request'], 400);
    }
    // datauser other database
    public function cek_user_baru() {
        // variabel users dari table bawaan laravel
        $users = User::query();
        // variabel users dari database lain
        $ref_user = DB::connection('mysql2')->table('ref_users')->get();

        // perbandingan jumlah users dan ref_users
        $jumlah_users = $users->count();
        $jumlah_ref_user = $ref_user->count();

        // jika jumlah_user lebih kecil
        if ($jumlah_users < $jumlah_ref_user) {
            // cari username dalam ref_user yang tidak ada di dalam table users
            $username_ref_user = $ref_user->pluck('username')->diff($users->pluck('username'))->toArray();
            // ambil data dari ref_user yang tidak ada di dalam table users
            // dd($username_ref_user);
            $data_ref_user = DB::connection('mysql2')->table('ref_users')->whereIn('username', $username_ref_user)->get();
            return DataTables::of($data_ref_user)
                    ->addIndexColumn()
                    ->toJson();
        }
        // jika jumlah_user itu lebih besar
        else if ($jumlah_users > $jumlah_ref_user) {
            // cari username dalam users yang tidak ada di dalam table ref_user
            $username_users = $users->pluck('username')->diff($ref_user->pluck('username'))->toArray();
            // ambil data dari users yang tidak ada di dalam table ref_user
            $data_users = User::whereIn('username', $username_users)->get();
            // dd($data_users);
            return DataTables::of($data_users)
                    ->addIndexColumn()
                    ->toJson();
        }else {
            // tidak ada data dan di return ke datatable
            return DataTables::of([])
                ->addIndexColumn()
                ->toJson();
        }
    }

    // function sinkron
    public function sinkron(Request $request){
        $user = User::query()->where('username', $request->username)->first();
        // jika data yang dikirim itu ada pada table user
        if ($user == null) {
            $ref_user = DB::connection('mysql2')->table('ref_users')->where('username', $request->username)->first();
            // buat users
            DB::beginTransaction();
            try {
                User::create([
                    "ref_group_id" => $ref_user->ref_group_id,
                    "username" => $ref_user->username,
                    "name" => $ref_user->name,
                    "password" => Hash::make($ref_user->password),
                ]);
                DB::commit();
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                return response()->json(['message' => 'Berhasil Sinkron Data'], 200);
            }
        }else {
            $user->delete();
        }

    }

}

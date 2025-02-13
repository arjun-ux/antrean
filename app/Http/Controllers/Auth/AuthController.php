<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // unauthorized
    public function unauthorized(){
        return view('auth.unauthorized');
    }
    // login
    public function login_page(){
        return view('auth.login');
    }
    // dologin
    public function dologin(Request $request){
        $credential = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ],[
            'username.required' => 'Username wajib di isi!',
            'password.required' => 'Password wajib di isi!',
        ]);
        // proses login
        if (Auth::attempt($credential)) {
            if (Auth::user()->ref_group_id == "1") {
                $request->session()->regenerate();
                return redirect()->route('admin.index')->with('success_login', 'Login Sukses Sebagai');
            }else {
                $request->session()->regenerate();
                return redirect()->route('pasien.index')->with('success_login', 'Login Sukses Sebagai');
            }
        }else {
            return redirect()->back()->with('gagal_login', 'Username atau Password Salah!');
        }
    }
    // logout
    public function logout(Request $request){
        //hapus session ketika logout
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
}

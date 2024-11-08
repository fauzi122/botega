<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(){
        if(session('admin') != null){
            return redirect()->to(url('/admin/dashboard'));
        }
        return view("admin.login");
    }

    public function auth(){
        return redirect()->to(url("/admin/dashboard"));
    }

    public function logout(){
        session()->flush();
        return redirect()->to(url(url("admin/auth")));
    }
}

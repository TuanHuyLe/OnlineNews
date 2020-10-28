<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        return view('admin.home');
    }

    public function loginAdmin()
    {
        if (auth()->check()) {
            return redirect()->route('admin.home');
        }
        return view('login');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function authenticate(Request $request)
    {
        $remember = $request->has('remember_me');
        if (auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember)) {
            return redirect()->route('admin.home');
        }
        return view('login');
    }
}

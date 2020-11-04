<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        return view('admin.web.index');
    }

    public function loginAdmin()
    {
        if (auth()->check()) {
            return redirect()->route('admin.web');
        }
        return view('login');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('web');
    }

    public function authenticate(Request $request)
    {
        $remember = $request->has('remember_me');
        if (auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember)) {
            return redirect()->route('admin.web');
        }
        return view('login');
    }
}

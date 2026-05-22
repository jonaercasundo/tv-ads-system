<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        // DEFAULT CREDENTIALS
        if ($username === 'ITAdmin' && $password === '@metro-mobilia') {
            session(['admin_logged_in' => true]);
            return redirect('/admin');
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect('/login');
    }
}

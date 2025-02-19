<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        $data = $request->only('email', 'password');

        if(Auth::attempt($data)){
            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken('auth')->plainTextToken;

            return response()->json(['status' => true, 'data' => $user,'token' => $token, 'message' => 'Berhasil Login']);
        } 
        return response()->json(['status' => false, 'data' => null, 'message' => 'Gagal Login']);
    }

    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['status' => true, 'data' => $user, 'message' => 'Berhasil Regist']);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['status' => true, 'message' => 'Log Out']);
    }
}

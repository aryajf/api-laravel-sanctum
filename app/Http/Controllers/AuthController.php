<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format Email tidak sesuai',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Proses validasi gagal',
                'data' => $validation->errors(),
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil membuat akun',
            'data' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format Email tidak sesuai',
            'password.required' => 'Password wajib diisi',
        ]);

        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Proses login gagal',
                'data' => $validation->errors(),
            ], 400);
        }

        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil login',
            'token' => $user->createToken('api-product')->plainTextToken
        ]);
    }
}

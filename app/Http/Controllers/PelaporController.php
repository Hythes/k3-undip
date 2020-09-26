<?php

namespace App\Http\Controllers;

use App\Pelapor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;


class PelaporController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nik' => 'required|unique:pelapor|integer',
            'nama' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:8'
        ]);
        if ($validate->fails()) {
            return response()->json(['msg' => $validate->errors()->toJson()], 400);
        }
        $pelapor = Pelapor::create([
            'nik' => $request->input('nik'),
            'nama' => $request->input('nama'),
            'alamat' => $request->input('alamat'),
            'password' => Hash::make($request->input('password')),
        ]);

        $cred = $request->only('nik', 'password');

        try {
            if (!$token = JWTAuth::attempt($cred)) {
                return response()->json(['error' => 'invalid_credentials', "status" => $e->getStatusCode()], 400);
            }
            $status = 201;
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', "status" => $e->getStatusCode()], 500);
        }

        return response()->json(compact('pelapor', 'token', 'status'), 201);
    }

    public function login(Request $request)
    {
        $cred = $request->only('nik', 'password');
        try {
            if (!$token = JWTAuth::attempt($cred)) {
                return response()->json(['error' => 'invalid_credentials', "status" => $e->getStatusCode()], 400);
            }
            $status = 200;
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', "status" => $e->getStatusCode()], 500);
        }
        return response()->json(compact('token', 'status'), 200);
    }
}

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

    public function getData()
    {
        try {
            return response()->json([
                'status' => 200,
                'data' => Pelapor::all()
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => '500'], 500);
        }
    }

    public function getDataSatu($id)
    {
        try {
            return response()->json([
                'status' => 200,
                'data' => Pelapor::find($id)
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => '500'], 500);
        }
    }

    public function editData(Request $request, $id)
    {
        try {

            $Pelapor  = Pelapor::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'nik' => 'required|unique:pelapor|integer',
                'nama' => 'required',
                'alamat' => 'required',
                'password' => 'required|min:8'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $Pelapor->update([
                'nik' => $request->input('nik'),
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json([
                'status' => 200,
                'data' => $Pelapor
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => '500'], 500);
        }
    }
    public function delete($id)
    {
        try {
            $Pelapor = Pelapor::findOrFail($id);
            $Pelapor->delete();

            return response()->json(['message' => 'data berhasil dihapus!', "status" => 200], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "status" => 500], 500);
        }
    }
}

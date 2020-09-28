<?php

namespace App\Http\Controllers;

use App\Admin;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class AdminController extends Controller
{


    /**
     * Registering Admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $admin = Admin::create([
            'nama' => $request->input('nama'),
            'username' => $request->input('username'),
            'password' =>  Hash::make($request->input('password'))
        ]);

        $cred = $request->only("username", "password");
        $status = 201;
        $token = JWTAuth::attempt($cred);
        return response()->json(compact('admin', 'token', 'status'), 201);
    }

    /**
     * Login Admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $cred  = $request->only('username', 'password');
        try {
            if (!$token = JWTAuth::attempt($cred)) {
                return response()->json(['message' => 'invalid_credentials', "status" => 400], 400);
            }
            $status = 200;
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token', "status" => 500], 500);
        }
        return response()->json(compact('token', 'status'));
    }

    public function getData()
    {
        try {
            return response()->json([
                'status' => 200,
                'data' => Admin::all()
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
                'data' => Admin::find($id)
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => '500'], 500);
        }
    }

    public function editData(Request $request, $id)
    {
        try {

            $admin  = Admin::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:admin',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            $admin->update([
                'nama' => $request->input('nama'),
                'username' => $request->input('username'),
                'password' =>  Hash::make($request->input('password'))
            ]);

            return response()->json([
                'status' => 200,
                'data' => $admin
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => '500'], 500);
        }
    }
    public function delete($id)
    {
        try {
            $Admin = Admin::findOrFail($id);
            $Admin->delete();

            return response()->json(['message' => 'data berhasil dihapus!', "status" => 200], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "status" => 500], 500);
        }
    }
}

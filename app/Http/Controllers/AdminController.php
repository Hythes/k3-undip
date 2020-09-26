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

    /**
     * Getting Token for admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAdmin(Request $request)
    {
        try {
            if (!$admin = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
            $status = 200;
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired', "status" => $e->getStatusCode()], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid', "status" => $e->getStatusCode()], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent', "status" => $e->getStatusCode()], $e->getStatusCode());
        }

        return response()->json(compact('user', 'status'));
    }

    /**
     * Editing data Admin, not used for now
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Deleting data Admin, not used for now
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\K3;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Image;
use Carbon\Carbon;
use Exception;

class K3Controller extends Controller
{
    public function index()
    {
        return response()->json(['res' => 'ooookkkkk']);
    }
    public function authData()
    {
        $res = [
            'your name' => Auth::user()
        ];
        return response()->json($res);
    }
    public function PelaporInputData(Request $request)
    {
        try {

            $validate = Validator::make($request->all(), [
                'lat' => 'required',
                'long' => 'required',
                'keterangan' => 'required',
                'foto' => 'required|mimes:png,jpeg,jpg|max:5096',
            ]);
            if ($validate->fails()) {
                return response()->json(['msg' => $validate->errors()->toJson()], 400);
            }

            $file = $request->file('foto');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->save(storage_path($fileName));

            $k3 = K3::create([
                'lat' => $request->input('lat'),
                'long' => $request->input('long'),
                'keterangan' => $request->input('keterangan'),
                'foto' => $fileName,
                'id_pelapor' => Auth::id(),
            ]);

            return response()->json(["msg" => 'Laporan berhasil disimpan!'], 200);
        } catch (Exception $e) {
            return response()->json(["msg" => $e->getMessage()], 500);
        }
    }
    public function PelaporCekStatus()
    {
        $data = K3::where('id_pelapor', Auth::id())->get();
        $res = [
            'msg' => 'data berhasil diambil!',
            'data' => $data,
        ];
        return response()->json($res, 200);
    }
}

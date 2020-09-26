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
use Illuminate\Support\Facades\File;


class K3Controller extends Controller
{
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
                return response()->json(['message' => $validate->errors()->toJson()], 400);
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

            return response()->json(["message" => 'Laporan berhasil disimpan!', "status" => 200], 200);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage(), "status" => 500], 500);
        }
    }
    public function PelaporCekStatus()
    {
        $data = K3::where('id_pelapor', Auth::id())->get();
        $res = [
            'message' => 'data berhasil diambil!',
            'data' => $data,
            "status" => 200,
        ];
        return response()->json($res, 200);
    }
    public function getData()
    {
        $data = K3::get();
        $res = [
            'message' => 'data berhasil diambil!',
            'data' => $data,
            "status" => 200,

        ];
        return response()->json($res, 200);
    }
    public function getDataSatu($id)
    {
        try {
            $data = K3::findOrFail($id);
            $res = [
                'message' => 'data berhasil diambil!',
                'data' => $data,
                "status" => 200,

            ];
            return response()->json($res, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "status" => 500], 400);
        }
    }
    public function editData(Request $request, $id)
    {
        try {
            $k3 = K3::findOrFail($id);
            $validate = Validator::make($request->all(), [
                'lat' => 'required',
                'long' => 'required',
                'keterangan' => 'required',
                'foto' => 'required|mimes:png,jpeg,jpg|max:5096',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()->toJson(), "status" => 400], 400);
            }
            File::delete(storage_path($k3->foto));
            $file = $request->file('foto');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->save(storage_path($fileName));

            $k3->update([
                'lat' => $request->input('lat'),
                'long' => $request->input('long'),
                'keterangan' => $request->input('keterangan'),
                'foto' => $fileName,
                'id_pelapor' => Auth::id(),
            ]);

            return response()->json(["message" => 'Laporan berhasil disimpan!', "status" => 200], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "status" => 500], 500);
        }
    }
    public function delete($id)
    {
        try {
            $k3 = K3::findOrFail($id);
            File::delete(storage_path($k3->foto));
            $k3->delete();

            return response()->json(['message' => 'data berhasil dihapus!', "status" => 200], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "status" => 500], 500);
        }
    }
}

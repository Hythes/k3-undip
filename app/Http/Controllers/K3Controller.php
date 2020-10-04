<?php

namespace App\Http\Controllers;

use App\Events\K3Baru;
use App\K3;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Image;
use Session;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class K3Controller extends Controller
{
    public function show()
    {
        $k3 = DB::table('k3')
            ->select(DB::raw('k3.*,pelapor.nama'))
            ->join('pelapor', 'k3.id_pelapor', '=', 'pelapor.id')
            ->where('k3.sudah_diterima', '0')
            ->orderBy('k3.id', 'DESC')
            ->paginate(10);
        $k3Approve = DB::table('k3')
            ->select(DB::raw('k3.*,pelapor.nama'))
            ->join('pelapor', 'k3.id_pelapor', '=', 'pelapor.id')
            ->where('k3.sudah_diterima', '1')
            ->orderBy('k3.id', 'DESC')
            ->paginate(10);
        $k3Tolak = DB::table('k3')
            ->select(DB::raw('k3.*,pelapor.nama'))
            ->join('pelapor', 'k3.id_pelapor', '=', 'pelapor.id')
            ->where('k3.sudah_diterima', '2')
            ->orderBy('k3.id', 'DESC')
            ->paginate(10);

        $i = 1;
        $data = [
            'k3' => $k3,
            'k3Approve' => $k3Approve,
            'k3Tolak' => $k3Tolak,
            'i' => $i
        ];
        return view('k3.index')->with($data);
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
                return response()->json(['message' => $validate->errors()->toJson(), "status" => 400], 400);
            }

            $file = $request->file('foto');
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = Storage::putFileAs(
                'public',
                $request->file('foto'),
                $fileName
            );
            $k3 = K3::create([
                'lat' => $request->input('lat'),
                'long' => $request->input('long'),
                'keterangan' => $request->input('keterangan'),
                'foto' => $fileName,
                'id_pelapor' => Auth::id(),
            ]);
            event(new K3Baru($k3, 'baru'));
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
    public function terima($id)
    {
        $k3 = K3::findOrFail($id);
        event(new K3Baru($k3, 'terima'));

        $k3->update([
            'id_admin' => Session::get('data')->id,
            'sudah_diterima' => 1
        ]);
        return response()->json(["message" => 'Berhasil menyimpan data', "status" => 200], 200);
    }
    public function tolak($id)
    {
        $k3 = K3::findOrFail($id);
        event(new K3Baru($k3, 'tolak'));

        $k3->update([
            'id_admin' => Session::get('data')->id,
            'sudah_diterima' => 2
        ]);
        return response()->json(["message" => 'Berhasil menyimpan data', "status" => 200], 200);
    }
    public function getDataSatu($id)
    {

        try {
            $data = DB::table('k3')
                ->select(DB::raw('k3.*,pelapor.nama'))
                ->join('pelapor', 'k3.id_pelapor', '=', 'pelapor.id')
                ->where('k3.id', $id)
                ->first();
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

            $path = Storage::putFileAs(
                'public',
                $request->file('foto'),
                $fileName
            );

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

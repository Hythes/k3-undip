<?php

namespace App\Http\Controllers;

use App\Admin;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Events\AdminDibuat;
use App\K3;
use App\Pelapor;

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
        $validator = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin',
            'password' => 'required|string|min:6'
        ]);

        $admin = Admin::create([
            'nama' => $request->input('nama'),
            'username' => $request->input('username'),
            'password' =>  Hash::make($request->input('password'))
        ]);

        $data = [
            'notLoggedIn' => true,
            'showAlert' => 'Anda Berhasil Register! Silahkan login'
        ];


        return view('login')->with($data);
    }
    public function store(Request $request)
    {
        $validator = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin',
            'password' => 'required|string|min:6'
        ]);

        $admin = Admin::create([
            'nama' => $request->input('nama'),
            'username' => $request->input('username'),
            'password' =>  Hash::make($request->input('password'))
        ]);
        event(new AdminDibuat($admin));
        return Redirect::to('admin/dataAdmin');
    }
    /**
     * Login Admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validate_admin = Admin::where('username', $request->input('username'))
            ->first();
        if ($validate_admin && Hash::check($request->input('password'), $validate_admin->password)) {
            session(['loggedin' => true, 'data' => $validate_admin]);
            return redirect('/');
        } else {
            $data = [
                'notLoggedIn' => true,
                'showAlert' => 'Gagal login! Username atau Password anda salah'
            ];
            return view('login')->with($data);
        }
    }

    public function index(Request $request)
    {
        if ($request->session()->exists('loggedin')) {
            return redirect('admin/dashboard');
        } else {
            return redirect('login');
        }
    }
    public function show()
    {
        $admin = Admin::paginate(10);
        $data = [
            'i' => 1,
            'dataAdmin' => $admin
        ];
        return view('dataAdmin.index')->with($data);
    }
    public function getCountData()
    {
        try {
            $jumlahData = [
                'k3Baru' => K3::where('sudah_diterima', 0)->count(),
                'k3Diterima' => K3::where('sudah_diterima', 1)->count(),
                'k3Ditolak' => K3::where('sudah_diterima', 2)->count(),
                'pelapor' => Pelapor::all()->count()
            ];
            return response()->json([
                'status' => 200,
                'message' => 'berhasil mengambil data!',
                'data' => $jumlahData
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => '500'], 500);
        }
    }
    public function dashboard()
    {
        $jumlahData = [
            'k3Baru' => K3::where('sudah_diterima', 0)->count(),
            'k3Diterima' => K3::where('sudah_diterima', 1)->count(),
            'k3Ditolak' => K3::where('sudah_diterima', 2)->count(),
            'pelapor' => Pelapor::all()->count()
        ];
        $data = [
            'pelapor' => Pelapor::all()->take(3),
            'iA' => 1,
            'iP' => 1,
            'dataAdmin' => Admin::all()->take(3),
            'jumlahData' => $jumlahData
        ];
        return view('index')->with($data);
    }
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
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

    public function editData(Request $request)
    {
        try {

            $admin  = Admin::findOrFail($request->input('id'));
            $validator = $request->validate([
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:6'
            ]);

            $admin->update([
                'nama' => $request->input('nama'),
                'username' => $request->input('username'),
                'password' =>  Hash::make($request->input('password'))
            ]);

            return Redirect::to('/admin/dataAdmin');
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => '500'], 500);
        }
    }
    public function delete($id)
    {
        try {
            $Admin = Admin::findOrFail($id);
            $Admin->delete();
            return Redirect::to('/admin/dataAdmin')->with(['showAlert' => 'Data berhasil dihapus!']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "status" => 500], 500);
        }
    }

    public function loginGet()
    {
        $data = ['notLoggedIn' => true];
        return view('login')->with($data);
    }

    public function registerGet()
    {
        $data = ['notLoggedIn' => true];
        return view('register')->with($data);
    }
}

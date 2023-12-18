<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MasterController extends Controller
{
    function jenisKelamin($idJenisKelamin) {
        switch ($idJenisKelamin) {
            case "0":
                return "Tidak diketahui";
            case "1":
                return "Laki-laki";
            case "2":
                return "Perempuan";
            case "3":
                return "Tidak dapat ditentukan";
            case "4":
                return "Tidak mengisi";
            default:
                return "Tidak diketahui";
        }
    }

    function toObject($array) {
        return json_decode(json_encode($array), false);
    }

    function current() {
        return $this->toObject([
            "nama" => Session::get("nama")[0],
            "username" => Session::get("username")[0]
        ]);
    }

    public function login() {
        return view("login");
    }

    public function authenticate(Request $request){
        $credentials = $request->only("username", "password");
        $users = DB::table("users")
                    ->where("username", $credentials["username"])
                    ->where("password", $credentials["password"])
                    ->get();
        if (count($users) === 0) {
            Session::flush();
            return back();
        } else {
            Session::push("isLogin", true);
            Session::push("nama", $users[0]->nama);
            Session::push("username", $users[0]->username);
            return redirect()->route("dashboard");
        }
    }

    public function logout() {
        Session::flush();
        return redirect()->route("login");
    }

    public function dashboard() {
        $current = $this->current();
        return view("dashboard", compact("current"));
    }

    public function rekamMedis() {
        $current = $this->current();
        $rekamMedisData = DB::table("rekam_medis")->get();
        $rekamMedisData->transform(function ($item) {
            $item->jenis_kelamin = $this->jenisKelamin($item->jenis_kelamin);
            return $item;
        });
        return view("rekamMedis", compact("current", "rekamMedisData"));
    }

    public function createRekamMedis(Request $request) {
        DB::table("rekam_medis")
            ->insert([
                "nama_pasien" => $request->nama_pasien,
                "nik" => $request->nik,
                "tempat_lahir" => $request->tempat_lahir,
                "tanggal_lahir" => $request->tanggal_lahir,
                "jenis_kelamin" => $request->jenis_kelamin,
                "alamat" => $request->alamat,
                "nomor_kontak" => $request->nomor_kontak
            ]);
        return back();
    }

    public function updateRekamMedis(Request $request) {
        DB::table("rekam_medis")
            ->where("id", $request->id)
            ->update([
                "nama_pasien" => $request->nama_pasien,
                "nik" => $request->nik,
                "tempat_lahir" => $request->tempat_lahir,
                "tanggal_lahir" => $request->tanggal_lahir,
                "jenis_kelamin" => $request->jenis_kelamin,
                "alamat" => $request->alamat,
                "nomor_kontak" => $request->nomor_kontak
            ]);
        return back();
    }

    public function deleteRekamMedis(Request $request) {
        DB::table("rekam_medis")
            ->where("id", $request->id)
            ->delete();
        return back();
    }
}

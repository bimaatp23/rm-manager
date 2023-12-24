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
            "username" => Session::get("username")[0],
            "role" => Session::get("role")[0]
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
        if (count($users) == 0) {
            Session::flush();
            return back();
        } else {
            Session::push("isLogin", true);
            Session::push("nama", $users[0]->nama);
            Session::push("username", $users[0]->username);
            Session::push("role", $users[0]->role);
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
        $peminjaman = DB::table("peminjaman")->get();
        $rekamMedisData = DB::table("rekam_medis")->get();
        $rekamMedisData->transform(function ($item) use ($peminjaman) {
            $item->jenis_kelamin = $this->jenisKelamin($item->jenis_kelamin);
            if (in_array($item->id, $peminjaman->pluck("id_rekam_medis")->toArray())) {
                $item->status_pemakaian = 1;
            } else {
                $item->status_pemakaian = 0;
            }
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
                "nomor_kontak" => $request->nomor_kontak,
                "author" => $this->current()->username
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

    public function checkup($idRekamMedis) {
        $current = $this->current();
        $checkupData = DB::table("checkup")
                        ->join("dokter", "checkup.id_dokter", "=", "dokter.id")
                        ->select("checkup.*", "dokter.nama as nama_dokter")
                        ->where("id_rekam_medis", $idRekamMedis)
                        ->get();
        $rekamMedisData = DB::table("rekam_medis")
                            ->where("id", $idRekamMedis)
                            ->first();
        $dokterData = DB::table("dokter")->get();
        return view("checkup", compact("current", "checkupData", "rekamMedisData", "dokterData", "idRekamMedis"));
    }

    public function createCheckup(Request $request, $idRekamMedis) {
        DB::table("checkup")
            ->insert([
                "id_dokter" => $request->id_dokter,
                "id_rekam_medis" => $idRekamMedis,
                "diagnosis" => $request->diagnosis,
                "resep" => $request->resep,
                "tanggal" => $request->tanggal
            ]);
        return back();
    }

    public function updateCheckup(Request $request, $idRekamMedis) {
        DB::table("checkup")
            ->where("id", $request->id)
            ->update([
                "id_dokter" => $request->id_dokter,
                "id_rekam_medis" => $idRekamMedis,
                "diagnosis" => $request->diagnosis,
                "resep" => $request->resep,
                "tanggal" => $request->tanggal
            ]);
        return back();
    }

    public function deleteCheckup(Request $request, $idRekamMedis) {
        DB::table("checkup")
            ->where("id", $request->id)
            ->delete();
        return back();
    }

    public function peminjaman() {
        $current = $this->current();
        $peminjaman = DB::table("peminjaman")
                            ->orderBy("tanggal_peminjaman", "DESC")
                            ->get();
        $peminjamanData = $peminjaman->whereNull("tanggal_pengembalian")->concat($peminjaman->whereNotNull("tanggal_pengembalian"));
        $rekamMedisData = DB::table("rekam_medis")
                            ->whereNotIn("id", $peminjaman->whereNull("tanggal_pengembalian")->pluck("id_rekam_medis")->toArray())
                            ->get();
        return view("peminjaman", compact("current", "peminjamanData", "rekamMedisData"));
    }

    public function createPeminjaman(Request $request) {
        $timestampNow = date("Y-m-d H:i:s");
        $timestampTomorrow = date("Y-m-d H:i:s", strtotime("+1 day"));
        $timestampTomorrowNext = date("Y-m-d H:i:s", strtotime("+2 days"));
        DB::table("peminjaman")
            ->insert([
                "id_rekam_medis" => $request->id_rekam_medis,
                "nama_peminjam" => $request->nama_peminjam,
                "kontak_peminjam" => $request->kontak_peminjam,
                "keperluan" => $request->keperluan,
                "keterangan" => $request->keterangan,
                "tanggal_peminjaman" => $timestampNow,
                "batas_pengembalian" => $request->keperluan == "Rawat Inap" ? $timestampTomorrowNext : $timestampTomorrow,
                "reminder" => 0,
                "author" => $this->current()->username
            ]);
        return back();
    }

    public function updatePeminjaman(Request $request) {
        $timestampNow = date("Y-m-d H:i:s");
        DB::table("peminjaman")
            ->where("id", $request->id)
            ->update([
                "tanggal_pengembalian" => $timestampNow
            ]);
        return back();
    }

    public function deletePeminjaman(Request $request) {
        DB::table("peminjaman")
            ->where("id", $request->id)
            ->delete();
        return back();
    }

    public function dokter() {
        $current = $this->current();
        $dokterData = DB::table("dokter")->get();
        return view("dokter", compact("current", "dokterData"));
    }

    public function createDokter(Request $request) {
        $dokterData = DB::table("dokter")
                        ->where("nip", $request->nip)
                        ->get();
        if (count($dokterData) === 0) {
            DB::table("dokter")
                ->insert([
                    "nama" => $request->nama,
                    "nip" => $request->nip,
                    "tempat_lahir" => $request->tempat_lahir,
                    "tanggal_lahir" => $request->tanggal_lahir,
                    "jenis_kelamin" => $request->jenis_kelamin,
                    "alamat" => $request->alamat
                ]);
        }
        return back();
    }

    public function updateDokter(Request $request) {
        DB::table("dokter")
            ->where("id", $request->id)
            ->update([
                "nama" => $request->nama,
                "nip" => $request->nip,
                "tempat_lahir" => $request->tempat_lahir,
                "tanggal_lahir" => $request->tanggal_lahir,
                "jenis_kelamin" => $request->jenis_kelamin,
                "alamat" => $request->alamat
            ]);
        return back();
    }

    public function deleteDokter(Request $request) {
        DB::table("dokter")
            ->where("id", $request->id)
            ->delete();
        return back();
    }
}

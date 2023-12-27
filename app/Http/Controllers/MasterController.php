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
            "nama" => Session::get("nama"),
            "username" => Session::get("username"),
            "role" => Session::get("role"),
            "notificationIcon" => Session::get("notificationIcon") ?? null,
            "notificationMessage" => Session::get("notificationMessage") ?? null
        ]);
    }

    function setNotification($icon, $message) {
        Session::flash("notificationIcon", $icon);
        Session::flash("notificationMessage", $message);
    }

    public function login() {
        $current = $this->toObject([
            "notificationIcon" => Session::get("notificationIcon") ?? null,
            "notificationMessage" => Session::get("notificationMessage") ?? null
        ]);
        return view("login", compact("current"));
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
            Session::put("isLogin", true);
            Session::put("nama", $users[0]->nama);
            Session::put("username", $users[0]->username);
            Session::put("role", $users[0]->role);
            return redirect()->route("dashboard");
        }
    }

    public function logout() {
        Session::forget("isLogin");
        Session::forget("nama");
        Session::forget("username");
        Session::forget("role");
        Session::flash("notificationIcon", Session::get("notificationIcon"));
        Session::flash("notificationMessage", Session::get("notificationMessage"));
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
        $rekamMedisData = DB::table("rekam_medis")
                            ->where("nik", $request->nik)
                            ->get();
        if (count($rekamMedisData) == 0) {
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
            $this->setNotification("success", "Tambah rekam medis berhasil");
        } else {
            $this->setNotification("info", "NIK sudah digunakan");
        }
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
        $this->setNotification("success", "Edit rekam medis berhasil");
        return back();
    }

    public function deleteRekamMedis(Request $request) {
        DB::table("rekam_medis")
            ->where("id", $request->id)
            ->delete();
        $this->setNotification("success", "Hapus rekam medis berhasil");
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
        $this->setNotification("success", "Tambah checkup berhasil");
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
        $this->setNotification("success", "Edit checkup berhasil");
        return back();
    }

    public function deleteCheckup(Request $request, $idRekamMedis) {
        DB::table("checkup")
            ->where("id", $request->id)
            ->delete();
        $this->setNotification("success", "Hapus checkup berhasil");
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
        // * Harap Dihapus Pada Saat Imple
        $timestampTomorrow = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $timestampTomorrowNext = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        // Sampai Sini Ya Hapusnya
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
        $this->setNotification("success", "Tambah peminjaman berhasil");
        return back();
    }

    public function updatePeminjaman(Request $request) {
        $timestampNow = date("Y-m-d H:i:s");
        DB::table("peminjaman")
            ->where("id", $request->id)
            ->update([
                "tanggal_pengembalian" => $timestampNow
            ]);
        $this->setNotification("success", "Edit peminjaman berhasil");
        return back();
    }

    public function deletePeminjaman(Request $request) {
        DB::table("peminjaman")
            ->where("id", $request->id)
            ->delete();
        $this->setNotification("success", "Hapus peminjaman berhasil");
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
        if (count($dokterData) == 0) {
            DB::table("dokter")
                ->insert([
                    "nama" => $request->nama,
                    "nip" => $request->nip,
                    "tempat_lahir" => $request->tempat_lahir,
                    "tanggal_lahir" => $request->tanggal_lahir,
                    "jenis_kelamin" => $request->jenis_kelamin,
                    "alamat" => $request->alamat
                ]);
            $this->setNotification("success", "Tambah dokter berhasil");
        } else {
            $this->setNotification("info", "NIP sudah digunakan");
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
        $this->setNotification("success", "Edit dokter berhasil");
        return back();
    }

    public function deleteDokter(Request $request) {
        DB::table("dokter")
            ->where("id", $request->id)
            ->delete();
        $this->setNotification("success", "Hapus dokter berhasil");
        return back();
    }

    public function petugas() {
        $current = $this->current();
        $petugasData = DB::table("users")
                        ->where("role", "Petugas RM")
                        ->get();
        return view("petugas", compact("current", "petugasData"));
    }

    public function createPetugas(Request $request) {
        $petugasData = DB::table("users")
                        ->where("username", $request->username)
                        ->get();
        if (count($petugasData) == 0) {
            DB::table("users")
                ->insert([
                    "nama" => $request->nama,
                    "username" => $request->username,
                    "role" => "Petugas RM",
                    "password" => "simpepe"
                ]);
            $this->setNotification("success", "Tambah petugas berhasil");
        } else {
            $this->setNotification("info", "Username sudah digunakan");
        }
        return back();
    }

    public function updatePetugas(Request $request) {
        DB::table("users")
            ->where("id", $request->id)
            ->update([
                "nama" => $request->nama,
                "username" => $request->username,
                "role" => "Petugas RM"
            ]);
        $this->setNotification("success", "Edit petugas berhasil");
        return back();
    }

    public function deletePetugas(Request $request) {
        DB::table("users")
            ->where("id", $request->id)
            ->delete();
        $this->setNotification("success", "Hapus petugas berhasil");
        return back();
    }

    public function changePassword(Request $request) {
        $usersData = DB::table("users")
                        ->where("username", $request->username)
                        ->where("password", $request->password)
                        ->get();
        if (count($usersData) == 0) {
            $this->setNotification("info", "Password lama salah");
            return back();
        } else if ($request->new_password != $request->renew_password) {
            $this->setNotification("info", "Password baru tidak sama");
            return back();
        } else {
            DB::table("users")
                ->where("username", $request->username)
                ->update([
                    "password" => $request->new_password
                ]);
            $this->setNotification("success", "Ganti password berhasil");
            return redirect()->route("logout");
        }
    }
}

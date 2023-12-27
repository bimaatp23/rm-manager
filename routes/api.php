<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/reminder", function (Request $request) {
    $peminjamanData = DB::table("peminjaman")
        ->where("reminder", 0)
        ->where("batas_pengembalian", "<", Carbon::now())
        ->whereNull("tanggal_pengembalian")
        ->get();

    foreach ($peminjamanData as $data) {
        $curl = curl_init();

        $no_rekam_medis = $data->id_rekam_medis + 100000;

        $message = "Halo $data->nama_peminjam, \n"
                     . "Mohon untuk segera mengembalikan dokumen rekam medis RM$no_rekam_medis. \n"
                     . "Dikarenakan sudah melewati batas pengembalian. \n"
                     . "Terima kasih. \n\n"
                     . "---- TTD Petugas Rekam Medis ---";

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
        "target" => "6283862146344",
        "message" => $message,
        "countryCode" => "62",
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: ox26mCzzeLxZ9s6@85tP"
        ),
        ));

        $response = curl_exec($curl);
        $error_msg = "";
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        DB::table("peminjaman")
            ->where("id", $data->id)
            ->update([
                "reminder" => 1
            ]);
    }

    $message = count($peminjamanData) == 0 ? "No reminders need to be sent" : "WhatsApp reminder sent successfully";

    return response()->json(["message" => $message], 200);
})->name("reminder");

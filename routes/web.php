<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/login", [MasterController::class, "login"])->name("login");
Route::post("/login", [MasterController::class, "authenticate"])->name("authenticate");
Route::get("/logout", [MasterController::class, "logout"])->name("logout");

Route::middleware(["auth.redirect"])->group(function () {
    Route::get("/", [MasterController::class, "dashboard"])->name("dashboard");
    Route::get("/rekam-medis", [MasterController::class, "rekamMedis"])->name("rekamMedis");
    Route::post("/rekam-medis", [MasterController::class, "createRekamMedis"])->name("createRekamMedis");
    Route::put("/rekam-medis", [MasterController::class, "updateRekamMedis"])->name("updateRekamMedis");
    Route::delete("/rekam-medis", [MasterController::class, "deleteRekamMedis"])->name("deleteRekamMedis");
    Route::get("/checkup/{idRekamMedis}", [MasterController::class, "checkup"])->name("checkup");
    Route::post("/checkup/{idRekamMedis}", [MasterController::class, "createCheckup"])->name("createCheckup");
    Route::put("/checkup/{idRekamMedis}", [MasterController::class, "updateCheckup"])->name("updateCheckup");
    Route::delete("/checkup/{idRekamMedis}", [MasterController::class, "deleteCheckup"])->name("deleteCheckup");
    Route::get("/peminjaman", [MasterController::class, "peminjaman"])->name("peminjaman");
    Route::post("/peminjaman", [MasterController::class, "createPeminjaman"])->name("createPeminjaman");
    Route::put("/peminjaman", [MasterController::class, "updatePeminjaman"])->name("updatePeminjaman");
    Route::delete("/peminjaman", [MasterController::class, "deletePeminjaman"])->name("deletePeminjaman");
    Route::get("/dokter", [MasterController::class, "dokter"])->name("dokter");
    Route::post("/dokter", [MasterController::class, "createDokter"])->name("createDokter");
    Route::put("/dokter", [MasterController::class, "updateDokter"])->name("updateDokter");
    Route::delete("/dokter", [MasterController::class, "deleteDokter"])->name("deleteDokter");
    Route::get("/petugas", [MasterController::class, "petugas"])->name("petugas");
    Route::post("/petugas", [MasterController::class, "createPetugas"])->name("createPetugas");
    Route::put("/petugas", [MasterController::class, "updatePetugas"])->name("updatePetugas");
    Route::delete("/petugas", [MasterController::class, "deletePetugas"])->name("deletePetugas");
});

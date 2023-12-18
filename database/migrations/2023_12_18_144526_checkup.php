<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Checkup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rekam_medis')->constrained('rekam_medis');
            $table->string('nama_dokter');
            $table->text('diagnosis');
            $table->text('resep');
            $table->date('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkup');
    }
}

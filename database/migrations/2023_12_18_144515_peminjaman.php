<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Peminjaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rekam_medis')->constrained('rekam_medis');
            $table->string('nama_peminjam');
            $table->string('kontak_peminjam');
            $table->enum('keperluan', ['Rawat Inap', 'Rawat Jalan']);
            $table->string('keterangan')->nullable();
            $table->timestamp('tanggal_peminjaman')->nullable();
            $table->timestamp('reminder_pengembalian')->nullable();
            $table->timestamp('tanggal_pengembalian')->nullable();
            $table->integer('reminder');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tkbm_presensi', function (Blueprint $table) {
            // Kolom ID utama yang auto-increment
            $table->id();

            // Kolom untuk ID karyawan
            $table->string('employee_id', 100);

            // Kolom untuk waktu masuk dan keluar
            $table->time('time_in');
            $table->time('time_out')->nullable(); // Boleh kosong jika belum absen pulang

            // Kolom untuk tanggal absensi
            $table->date('present_date');

            // Kolom opsional lainnya
            $table->string('machine_id', 100)->nullable();
            $table->string('shift_id', 100)->nullable();
            $table->string('spk', 100)->nullable();

            // Kolom status sinkronisasi (0 = false, 1 = true)
            $table->boolean('sync')->default(false);

            // Kolom created_at dan updated_at secara otomatis
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi (jika di-rollback).
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tkbm_presensi');
    }
};

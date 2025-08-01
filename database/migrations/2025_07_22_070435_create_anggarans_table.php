<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggarans', function (Blueprint $table) {
        $table->id();
        $table->string('modul'); // Contoh: 'perlengkapan', 'peralatan'
        $table->year('tahun');
        $table->decimal('total_anggaran', 15, 2)->default(0);
        $table->timestamps();
        $table->unique(['modul', 'tahun']); // Pastikan hanya ada satu anggaran per modul per tahun
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggarans');
    }
};

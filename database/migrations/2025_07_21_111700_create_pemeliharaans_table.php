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

    Schema::create('pemeliharaans', function (Blueprint $table) {
        $table->id(); // Nomor Urutan
        $table->string('pekerjaan');
        $table->string('laporan_bulanan')->nullable();
        $table->string('bast')->nullable();
        $table->string('bapf')->nullable();
        $table->string('bap')->nullable();
        $table->string('dok_tagihan')->nullable();
        $table->date('nd_pembayaran')->nullable();
        $table->decimal('dpp', 15, 2);
        $table->string('mitra');
        $table->string('status');
        $table->text('keterangan')->nullable();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemeliharaans');
    }
};

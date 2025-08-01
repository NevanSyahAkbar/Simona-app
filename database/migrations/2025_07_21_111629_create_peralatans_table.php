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
        Schema::create('peralatans', function (Blueprint $table) {
        $table->id(); // Nomor Urutan
        $table->year('tahun');
        $table->string('pekerjaan');
        $table->date('nd_ijin')->nullable();
        $table->date('date_pr')->nullable();
        $table->string('pr_number')->nullable();
        $table->string('po_number')->nullable();
        $table->string('gr_string')->nullable();
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
        Schema::dropIfExists('peralatans');
    }
};

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
    public function up(): void
    {
    Schema::create('perlengkapans', function (Blueprint $table) {
        $table->id(); // Nomor Urut
        $table->string('kode_perlengkapan')->unique()->after('id');
        $table->year('tahun');
        $table->string('sub_bagian');
        $table->string('pekerjaan');
        $table->date('date_nd_user')->nullable();
        $table->date('date_survey')->nullable();
        $table->date('date_nd_ijin')->nullable();
        $table->date('date_pr')->nullable();
        $table->string('pr_number')->nullable();
        $table->string('po_number')->nullable();
        $table->string('gr_number')->nullable();
        $table->string('order_padi');
        $table->date('bast_user')->nullable();
        $table->date('nd_pembayaran')->nullable();
        $table->decimal('dpp', 15, 2);
        $table->string('mitra');
        $table->string('status');
        $table->text('keterangan')->nullable();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->boolean('sync')->default(false);
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
        Schema::dropIfExists('perlengkapans');
    }
};

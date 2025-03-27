<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_pajak_hotel', function (Blueprint $table) {
            $table->id();
            $table->string('bulan');
            $table->string('tahun');
            $table->string('jumlah_setoran');
            $table->string('bukti_laporan')->nullable();
            $table->unsignedBigInteger('pajak_hotel_id');
            $table->foreign('pajak_hotel_id')->references('id')->on('pajak_hotel')->onDelete('cascade');
            $table->string('tgl_surat_pemberitahuan')->nullable();
            $table->string('tgl_surat_teguran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pajak_hotel');
    }
};

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
        Schema::create('laporan_pajak_restoran', function (Blueprint $table) {
            $table->id();
            $table->string('bulan');
            $table->string('tahun');
            $table->string('jumlah_setoran');
            $table->string('bukti_laporan')->nullable();
            $table->unsignedBigInteger('pajak_restoran_id');
            $table->foreign('pajak_restoran_id')->references('id')->on('pajak_restoran')->onDelete('cascade');
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
        Schema::dropIfExists('laporan_pajak_restoran');
    }
};

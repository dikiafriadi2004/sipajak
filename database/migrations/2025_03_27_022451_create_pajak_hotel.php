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
        Schema::create('pajak_hotel', function (Blueprint $table) {
            $table->id();
            $table->string('npwpd')->unique();
            $table->string('nama_pemilik');
            $table->string('slug')->unique();
            $table->string('no_hp');
            $table->string('nama_usaha');
            $table->string('alamat_usaha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pajak_hotel');
    }
};

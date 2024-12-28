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
        Schema::create('pasien_onsites', function (Blueprint $table) {
            $table->id();
            $table->string('kode_puskesmas');
            $table->string('nomorkartu')->nullable();
            $table->string('nik')->nullable();
            $table->string('nohp')->nullable();
            $table->string('kodepoli')->nullable();
            $table->string('namapoli')->nullable();
            $table->string('norm')->nullable();
            $table->string('tanggalperiksa')->nullable();
            $table->string('kodedoketer')->nullable();
            $table->string('namadokter')->nullable();
            $table->string('jampraktek')->nullable();
            $table->string('nomorantrean')->nullable();
            $table->string('angkaantrean')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('response')->nullable();
            $table->string('flag')->nullable();
            $table->timestamps();
            $table->string('waktu_kirim')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien_onsites');
    }
};

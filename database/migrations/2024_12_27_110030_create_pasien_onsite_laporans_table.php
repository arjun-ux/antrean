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
        Schema::create('pasien_onsite_laporans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kode_puskesmas');
            $table->string('nomorkartu')->nullable();
            $table->string('namapoli')->nullable();
            $table->string('nomorantrean')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien_onsite_laporans');
    }
};

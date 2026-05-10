<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan'); // Ini kolom nama
            $table->text('deskripsi');     // Ini kolom deskripsi
            $table->integer('harga');      // Ini kolom harga
            $table->string('satuan');     // Ini kolom satuan (kg/pcs)
            $table->string('image');      // Ini kolom nama gambar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
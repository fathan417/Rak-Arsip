<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pengarang');
            $table->text('abstrak');
            $table->string('lokasi_rak');
            $table->string('lokasi_baris');
            $table->string('kategori')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('file_dokumen_path')->nullable();
            $table->string('slug')->unique();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};

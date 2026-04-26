<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah tabel sudah ada
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('nama_file');
                $table->string('path');
                $table->string('tipe_file')->nullable();
                $table->unsignedBigInteger('ukuran')->nullable();
                $table->unsignedBigInteger('dokumen_id')->nullable();
                $table->unsignedBigInteger('lampiran_id')->nullable();
                $table->timestamps();
                
                // Hanya tambahkan foreign key jika bukan SQLite
                if (config('database.default') !== 'sqlite') {
                    $table->foreign('dokumen_id')->references('dokumen_id')->on('dokumen')->onDelete('cascade');
                    $table->foreign('lampiran_id')->references('lampiran_id')->on('lampiran')->onDelete('cascade');
                }
            });
        }
    }

    public function down()
    {
        // Untuk SQLite, drop tabel langsung
        if (config('database.default') === 'sqlite') {
            Schema::dropIfExists('media');
        } else {
            // Untuk MySQL/PostgreSQL, drop foreign key dulu
            Schema::table('media', function (Blueprint $table) {
                // Hapus foreign key constraints
                $table->dropForeign(['dokumen_id']);
                $table->dropForeign(['lampiran_id']);
            });
            Schema::dropIfExists('media');
        }
    }
};
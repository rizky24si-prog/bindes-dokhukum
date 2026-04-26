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
        Schema::create('lampiran_dokumen', function (Blueprint $table) {
            $table->id('lampiran_id');
            $table->foreignId('dokumen_id')
                  ->constrained('dokumen_hukum', 'dokumen_id')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('keterangan')->nullable();
            $table->foreignId('media_id')->nullable()->constrained('media', 'media_id')->nullOnDelete();
            $table->string('nama_file')->nullable();
            $table->integer('ukuran_file')->nullable()->comment('Ukuran dalam bytes');
            $table->string('tipe_file')->nullable();
            $table->timestamps();
            
            $table->index('dokumen_id');
            $table->index(['dokumen_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran_dokumen');
    }
};
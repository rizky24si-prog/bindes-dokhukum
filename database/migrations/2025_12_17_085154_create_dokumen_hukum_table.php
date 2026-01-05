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
        Schema::create('dokumen_hukum', function (Blueprint $table) {
            $table->id('dokumen_id'); 
            
            $table->unsignedBigInteger('jenis_id'); 
            $table->unsignedBigInteger('kategori_id'); 
            
            $table->string('nomor', 50)->unique();
            $table->string('judul', 200);
            $table->date('tanggal');
            $table->text('ringkasan');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            $table->timestamps();
            
            $table->foreign('jenis_id')
                  ->references('jenis_id') 
                  ->on('jenis_dokumen')    
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
                  
            $table->foreign('kategori_id')
                  ->references('kategori_id') 
                  ->on('kategori')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            
            $table->index(['jenis_id', 'kategori_id']);
            $table->index('nomor');
            $table->index('status');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_hukum');
    }
};
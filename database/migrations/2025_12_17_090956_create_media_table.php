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
        Schema::create('media', function (Blueprint $table) {
            $table->id('media_id');
            
            $table->string('ref_table', 100)->comment('Nama tabel yang direferensi, contoh: dokumen_hukum');
            $table->unsignedBigInteger('ref_id')->comment('ID dari tabel yang direferensi');
            
            $table->string('file_url');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('mime_type', 100);
            $table->integer('file_size')->nullable()->comment('Ukuran file dalam bytes');
            
            $table->string('caption', 255)->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->text('description')->nullable();
            
            $table->integer('sort_order')->default(0);
            
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            
            $table->timestamps();
            
            $table->softDeletes();
            
            $table->index(['ref_table', 'ref_id']);
            $table->index('sort_order');
            $table->index('status');
            $table->index('mime_type');
            $table->index('created_at');
            
            $table->index(['ref_table', 'ref_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
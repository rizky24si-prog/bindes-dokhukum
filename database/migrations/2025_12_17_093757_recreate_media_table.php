<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_hukum', function (Blueprint $table) {
            $table->dropForeign('dokumen_hukum_media_id_foreign');
        });
        
        Schema::dropIfExists('media');
        
        Schema::create('media', function (Blueprint $table) {
            $table->id('media_id');
            $table->string('ref_table', 100);
            $table->unsignedBigInteger('ref_id');
            $table->string('file_name');
            $table->string('caption', 255)->nullable();
            $table->string('mime_type', 100);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['ref_table', 'ref_id']);
            $table->index('sort_order');
            $table->index('mime_type');
        });
        
        Schema::table('dokumen_hukum', function (Blueprint $table) {
            $table->foreign('media_id')
                  ->references('media_id')
                  ->on('media')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_hukum', function (Blueprint $table) {
            $table->dropForeign('dokumen_hukum_media_id_foreign');
        });
        
        Schema::dropIfExists('media');
    }
};
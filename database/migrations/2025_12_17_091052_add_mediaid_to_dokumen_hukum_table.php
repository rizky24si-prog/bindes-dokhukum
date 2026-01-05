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
        Schema::table('dokumen_hukum', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->nullable()->after('status');
            
            $table->foreign('media_id')
                  ->references('media_id')
                  ->on('media')
                  ->onDelete('set null') 
                  ->onUpdate('cascade');
            
            $table->index('media_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_hukum', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            
            $table->dropIndex(['media_id']);
            
            $table->dropColumn('media_id');
        });
    }
};
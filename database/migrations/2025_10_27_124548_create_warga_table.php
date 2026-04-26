<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('warga', function (Blueprint $table) {
        $table->id('warga_id');
        $table->string('no_ktp')->unique(); 
        $table->string('nama');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('agama');
        $table->string('pekerjaan');
        $table->string('telp')->nullable();
        $table->string('email')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};

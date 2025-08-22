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
        Schema::create('tb_riwayat_jabatan', function (Blueprint $table) {
            $table->id('ID_RIWAYAT_JABATAN');
            $table->string('NIP');
            $table->unsignedBigInteger('JABATAN_ID');
            $table->unsignedBigInteger('UNIT_ID');
            $table->date('TGL_MULAI');
            $table->date('TGL_SELESAI')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->string('CREATED_BY')->nullable();
            $table->string('UPDATED_BY')->nullable();
            $table->string('DELETED_BY')->nullable();

            $table->foreign('NIP')->references('NIP')->on('tb_pegawai')->onDelete('cascade');
            $table->foreign('JABATAN_ID')->references('ID_JABATAN')->on('md_jabatan')->onDelete('cascade');
            $table->foreign('UNIT_ID')->references('ID_UNIT_KERJA')->on('md_unit_kerja')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_jabatan');
    }
};
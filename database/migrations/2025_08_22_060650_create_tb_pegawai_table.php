<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_pegawai', function (Blueprint $table) {
            $table->string('NIP', 50)->primary();
            $table->string('NAMA_PEGAWAI', 255);
            $table->string('TEMPAT_LAHIR', 255)->nullable();
            $table->date('TGL_LAHIR')->nullable();
            $table->enum('JENIS_KELAMIN', ['L', 'P']);

            // Relasi manual
            $table->unsignedBigInteger('ID_GOLONGAN')->nullable();
            $table->foreign('ID_GOLONGAN')
                ->references('ID_GOLONGAN')
                ->on('md_golongan')
                ->nullOnDelete();
            $table->unsignedBigInteger('ID_ESELON')->nullable();
            $table->foreign('ID_ESELON')->references('ID_ESELON')->on('md_eselon')->nullOnDelete();

            $table->unsignedBigInteger('ID_UNIT_KERJA')->nullable();
            $table->foreign('ID_UNIT_KERJA')->references('ID_UNIT_KERJA')->on('md_unit_kerja')->nullOnDelete();

            $table->unsignedBigInteger('ID_JABATAN')->nullable();
            $table->foreign('ID_JABATAN')->references('ID_JABATAN')->on('md_jabatan')->nullOnDelete();

            $table->unsignedBigInteger('TEMPAT_TUGAS')->nullable();
            $table->foreign('TEMPAT_TUGAS')
                ->references('ID_KOTA')
                ->on('md_kota')
                ->nullOnDelete();


            $table->string('AGAMA', 100)->nullable();
            $table->string('NO_TELEPON', 50)->nullable();
            $table->string('NPWP', 50)->nullable();
            $table->text('FOTO')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->string('CREATED_BY')->nullable();
            $table->string('UPDATED_BY')->nullable();
            $table->string('DELETED_BY')->nullable();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pegawai');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_pegawai', function (Blueprint $table) {
            $table->string('NIP')->primary();
            $table->string('NAMA_PEGAWAI');
            $table->string('TEMPAT_LAHIR');
            $table->date('TGL_LAHIR');
            $table->enum('JENIS_KELAMIN', ['L', 'P']);
            $table->string('GOLONGAN');
            $table->string('ESELON');
            $table->string('AGAMA');
            $table->string('NO_TELEPON');
            $table->string('NPWP');
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

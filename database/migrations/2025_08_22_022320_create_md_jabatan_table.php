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
        Schema::create('md_jabatan', function (Blueprint $table) {
            $table->id('ID_JABATAN');
            $table->string('NAMA_JABATAN');

            $table->timestamps();
            $table->softDeletes();

            $table->string('CREATED_BY')->nullable();
            $table->string('UPDATED_BY')->nullable();
            $table->string('DELETED_BY')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('md_jabatan');
    }
};
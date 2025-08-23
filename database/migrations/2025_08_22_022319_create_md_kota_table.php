<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('md_kota', function (Blueprint $table) {
            $table->id('ID_KOTA');
            $table->string('NAMA_KOTA');

            $table->timestamps();
            $table->softDeletes();

            $table->string('CREATED_BY')->nullable();
            $table->string('UPDATED_BY')->nullable();
            $table->string('DELETED_BY')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('md_kota');
    }
};

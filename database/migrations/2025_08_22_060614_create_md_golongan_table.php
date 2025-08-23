<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('md_golongan', function (Blueprint $table) {
            $table->bigIncrements('ID_GOLONGAN');
            $table->string('NAMA_GOLONGAN', 50);
            $table->timestamps();
            $table->softDeletes();
            $table->string('CREATED_BY')->nullable();
            $table->string('UPDATED_BY')->nullable();
            $table->string('DELETED_BY')->nullable();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('md_golongan');
    }
};

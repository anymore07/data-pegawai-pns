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
        Schema::create('md_unit_kerja', function (Blueprint $table) {
            $table->id('ID_UNIT_KERJA');
            $table->string('NAMA_UNIT');
            $table->string('LOKASI')->nullable();

            // self reference (komponen tree)
            $table->unsignedBigInteger('PARENT_ID')->nullable();
            $table->foreign('PARENT_ID')
                ->references('ID_UNIT_KERJA')
                ->on('md_unit_kerja')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

            $table->string('CREATED_BY')->nullable();
            $table->string('UPDATED_BY')->nullable();
            $table->string('DELETED_BY')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('md_unit_kerja');
    }
};

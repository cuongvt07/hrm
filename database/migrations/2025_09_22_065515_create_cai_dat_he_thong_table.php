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
        Schema::create('cai_dat_he_thong', function (Blueprint $table) {
            $table->id();
            $table->string('ten_cai_dat', 100)->unique();
            $table->text('gia_tri_cai_dat');
            $table->string('mo_ta', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cai_dat_he_thong');
    }
};

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
        Schema::create('phong_ban', function (Blueprint $table) {
            $table->id();
            $table->string('ten_phong_ban', 200);
            $table->unsignedBigInteger('phong_ban_cha_id')->nullable();
            $table->timestamps();
            
            $table->foreign('phong_ban_cha_id')->references('id')->on('phong_ban')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phong_ban');
    }
};

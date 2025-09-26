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
        Schema::create('cai_dat_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('danh_muc_id')->constrained('cai_dat_he_thong')->onDelete('cascade');
            $table->string('ten_item');
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cai_dat_item');
    }
};

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
        Schema::create('calorie_targets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('target_burned_calories_day')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calorie_targets');
    }
};

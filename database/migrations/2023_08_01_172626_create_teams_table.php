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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('play_style', ['technical', 'physical', 'aerial']);
            $table->enum('tactic', ['high_press', 'deep_defense', 'attacking']);
            $table->enum('strength', ['counter_attack', 'full_defense']);
            $table->enum('weakness', ['press_resistance', 'breaking_defense']);
            $table->integer('power');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('championship_id');
            $table->unsignedBigInteger('home_team');
            $table->unsignedBigInteger('away_team');
            $table->integer('home_team_score');
            $table->integer('away_team_score');
            $table->dateTime('match_date');
            $table->timestamps();

            $table->foreign('championship_id')->references('id')->on('championships');
            $table->foreign('home_team')->references('id')->on('teams');
            $table->foreign('away_team')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};

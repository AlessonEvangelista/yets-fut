<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rules_game_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('players_count_per_team')
                ->default(6);
            $table->boolean('sort_players')
                ->default(true);
            $table->boolean('leveling')
                ->default(true);
            $table->boolean('goalkeeper')
                ->default(true);
            $table->unsignedBigInteger('game_settings');
            $table->foreign('game_settings')
                ->references('id')
                ->on('game_settings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules_game_settings');
    }
};

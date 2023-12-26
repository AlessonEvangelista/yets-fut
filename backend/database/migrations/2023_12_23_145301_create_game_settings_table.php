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
        Schema::create('game_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('players_count_per_team')
                ->default(6);
            $table->boolean('sort_players')
                ->default(true);
            $table->boolean('leveling')
                ->default(true);
            $table->boolean('goalkeeper')
                ->default(true);
            $table->date('game_date');
            $table->boolean('active')
                ->default(false);
            $table->unsignedBigInteger('soccer_ginasium_id');
            $table->foreign('soccer_ginasium_id')
                ->references('id')
                ->on('soccer_ginasium');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_settings');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'players_count_per_team',
        'sort_players',
        'leveling',
        'goalkeeper',
        'game_date',
        'soccer_ginasium_id',
        'active',
    ];

    /**
     * Includes for search in API ( Not Implement ON MVP. But Set on Model for fuctures features).
     */
    public $allowedIncludes = [
        'soccerGinasium' => 'soccerGinasium'
    ];

    /**
     * Belong to to SoccerGinasium.
     */
    public function soccerGinasium(): BelongsTo
    {
        return $this->belongsTo(SoccerGinasium::class, 'soccer_ginasium_id');
    }

    /**
     * Has Many to Teams per Game.
     */
    public function Teams(): HasMany
    {
        return $this->hasMany(TeamPlayers::class, 'gameSettings');
    }
}

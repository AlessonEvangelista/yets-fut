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
        'game_date',
        'active',
    ];

    /**
     * Includes for search in API ( Not Implement ON MVP. But Set on Model for fuctures features).
     */
    public $allowedIncludes = [];

    /**
     * Belong to to SoccerGinasium.
     */
    public function SoccerGinasium(): BelongsTo
    {
        return $this->belongsTo(SoccerGinasium::class, 'soccer_ginasium');
    }

    /**
     * Has Many to Teams per Game.
     */
    public function Teams(): HasMany
    {
        return $this->hasMany(TeamPlayers::class, 'gameSettings');
    }

    /**
     * Has Many to Rules per Game.
     */
    public function Rules(): HasMany
    {
        return $this->hasMany(RulesGameSetting::class, 'GameSetting');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'soccer_field',
        'address',
        'game_date',
    ];

    /**
     * Includes for search in API ( Not Implement ON MVP. But Set on Model for fuctures features).
     */
    public $allowedIncludes = [];

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

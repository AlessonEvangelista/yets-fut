<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RulesGameSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'players_count_per_team',
        'sort_players',
        'leveling',
        'goalkeeper',
    ];

    /**
     * Includes for search in API ( Not Implement ON MVP. But Set on Model for fuctures features).
     */
    public $allowedIncludes = [];

    public function GameSetting(): BelongsTo
    {
        return $this->belongsTo(GameSettings::class, 'game_settings');
    }
}

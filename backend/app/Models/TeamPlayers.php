<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamPlayers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'game_settings_id'
    ];

    /**
     * Includes for search in API ( Not Implement ON MVP. But Set on Model for fuctures features).
     */
    public $allowedIncludes = [
        'gameSettings' => 'gameSettings',
        'players' => 'players'
    ];

    /**
     * Relaption with game settings.
     */
    public function gameSettings(): BelongsTo
    {
        return $this->belongsTo(GameSettings::class, 'game_settings_id');
    }

    /**
     * Has Many with Players per Team.
     */
    public function players(): HasMany
    {
        return $this->hasMany(Players::class);
    }
}

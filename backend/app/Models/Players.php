<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Players extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'users_id',
        'team_players_id'
    ];

    /**
     * Includes for search in API ( Not Implement ON MVP. But Set on Model for fuctures features).
     */
    public $allowedIncludes = [];

    /**
     * Relaption with User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Relaption with team players.
     */
    public function teamPlayers(): BelongsTo
    {
        return $this->belongsTo(TeamPlayers::class, 'team_players_id');
    }
}

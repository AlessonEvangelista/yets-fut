<?php

namespace App\Http\Resources\Api\V1;

use App\Models\SoccerGinasium;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameSettingsResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'players_count_per_team' => $this->players_count_per_team,
            'sort_players' => (bool)$this->sort_players,
            'leveling' => (bool)$this->leveling,
            'goalkeeper' => (bool)$this->goalkeeper,
            'game_date' => $this->game_date,
            'active' => (bool)$this->active,
            'soccer_ginasium' => new SoccerGinasiumResources( $this->soccerGinasium )
        ];
    }
}

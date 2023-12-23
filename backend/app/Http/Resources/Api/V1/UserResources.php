<?php

namespace App\Http\Resources\Api\V1;

use App\Enum\Level;
use App\Enum\PositionPlay;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'position_play' => PositionPlay::getById($this->position_play->value),
            'level' => Level::getById($this->level->value),
        ];
    }
}

<?php

namespace App\Services\Api\V1;

use App\Http\Resources\Api\V1\TeamPlayersResources;
use App\Models\Players;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class TeamPlayersService extends BaseService
{
    public function __construct()
    {
    }

    public function presenceConfirm(array $data): array
    {
        try {
            $transaction = DB::transaction(function () use ($data) {
                foreach ($data['players'] as $player) {
                    $callback[] = Players::create([
                        'users_id' => $player,
                        'team_players_id' => $data['team']
                    ]);
                }
                return $callback;
            });
            $response = $transaction;
        } catch (\Exception $e) {
            $this->status = 400;
            $response = $e->getMessage();
        }

        return ['status' => $this->status, 'data' => $response];
    }

}

<?php

namespace App\Services\Api\V1;

use App\Enum\Level;
use App\Http\Resources\Api\V1\TeamPlayersResources;
use App\Models\Players;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeamPlayersService extends BaseService
{
    public function __construct()
    {
    }

    /**
     * @param array $data
     * @return array
     */
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

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function sortTeam(int $id): array
    {
        $team = $this->show($id);
        if( gettype($team['data']) == "string") {
            return $team;
        }

        $gameSetting = $team['data']->gameSettings()->get()->all()[0];

        /**
         * Verify players confirmed if minimum necessary
         */
        if(!$this->playersMin($team['data'], $gameSetting->players_count_per_team*2)) {
            return ['status' => 400, 'data' => 'Minimal players not reached!'];
        }

        $players = $team['data']->players()->get();

        /**
         * If game need goalkeeper by config. verify minimum 2 per team
         */
        if($gameSetting->goalkeeper) {
            if(!$this->needGoalKeeper($players->all()) ) {
                return ['status' => 400, 'data' => 'Need minimal 2 Goalkeeper to Play'];
            }
        }

        /**
         * If leveling by config, sort Teams by leveling
         */
        if($gameSetting->leveling) {
            if(!$this->sort($players, $gameSetting->players_count_per_team)) {
                return ['status' => 400, 'data' => 'Nop Sort Teeam. Contact adm'];
            }
        }
        $team['data']->gameSettings()->get()->all()[0]->update(['active' => true]);

        return ['status' => $this->status, 'data' => 'Sort Team successful'];
    }

    /**
     * @param Model $team
     * @param int $minimal
     * @return bool
     */
    private function playersMin(Model $team, int $minimal) : bool
    {
        $playersConfirmed = count($team->players()->get()->all());
        if($playersConfirmed >= $minimal)
            return true;

        return false;
    }

    /**
     * @param array $players
     * @return bool
     */
    private function needGoalKeeper(array $players) : bool
    {
        $count_goalkeeper=0;
        foreach ($players as $player) {
            $count_goalkeeper += ($player->user()->get()->all()[0]->position_play->name == "goalkeeper") ? 1 : 0 ;
        }
        if ($count_goalkeeper >= 2)
            return true;

        return false;
    }

    /**
     * @param Collection $players
     * @param int $teams
     * @return bool
     */
    private function sort(Collection $players, int $teams) : bool
    {
        $lastTeam=1;
        $tteams=[];

        try {
            foreach ($players as $key => $player) {
                if ($key + 1 <= $teams * 2) {
                    $tteams[$key] = [
                        'id' => $player->id,
                        'team' => $lastTeam
                    ];

                    $lastTeam = ($lastTeam < 2) ? $lastTeam + 1 : 1;
                }
            }

            foreach ($tteams as $items) {
                Players::where('id', $items['id'])->update(['team_id' => $items['team']]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}

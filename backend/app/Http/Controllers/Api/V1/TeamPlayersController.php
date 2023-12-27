<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TeamPlayersResources;
use App\Models\Players;
use App\Models\TeamPlayers;
use App\Services\Api\V1\TeamPlayersService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamPlayersController extends Controller
{
    use HttpResponses;
    protected $validated;

    private function validData()
    {
        $this->validated = [
            'name' => 'required',
            'game_settings_id' => 'required'
        ];
    }

    public function index() : JsonResponse
    {
        $services = (new TeamPlayersService())
            ->setModel(TeamPlayers::class)
            ->index();

        if ($services['status'] == 200) {
            return $this->response('Success', $services['status'], TeamPlayersResources::collection($services['data']));
        } else {
            return $this->errors('Something Wrong: '.$services['data'], $services['status']);
        }
    }

    public function store(Request $request) : JsonResponse
    {
        $this->validData();
        $created = new TeamPlayersResources(
            (new TeamPlayersService())
                ->setModel(TeamPlayers::class)
                ->store($request->all(), $this->validated)
        );

        if ($created['status'] == 200) {
            return $this->response('Sucesso', $created['status'], $created['data']);
        } elseif ($created['status'] == 422) {
            return $this->errors('Something wrong: '.$created['data'][0], $created['status'], $created['data'][1]);
        } else {
            return $this->errors('Something wrong: '.$created['data'], $created['status']);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = (new TeamPlayersService())
            ->setModel(TeamPlayers::class)
            ->destroy($id);

        if ($deleted['status'] == 200) {
            return $this->response('Success', $deleted['status']);
        } else {
            return $this->errors($deleted['data'], $deleted['status']);
        }
    }

    public function playersConfirm(Request $request)
    {
        $presenceConfirm =
            (new TeamPlayersService())
                ->setModel(Players::class)
                ->presenceConfirm($request->all());

        if ($presenceConfirm['status'] == 200) {
            return $this->response('Sucesso', $presenceConfirm['status'], $presenceConfirm['data']);
        } else {
            return $this->errors('Something wrong: '.$presenceConfirm['data'], $presenceConfirm['status']);
        }
    }
}

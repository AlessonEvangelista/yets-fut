<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GameSettingsResources;
use App\Models\GameSettings;
use App\Services\Api\V1\GameSettingsService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameSettingsController extends Controller
{
    use HttpResponses;
    protected $validated;

    public function validData()
    {
        $this->validated = [
            'game_date' => 'required|date_format:Y-m-d H:i:s',
            'soccer_ginasium_id' => 'required',
            'players_count_per_team' => 'required'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $services = (new GameSettingsService())
            ->setModel(GameSettings::class)
            ->index();

        if ($services['status'] == 200) {
            return $this->response('Success', $services['status'], GameSettingsResources::collection($services['data']));
        } else {
            return $this->errors('Something Wrong: '.$services['data'], $services['status']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $service = (new GameSettingsService())
            ->setModel(GameSettings::class)
            ->show($id);

        if ($service['status'] == 200) {
            return $this->response('Success', $service['status'], new GameSettingsResources($service['data']));
        } else {
            return $this->errors('Something Wrong: '.$service['data'], $service['status']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validData();
        $created = new GameSettingsResources(
            (new GameSettingsService())
                ->setModel(GameSettings::class)
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validData();
        $updated = new GameSettingsResources(
            (new GameSettingsService())
                ->setModel(GameSettings::class)
                ->update($request->all(), $this->validated, $id)
        );

        if ($updated['status'] == 200) {
            return $this->response('Sucesso', $updated['status'], $updated['data']);
        } elseif ($updated['status'] == 422) {
            return $this->errors('Something wrong: '.$updated['data'][0], $updated['status'], $updated['data'][1]);
        } else {
            return $this->errors('Something wrong: '.$updated['data'], $updated['status']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = (new GameSettingsService())
            ->setModel(GameSettings::class)
            ->destroy($id);

        if ($deleted['status'] == 200) {
            return $this->response('Success', $deleted['status']);
        } else {
            return $this->errors($deleted['data'], $deleted['status']);
        }
    }
}

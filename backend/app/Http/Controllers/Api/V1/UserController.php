<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResources;
use App\Models\User;
use App\Services\Api\V1\UserService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;

    protected $validated;

    private function validData()
    {
        $this->validated = [
            'name' => 'required',
            'email' => 'required|email',
            'position_play' => 'required|numeric|between:1,4',
            'level' => 'required|numeric|between:1,5',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $services = (new UserService())
            ->setModel(User::class)
            ->index();

        if ($services['status'] == 200) {
            return $this->response('Success', $services['status'], UserResources::collection($services['data']));
        } else {
            return $this->errors('Something Wrong: '.$services['data'], $services['status']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $service = (new UserService())
            ->setModel(User::class)
            ->show($id);

        if ($service['status'] == 200) {
            return $this->response('Success', $service['status'], new UserResources($service['data']));
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
        $created = new UserResources(
            (new UserService())
                ->setModel(User::class)
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
        $updated = new UserResources(
            (new UserService())
                ->setModel(User::class)
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
        $deleted = (new UserService())
            ->setModel(User::class)
            ->destroy($id);

        if ($deleted['status'] == 200) {
            return $this->response('Success', $deleted['status']);
        } else {
            return $this->errors($deleted['data'], $deleted['status']);
        }
    }
}

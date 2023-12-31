<?php

namespace App\Services;

use App\Services\Contracts\BaseServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BaseService implements BaseServiceInterface
{
    protected ?Model $defaultModel;
    private ?Builder $customQuery = null;
    protected array $data = [];
    protected array $searchableColumns = [];
    protected int $status = 200;

    /**
     * @return array
     * @throws \Exception
     */
    public function index(): array
    {
        $data = '';
        $query = $this->defaultQuery();

        if (request()->include) {
            $relations = explode(',', request()->include);

            if ($diff = array_diff($relations, $this->defaultModel->allowedIncludes)) {
                return throw new \Exception("The following includes are not allowed: '".implode(',', $diff)."', enabled: '".implode(',', $this->defaultModel->allowedIncludes)."'");
            }
            $query->with($relations ?? []);
        }

        if (!empty(request()->search) && count($this->searchableColumns) > 0) {
            $query->where(function ($subQuery) {
                foreach ($this->searchableColumns as $column) {
                    $searchString = str_replace(' ', '%', trim(request()->search));
                    $subQuery->orWhere($column, 'LIKE', "%{$searchString}%");
                }
            });
        } elseif (!empty(request()->search) && 0 === count($this->searchableColumns)) {
            $this->status = 400;
            $data = 'Parameter search not enabled this route.';
        }

        if (!$data) {
            $data = $query->get();
        }

        return ['status' => $this->status, 'data' => $data];
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function show(int $id): array
    {
        $query = $this->defaultQuery();
        $data = null;

        if (request()->include) {
            $relations = explode(',', request()->include);
            if ($diff = array_diff($relations, array_keys($this->defaultModel->allowedIncludes))) {
                throw new \Exception("The following includes are not allowed: '".implode(',', $diff)."', enabled: '".implode(',', array_keys($this->defaultModel->allowedIncludes))."'");
            }
            $query->with($relations ?? []);
        }

        try {
            $data = $query->findOrFail($id);
        } catch (\Exception $e) {
            $data = 'No result for data ID: '.$id;
            $this->status = 400;
        }

        return ['status' => $this->status, 'data' => $data];
    }

    /**
     * @param array $data
     * @param array $validData
     * @return array
     */
    public function store(array $data, array $validData)
    {
        $valid = Validator::make($data, $validData);
        $response = null;

        if ($valid->fails()) {
            $this->status = 422;
            $response = ['Data Invalid', $valid->errors()];
        } else {
            try {
                $transaction = DB::transaction(function () use ($data) {
                    $callback = $this->defaultModel->create($data);

                    foreach ($data as $indice => $value) {
                        if (is_array($value)) {
                            $callback->$indice()->sync($value);
                        }
                    }

                    return $callback->refresh();
                });
                $response = $transaction;
            } catch (\Exception $e) {
                $this->status = 400;
                $response = $e->getMessage();
            }
        }

        return ['status' => $this->status, 'data' => $response];
    }

    /**
     * @param array $data
     * @param array $validator
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function update(array $data, array $validator, int $id)
    {
        $valid = Validator::make($data, $validator);
        $response = null;

        if ($valid->fails()) {
            $this->status = 422;
            $response = ['Data Invalid', $valid->errors()];
        } else {
            $show = $this->show($id);
            $model = $show['data'];

            if(gettype($model) === 'object') {
                try {
                    $transaction = DB::transaction(function () use ($data, $model) {
                        $model->update($data);

                        foreach ($data as $indice => $value) {
                            if (is_array($value)) {
                                $model->$indice()->sync($value, false);
                            }
                        }

                        return $model->refresh();
                    });
                    $response = $transaction;
                } catch (\Exception $e) {
                    $this->status = 400;
                    $response = $e->getMessage();
                }
            } else {
                $this->status = 400;
                $response = "Object id: ". $id ." Not find";
            }
        }

        return ['status' => $this->status, 'data' => $response];
    }

    /**
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        $response = null;
        $element = $this->show($id);
        $model = $element['data'];

        if(gettype($model) === 'object') {
            try {
                $response = $model->delete();
            } catch (\Exception $e) {
                $this->status = 400;
                $response = $e->getMessage();
            }
        } else {
            $this->status = 400;
            $response = "Object id: ". $id ." Not find";
        }

        return ['status' => $this->status, 'data' => $response];
    }

    /**
     * @param string|Model $value
     * @return $this
     */
    public function setModel(string|Model $value): self
    {
        $this->defaultModel = $value instanceof Model ? $value : new $value();

        return $this;
    }

    public function getModel(): Model
    {
        return $this->defaultModel;
    }

    protected function defaultQuery(): Builder
    {
        return $this->customQuery ?? $this->defaultModel::query();
    }
}

<?php

namespace App\Services\Api\V1;

use App\Services\BaseService;

class GameSettingsService extends BaseService
{
    public function __construct()
    {
        $this->searchableColumns = [
            'date'
        ];
    }

    public function store(array $data, array $validData)
    {
        $query = $this->defaultQuery();
        $query->where('game_date', $data['game_date'])
            ->where('soccer_ginasium_id', $data['soccer_ginasium_id']);

        if($query->get()->all())
            return ['status' => 400, 'data' => "Something Wrong: Data alread exists!"];
        else
            return parent::store($data, $validData);
    }
}

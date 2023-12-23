<?php

namespace App\Services\Api\V1;

use App\Services\BaseService;

class SoccerGinasiumService extends BaseService
{

    public function __construct()
    {
        $this->searchableColumns = [
            'name'
        ];
    }
}

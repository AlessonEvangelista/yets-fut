<?php

namespace App\Services\Contracts;

interface BaseServiceInterface
{
    public function index();

    public function show(int $id);

    public function store(array $data, array $validData);

    public function update(array $data, array $validator, int $id);

    public function destroy(int $id);
}

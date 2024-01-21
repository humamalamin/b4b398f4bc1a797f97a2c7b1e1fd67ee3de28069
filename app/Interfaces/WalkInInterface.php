<?php

namespace App\Interfaces;

interface WalkInInterface
{
    public function index(
        $statusId = null,
        $requestDate = null,
        $requestTime = null,
        $tableId = null,
        $name = null,
        $orderBy = 'created_at',
        $orderType = 'desc'
    );

    public function lockForUpdate(array $data);

    public function show($tableID);

    public function store(array $data);

    public function update($tableID, array $data);

    public function delete($tableID);
}

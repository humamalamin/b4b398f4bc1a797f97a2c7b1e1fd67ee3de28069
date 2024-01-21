<?php

namespace App\Interfaces;

interface TableModelInterface
{
    public function index(
        $isAvailable = null,
        $capacity = -1,
        $perPage = 10,
        $orderBy = 'created_at',
        $orderType = 'desc'
    );

    public function show($tableID);

    public function store(array $data);

    public function update($tableID, array $data);

    public function delete($tableID);
}
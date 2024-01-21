<?php

namespace App\Repositories;
use App\Interfaces\TableModelInterface;
use App\Models\TableModel;

class TableModelRepository implements TableModelInterface
{
    protected $model;

    public function __construct(TableModel $model)
    {
        $this->model = $model;
    }

    public function index(
        $isAvailable = null,
        $capacity = -1,
        $perPage = 10,
        $orderBy = 'created_at',
        $orderType = 'desc'
        )
    {
        $lists = $this->model;
        if (!empty($isAvailable)) {
            $lists = $lists->where("is_available", $isAvailable);
        }

        if ($capacity > 0) {
            $lists = $lists->where("capacity", $capacity);
        }

        return $lists->orderBy($orderBy, $orderType)->paginate($perPage);
    }

    public function show($tableID)
    {
        return $this->model->findOrFail($tableID);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update($tableID, array $data)
    {
        return $this->model->findOrFail($tableID)->update($data);
    }

    public function delete($tableID)
    {
        return $this->model->findOrFail($tableID)->delete();
    }
}

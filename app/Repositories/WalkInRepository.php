<?php

namespace App\Repositories;
use App\Interfaces\WalkInInterface;
use App\Models\TableModel;
use App\Models\WalkIn;

class WalkInRepository implements WalkInInterface
{
    protected $model;
    protected $tableModel;

    public function __construct(
        WalkIn $model,
        TableModel $tableModel
    )
    {
        $this->model = $model;
        $this->tableModel = $tableModel;
    }

    public function index(
        $statusId = null,
        $requestDate = null,
        $requestTime = null,
        $tableId = null,
        $name = null,
        $orderBy = 'created_at',
        $orderType = 'desc'
    )
    {
        return $this->model->with(['user', 'table', 'status'])
            ->when(!empty($statusId), function ($query) use ($statusId) {
                $query->where('status_id', $statusId);
            })
            ->when(!empty($tableId), function ($query) use ($tableId) {
                $query->where('table_id', $tableId);
            })
            ->when(!empty($name), function ($query, $name) {
                $query->whereHas('user', function ($query2) use ($name) {
                    $query2->where('name', 'like', '%' . $name . '%');
                });
            })
            ->when(!empty($requestDate), function ($query, $requestDate) {
                $query->whereDate('request_date', $requestDate);
            })
            ->when(!empty($requestTime), function ($query, $requestTime) {
                $query->whereTime('request_time', '=', $requestTime);
            })
            ->orderBy($orderBy, $orderType)
            ->paginate(10);
    }

    public function lockForUpdate(array $data)
    {
        return $this->tableModel
            ->where('id', $data['table_id'])
            ->lockForUpdate()
            ->first();
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

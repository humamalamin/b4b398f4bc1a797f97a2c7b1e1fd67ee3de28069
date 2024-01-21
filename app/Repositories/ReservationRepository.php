<?php

namespace App\Repositories;

use App\Interfaces\ReservationInterface;
use App\Models\Reservation;

class ReservationRepository implements ReservationInterface
{
    protected $model;

    public function __construct(Reservation $model)
    {
        $this->model = $model;
    }

    public function index(
        $statusId = null,
        $reservationDate = null,
        $reservationTime = null,
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
            ->when(!empty($reservationDate), function ($query, $reservationDate) {
                $query->whereDate('reservation_date', $reservationDate);
            })
            ->when(!empty($reservationTime), function ($query, $reservationTime) {
                $query->whereTime('reservation_time', '=', $reservationTime);
            })
            ->orderBy($orderBy, $orderType)
            ->paginate(10);
    }

    public function show($tableID)
    {
        return $this->model->findOrFail($tableID);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function lockForUpdate(array $data)
    {
        return $this->model
            ->whereDate('reservation_date', $data['reservation_date'])
            ->whereTime('reservation_time', $data['reservation_time'])
            ->where('table_id', $data['table_id'])
            ->lockForUpdate()
            ->first();
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

<?php

namespace App\Repositories;

use App\Interfaces\ReservationStatusInterface;
use App\Models\ReservationStatus;

class ReservationStatusRepository implements ReservationStatusInterface
{
    protected $model;

    public function __construct(ReservationStatus $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->all();
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }
    
    public function getByName($name)
    {
        return $this->model->where("name", $name)->first();
    }
}

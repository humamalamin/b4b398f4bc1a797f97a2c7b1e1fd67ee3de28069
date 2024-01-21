<?php

namespace App\Interfaces;

interface ReservationStatusInterface
{
    public function index();

    public function show($id);

    public function getByName($name);
}

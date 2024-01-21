<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Interfaces\ReservationStatusInterface;
use Illuminate\Http\Request;

class ReservationStatusController extends Controller
{
    private $reservStatusRepo;

    public function __construct(ReservationStatusInterface $reservStatusRepo)
    {
        $this->reservStatusRepo = $reservStatusRepo;
    }

    public function index()
    {
        $reservStatuses = $this->reservStatusRepo->index();

        return ResponseFormatter::success($reservStatuses);
    }
}

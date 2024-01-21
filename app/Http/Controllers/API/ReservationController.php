<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreRequest;
use App\Interfaces\ReservationInterface;
use App\Interfaces\ReservationStatusInterface;
use App\Interfaces\TableModelInterface;
use App\Jobs\Reservation\CancelBookingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    private $reservationRepo;
    private $tableModelRepo;
    private $reservStatusRepo;

    public function __construct(
        ReservationInterface $reservationRepo,
        TableModelInterface $tableModelRepo,
        ReservationStatusInterface $reservStatusRepo,
    )
    {
        $this->reservationRepo = $reservationRepo;
        $this->tableModelRepo = $tableModelRepo;
        $this->reservStatusRepo = $reservStatusRepo;
    }

    public function index(Request $request)
    {
        $orderBy = $request->query("order_by") ?? 'created_at';
        $orderType = $request->query("order_type") ?? 'desc';
        $statusId = $request->query("status_id") ?? null;
        $reservationDate = $request->query("reservation_date") ?? null;
        $reservationTime = $request->query("reservation_time") ?? null;
        $tableId = $request->query("table_id") ?? null;
        $name = $request->query("name") ?? null;

        $lists = $this->reservationRepo->index(
            $statusId,
            $reservationDate,
            $reservationTime,
            $tableId,
            $name,
            $orderBy,
            $orderType,
        );

        return ResponseFormatter::success($lists, 'success');
    }

    public function show($id)
    {
        $reservation = $this->reservationRepo->show($id);

        return ResponseFormatter::success($reservation);
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $status = $this->reservStatusRepo->getByName('Booked');
            $request['user_id'] = auth()->user()->id;
            $request['status_id'] = $status->id;
            $reservation = $this->reservationRepo->lockForUpdate($request->all());
            if (empty($reservation)) {
                $reservation = $this->reservationRepo->store($request->all());
                if ($status->name === 'Booked') {
                    $this->tableModelRepo->update($request->table_id, ['is_available' => false]);
                }
                $reservationDateTime = Carbon::parse(
                    $request->reservation_date . ' ' . $request->reservation_time
                );
                CancelBookingJob::dispatch($reservation)
                    ->delay($reservationDateTime->addMinutes(20));
                DB::commit();
                return ResponseFormatter::success(null, 'success', 201);
            } else {
                DB::rollBack();
                return ResponseFormatter::error(null, 'Reservation already exists', 422);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseFormatter::error(null, $th->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->reservationRepo->update($id, $request->all());
            DB::commit();
            return ResponseFormatter::success(null,'success');
        } catch (\Throwable $th) {
            DB::rollBack();

            return ResponseFormatter::error(null, $th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->reservationRepo->delete($id);
            DB::commit();
            return ResponseFormatter::success(null, 'success');
        } catch (\Exception $th) {
            DB::rollBack();
            return ResponseFormatter::error($th->getMessage());
        }
    }
}

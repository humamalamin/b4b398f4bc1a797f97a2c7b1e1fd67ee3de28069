<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\WalkIn\StoreRequest;
use App\Interfaces\AuthInterface;
use App\Interfaces\ReservationStatusInterface;
use App\Interfaces\TableModelInterface;
use App\Interfaces\WalkInInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalkInController extends Controller
{
    private $walkInRepo;
    private $reservStatusRepo;
    private $authRepo;
    private $tableModelRepo;

    public function __construct(
        WalkInInterface $walkInRepo,
        ReservationStatusInterface $reservStatusRepo,
        AuthInterface $authRepo,
        TableModelInterface $tableModelRepo,
    )
    {
        $this->walkInRepo = $walkInRepo;
        $this->reservStatusRepo = $reservStatusRepo;
        $this->authRepo = $authRepo;
        $this->tableModelRepo = $tableModelRepo;
    }

    public function index(Request $request)
    {
        $orderBy = $request->query("order_by") ?? 'created_at';
        $orderType = $request->query("order_type") ?? 'desc';
        $statusId = $request->query("status_id") ?? null;
        $requestDate = $request->query("request_date") ?? null;
        $requestTime = $request->query("request_time") ?? null;
        $tableId = $request->query("table_id") ?? null;
        $name = $request->query("name") ?? null;

        $lists = $this->walkInRepo->index(
            $statusId,
            $requestDate,
            $requestTime,
            $tableId,
            $name,
            $orderBy,
            $orderType,
        );

        return ResponseFormatter::success($lists, 'success');
    }

    public function show($id)
    {
        $data = $this->walkInRepo->show($id);

        return ResponseFormatter::success($data,'success');
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $status = $this->reservStatusRepo->getByName('On Progress');
            $user = $this->authRepo->profile($request->username);

            $request['user_id'] = $user->id;
            $request['status_id'] = $status->id;
            $reservation = $this->walkInRepo->lockForUpdate($request->all());

            if ($reservation) {
                $reservation = $this->walkInRepo->store($request->all());

                $this->tableModelRepo->update($request->table_id, ['is_available' => false]);

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
            $this->walkInRepo->update($id, $request->all());
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
            $this->walkInRepo->delete($id);
            DB::commit();
            return ResponseFormatter::success(null, 'success');
        } catch (\Exception $th) {
            DB::rollBack();
            return ResponseFormatter::error($th->getMessage());
        }
    }
}

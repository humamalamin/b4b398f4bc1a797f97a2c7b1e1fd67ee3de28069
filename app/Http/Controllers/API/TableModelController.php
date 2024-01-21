<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TableModel\StoreRequest;
use App\Interfaces\TableModelInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableModelController extends Controller
{
    private $tableRepo;

    public function __construct(TableModelInterface $tableRepo)
    {
        $this->tableRepo = $tableRepo;
    }

    public function index(Request $request)
    {
        $orderBy = $request->query("order_by") ?? 'created_at';
        $orderType = $request->query("order_type") ?? 'desc';
        $perPage = $request->query("per_page") ?? 10;
        $isAvailable = $request->query("is_available") ?? null;
        $capacity = $request->query("capacity") ?? -1;

        $lists = $this->tableRepo->index(
            $isAvailable,
            $capacity,
            $perPage,
            $orderBy,
            $orderType,
        );

        return ResponseFormatter::success($lists, 'success');
    }

    public function show($id)
    {
        try {
            $data = $this->tableRepo->show($id);

            return ResponseFormatter::success($data);
        } catch (\Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->tableRepo->store($request->all());
            DB::commit();
            return ResponseFormatter::success(null, 'success', 201);
        } catch (\Exception $th) {
            DB::rollBack();

            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->tableRepo->update($id, $request->all());
            DB::commit();
            return ResponseFormatter::success(null, 'success', 200);
        } catch (\Exception $th) {
            DB::rollBack();
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->tableRepo->delete($id);
            DB::commit();
            return ResponseFormatter::success(null, 'success');
        } catch (\Exception $th) {
            DB::rollBack();
            return ResponseFormatter::error($th->getMessage());
        }
    }
}

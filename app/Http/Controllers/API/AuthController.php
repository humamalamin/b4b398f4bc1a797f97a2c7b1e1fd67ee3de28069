<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $authRepo;

    public function __construct(AuthInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function login(LoginRequest $request)
    {
        $dataUser = $this->authRepo->login($request->email, $request->password);
        if ($dataUser) {
            if (Hash::check($request->password, $dataUser->password)) {
                $token = $dataUser->createToken($request->email)->accessToken;

                return ResponseFormatter::success(['token' => $token], 'success');
            } else {
                return ResponseFormatter::error(null, 'Password/E-mail Invalid', 401);
            }
        } else {
            return ResponseFormatter::error(null,'Data not found', 404);
        }
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->authRepo->register($request->all());

            DB::commit();
            return ResponseFormatter::success(null,'success', 201);
        } catch (\Exception $th) {
            DB::rollBack();

            return ResponseFormatter::error(null, $th->getMessage(),400);
        }
    }
}

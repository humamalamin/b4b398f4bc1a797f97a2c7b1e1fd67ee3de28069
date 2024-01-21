<?php

namespace App\Exceptions;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 404);
        });

        $this->renderable(function (ValidationException $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 422);
        });

        $this->renderable(function (AuthenticationException $e) {
            return ResponseFormatter::error(null, $e->getMessage(), 401);
        });

        $this->renderable(function (\Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        });
    }
}

<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render the exception handling callbacks for the application.
    */
    public function render($request , Throwable $e ) {
        // Exceptionによりハンドルする場合
        if($e instanceof ValidationException) {

        } else {
            return response()->json(
                ['message' => "APIエラーが発生しました。"],
                500,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
        return parent::render($request, $e);
    }
}

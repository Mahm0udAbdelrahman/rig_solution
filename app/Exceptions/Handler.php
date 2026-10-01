<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Forms submitted with AJAX must never show raw SQL to the user: the full error is still logged
        $this->renderable(function (QueryException $e, $request) {
            if (!$request->expectsJson()) {
                return null;
            }

            // 1048: column cannot be null, 1364: field has no default value
            $missingValue = in_array((int) ($e->errorInfo[1] ?? 0), [1048, 1364], true);

            return response()->json([
                'message' => $missingValue
                    ? 'Could not save: some required information is missing. Please check the form and try again.'
                    : 'Could not save because of a database error. Please try again or contact support.',
            ], $missingValue ? 422 : 500);
        });
    }
}

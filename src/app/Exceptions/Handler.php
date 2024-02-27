<?php

namespace App\Exceptions;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {

        Log::alert('E: ',[ 'success' => FALSE , 'data' => [ 'code' => $exception->getCode(), 'message' => $exception instanceof ValidationException ? $exception->errors() : $exception->getMessage() ,'r'=>$request->path() , 'ip'=>$request->ip()]]);
        return $request->expectsJson() ? response()->json([ 'success' => FALSE , 'data' => [ 'code' => $exception->getCode(), 'message' => $exception instanceof ValidationException ? $exception->errors() : $exception->getMessage()  ] ], ($exception->getMessage() =='Unauthenticated.'?401:200)) : parent::render($request, $exception);
    }
}

<?php

namespace App\Exceptions;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
   /* protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Determine if the request expects a JSON response
        if ($request->expectsJson()) {
            return $this->error('Unauthenticated', 401);
        }
        // For non-API routes, redirect to a login page or return a different type of response
        return parent::unauthenticated($request, $exception);
    }*/
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Throwable $exception)
    {

        if ($request->expectsJson()) {


            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                return $this->error('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
            }
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return $this->error($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY, $exception->errors());
            }

            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this->error('Resource not found', Response::HTTP_NOT_FOUND);
            }

            if ($exception instanceof ThrottleRequestsException) {
                $retryAfter = $exception->getHeaders()['Retry-After']; // Seconds until next attempt
                return $this->error(__('auth.throttle', [ 'seconds' =>$retryAfter ]), Response::HTTP_TOO_MANY_REQUESTS);
            }

            // Customize the response for specific exceptions
            if ($exception instanceof \App\Exceptions\CustomException) {
                return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }
            // You can add more specific exceptions here

            return $this->error($exception->getMessage(), $exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }
//    public function render($request, Throwable $exception)
//    {
//
//        Log::alert('E: ',[ 'success' => FALSE , 'data' => [ 'code' => $exception->getCode(), 'message' => $exception instanceof ValidationException ? $exception->errors() : $exception->getMessage() ,'r'=>$request->path() , 'ip'=>$request->ip()]]);
//        return $request->expectsJson() ? response()->json([ 'success' => FALSE , 'data' => [ 'code' => $exception->getCode(), 'message' => $exception instanceof ValidationException ? $exception->errors() : $exception->getMessage()  ] ], ($exception->getMessage() =='Unauthenticated.'?401:200)) : parent::render($request, $exception);
//    }
}

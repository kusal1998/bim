<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
  /*   public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    } */

    public function render($request, Exception $exception)
{

   // 404 page when a model is not found
    if ($exception instanceof ModelNotFoundException) {
        return response()->view('errors.404', [], 404);
    }

   return parent::render($request, $exception);
}


protected function convertExceptionToResponse(Exception $e)
{
	$e = FlattenException::create($e);
	return response()->view('errors.500', ['exception' => $e], $e->getStatusCode(), $e->getHeaders(), $e->getMessage());
}

}

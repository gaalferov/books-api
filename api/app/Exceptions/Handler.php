<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Utils\JsonResponseUtil;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($request->is('api/*')) {
            Log::error($e->getMessage(), [
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $errorsData = (method_exists($e, 'errors') || $e instanceof ErrorContextException)
                ? $e->errors()
                : ['message' => $e->getMessage(), 'code' => $this->getStatusCode($e)];

            if (config('app.debug')) {
                $errorsData['trace'] = $e->getTrace();
            }

            return JsonResponseUtil::errorResponse($errorsData, $this->getStatusCode($e));
        }

        return parent::render($request, $e);
    }

    private function getStatusCode(Throwable $e)
    {
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        if ($e instanceof HttpException) {
            return $e->getStatusCode();
        }

        if ($e->getCode() >= 400 && $e->getCode() < 600) {
            return $e->getCode();
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}

<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class MethodNotAllowedHttpExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::MethodNotAllowed;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof MethodNotAllowedHttpException;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'msg' => $exception->getMessage(),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        app('sentry')->captureException($exception);
    }
}

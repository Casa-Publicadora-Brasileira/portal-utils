<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class AccessDeniedHttpExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::Forbidden;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof AccessDeniedHttpException;
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

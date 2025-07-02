<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\OperationException;
use Throwable;

class OperationExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::BadRequest;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof OperationException;
    }

    public function build($exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'msg' => __($exception->getKey(), $exception->getArgs()),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        // app('sentry')->captureException($exception);
    }
}

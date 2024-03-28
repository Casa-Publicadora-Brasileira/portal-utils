<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class AuthorizationExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::Unauthorized;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof AuthorizationException;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'key' => 'errors.unauthorized',
            'msg' => __('cpb::errors.unauthorized'),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        // app('sentry')->captureException($exception);
    }
}

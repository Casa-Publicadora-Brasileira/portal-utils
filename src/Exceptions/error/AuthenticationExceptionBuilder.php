<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class AuthenticationExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::Unauthorized;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof AuthenticationException;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'key' => 'errors.not_authenticated',
            'msg' => __('cpb::errors.not_authenticated'),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        // app('sentry')->captureException($exception);
    }
}

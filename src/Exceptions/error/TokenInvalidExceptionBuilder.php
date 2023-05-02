<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Throwable;

class TokenInvalidExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::Unauthorized;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof TokenInvalidException;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'key' => 'errors.token_invalid',
            'msg' => __('cpb::errors.token_invalid'),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        app('sentry')->captureException($exception);
    }
}

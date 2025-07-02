<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Throwable;

class TokenExpiredExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::Unauthorized;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof TokenExpiredException;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'key' => 'errors.token_expired',
            'msg' => __('cpb::errors.token_expired'),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        // app('sentry')->captureException($exception);
    }
}

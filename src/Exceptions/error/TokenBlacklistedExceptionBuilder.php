<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Throwable;

class TokenBlacklistedExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::Unauthorized;
    }

    public function accept(Throwable $exception)
    {
        $exception instanceof TokenBlacklistedException;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'key' => 'errors.blacklisted_token',
            'msg' => __('cpb::errors.blacklisted_token'),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        app('sentry')->captureException($exception);
    }
}

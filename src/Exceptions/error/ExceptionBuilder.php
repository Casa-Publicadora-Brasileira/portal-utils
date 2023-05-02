<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Throwable;

class ExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::InternalError;
    }

    public function accept(Throwable $exception)
    {
        return true;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'key' => 'errors.internal_server_error',
            'msg' => __('cpb::errors.internal_server_error'),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        app('sentry')->captureException($exception);
    }
}

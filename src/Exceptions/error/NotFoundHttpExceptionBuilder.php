<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class NotFoundHttpExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::NotFound;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    public function build(Throwable $exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'key' => 'errors.not_found',
            'msg' => __('cpb::errors.not_found'),
        ];
    }

    public function registerSentry(Throwable $exception)
    {
        //
    }
}

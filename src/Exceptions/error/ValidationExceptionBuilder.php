<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions\error;

use CasaPublicadoraBrasileira\PortalUtils\Enums\HttpCodesEnum;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\ErrorResponseBuilder;
use Illuminate\Validation\ValidationException;
use Sentry\State\Scope;
use Throwable;

use function Sentry\configureScope;

class ValidationExceptionBuilder implements ErrorResponseBuilder
{
    public function statusCode(Throwable $exception)
    {
        return HttpCodesEnum::UnprocessableEntity;
    }

    public function accept(Throwable $exception)
    {
        return $exception instanceof ValidationException;
    }

    public function build($exception)
    {
        return [
            'code' => $this->statusCode($exception),
            'msg' => $exception->errors(),
        ];
    }

    public function registerSentry($exception)
    {
        configureScope(fn (Scope $scope) => $scope->setExtra('errors', $exception->errors()));
        app('sentry')->captureException($exception);
    }
}

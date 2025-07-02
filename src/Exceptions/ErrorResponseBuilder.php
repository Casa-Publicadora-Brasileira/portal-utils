<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions;

use Throwable;

interface ErrorResponseBuilder
{
    public function statusCode(Throwable $exception);
    public function accept(Throwable $exception);
    public function build(Throwable $exception);
    public function registerSentry(Throwable $exception);
}

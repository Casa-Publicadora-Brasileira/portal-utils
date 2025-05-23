<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Enums;

class HttpCodesEnum
{
    const Success = 200;
    const BadRequest = 400;
    const NotFound = 404;
    const InternalError = 500;
    const UnprocessableEntity = 422;
    const Unauthorized = 401;
    const Forbidden = 403;
    const MethodNotAllowed = 405;
    const Conflict = 409;
}

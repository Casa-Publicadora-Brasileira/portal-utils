<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Exceptions;

use Throwable;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\TokenExpiredExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\TokenInvalidExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\TokenBlacklistedExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\UnauthorizedHttpExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\MethodNotAllowedHttpExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\ValidationExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\NotFoundHttpExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\AccessDeniedHttpExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\AuthenticationExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\AuthorizationExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\ExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\HttpExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\JWTExceptionBuilder;
use CasaPublicadoraBrasileira\PortalUtils\Exceptions\error\OperationExceptionBuilder;
use Illuminate\Http\Request;
use Sentry\State\Scope;

use function Sentry\configureScope;

class ExceptionHandler
{
    private $builders = [];

    private function __construct()
    {
        $this->builders =  [
            new TokenBlacklistedExceptionBuilder(),
            new TokenExpiredExceptionBuilder(),
            new TokenInvalidExceptionBuilder(),
            new UnauthorizedHttpExceptionBuilder(),
            new MethodNotAllowedHttpExceptionBuilder(),
            new NotFoundHttpExceptionBuilder(),
            new AccessDeniedHttpExceptionBuilder(),
            new ValidationExceptionBuilder(),
            new OperationExceptionBuilder(),
            new JWTExceptionBuilder(),
            new HttpExceptionBuilder(),
            new AuthenticationExceptionBuilder(),
            new AuthorizationExceptionBuilder(),
        ];
    }

    public static function handler(Throwable $exception, Request $request)
    {
        return (new self())->translator($exception, $request);
    }

    private function translator(Throwable $exception, Request $request)
    {
        $error = collect($this->builders)
            ->first(fn (ErrorResponseBuilder $error) => $error->accept($exception), new ExceptionBuilder());

        if (!app()->isLocal() && app()->bound('sentry')) {
            configureScope(function (Scope $scope) use ($request) {
                if (auth()->check()) {
                    $scope->setUser([
                        'id' => auth()->user()->id,
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'uuid' => auth()->user()->uuid,
                    ]);
                }
                $scope->setExtra('token', $request->bearerToken());
                $scope->setExtra('query', $request->query->all());
                $scope->setExtra('body', $request->request->all());
                $scope->setExtra('request', $request->fullUrl());

                if (!empty($request->route())) {
                    $scope->setExtra('params', $request->route()->parameters());
                }
            });
            $error->registerSentry($exception);
        }

        return response()->json($error->build($exception), $error->statusCode($exception));
    }
}

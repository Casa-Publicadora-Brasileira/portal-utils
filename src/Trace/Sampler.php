<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Trace;

use Sentry\Tracing\SamplingContext;
use Sentry\State\Scope;

use function Sentry\configureScope;

class Sampler
{
    public static function tracesSampler(SamplingContext $context): float
    {
        self::setScope();
        if (app()->environment('production')) {
            return app()->runningInConsole() ? 0.05 : 0.2;
        }
        return 1.0;
    }

    private static function setScope()
    {
        configureScope(function (Scope $scope) {
            if (auth()->check()) {
                $scope->setUser([
                    'id' => auth()->user()->id,
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'uuid' => auth()->user()->uuid,
                ]);
            }
            if (app()->runningInConsole()) {
                $scope->setTag('event_type', 'queue');
            } else {
                $scope->setTag('event_type', 'http_request');
            }
        });
    }
}

<?php

namespace CasaPublicadoraBrasileira\PortalUtils;

use CasaPublicadoraBrasileira\PortalUtils\Auth\CachedUserProvider;
use CasaPublicadoraBrasileira\PortalUtils\Commands\MakeDomain;
use CasaPublicadoraBrasileira\PortalUtils\Commands\MakePayload;
use CasaPublicadoraBrasileira\PortalUtils\Commands\MakeQueryDomain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class PortalUtilsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'cpb');

        if (config('app.debug')) {
            DB::listen(fn ($query) => logger($query->sql, $query->bindings));
        }

        Auth::provider('cached', fn ($app, array $config) => new CachedUserProvider($app['hash'], $config['model']));

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeDomain::class,
                MakePayload::class,
                MakeQueryDomain::class,
            ]);
        }
    }
}

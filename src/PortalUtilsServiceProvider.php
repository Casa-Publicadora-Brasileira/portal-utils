<?php

namespace CasaPublicadoraBrasileira\PortalUtils;

use Illuminate\Support\ServiceProvider;

class PortalUtilsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'cpb');

    }
}

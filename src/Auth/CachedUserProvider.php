<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Cache;

class CachedUserProvider extends EloquentUserProvider
{
    public function retrieveById($identifier)
    {
        $stage = config('app.env') . ':';
        $prefix = config('cache.prefix', $stage);

        return Cache::store('login')
            ->remember("{$prefix}:user:$identifier", now()->addHour(4), fn () => parent::retrieveById($identifier));
    }
}

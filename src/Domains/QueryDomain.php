<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Domains;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class QueryDomain
{
    protected static string $model;

    public static function find(int|string $id): ?Model
    {
        return static::$model::find($id);
    }

    public static function all(array $columns = ['*']): Collection
    {
        return static::$model::all($columns);
    }

    public static function withRelations(array $relations): Builder
    {
        return static::$model::with($relations);
    }
}

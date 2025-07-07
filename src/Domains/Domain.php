<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Domains;

use Illuminate\Database\Eloquent\Model;

abstract class Domain extends QueryDomain
{
    public static function create(array $data): Model
    {
        return static::$model::create($data);
    }

    public static function update(int|string $id, array $data): ?Model
    {
        $model = static::find($id);

        if ($model) {
            $model->update($data);
        }

        return $model;
    }

    public static function delete(int|string $id): bool
    {
        $model = static::find($id);

        if ($model) {
            return $model->delete();
        }

        return false;
    }
}

<?php

namespace Infrastructure\Database\Traits;

use Illuminate\Support\Str;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = strtoupper(Str::uuid());
            }
        });
    }
}

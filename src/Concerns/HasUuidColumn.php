<?php

namespace Winata\Core\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static creating(\Closure $param)
 */
trait HasUuidColumn
{
    public static function bootHasUuidColumn(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::orderedUuid();
            }
        });
    }
}

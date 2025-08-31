<?php

namespace App\Traits;

trait HasSequence
{
    protected static function bootHasSequence()
    {
        static::creating(function ($model) {
            $model->sequence = self::max('sequence') + 1;
        });
    }
}
<?php

namespace Modules\Tracker\Actions;

use Exception;

trait AsObject
{
    public static function make(){
        return app(static::class);
    }

    /**
     * @throws Exception
     */
    public static function run(...$arguments)
    {
        return static::make()->handle(...$arguments);
    }
}

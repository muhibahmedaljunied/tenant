<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;

class InventoryAction extends Model
{



    protected $guarded=['id'];

    protected static function newFactory()
    {
        return \Modules\Inventory\Database\factories\InventoryActionFactory::new();
    }
}

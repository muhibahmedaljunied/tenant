<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryTransactions extends Model
{

    const STATUS_ON = 'on';
    const STATUS_OFF = 'off';
    protected $guarded = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\Inventory\Database\factories\InventoryTransactionsFactory::new();
    }
}

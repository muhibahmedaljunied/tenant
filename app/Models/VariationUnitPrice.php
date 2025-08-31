<?php

namespace App\Models;

use App\Unit;
use App\Product;
use App\Variation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariationUnitPrice extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id');
    }
}

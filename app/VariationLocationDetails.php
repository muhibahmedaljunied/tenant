<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationLocationDetails extends Model
{



    protected $table = 'variation_location_details';

    protected $fillable = [
        'product_id',
        'product_variation_id',
        'variation_id',
        'location_id',
        'store_id',
        'qty_available',
    ];

    // Relationships

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationLocationDetails extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function variation(): BelongsTo
    {
        return $this->belongsTo(Variation::class, 'variation_id', 'id');
    }
}

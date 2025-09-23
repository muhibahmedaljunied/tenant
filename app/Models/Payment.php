<?php

namespace App\Models;

use App\Contact;
use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'reference_number',
        'contact_id',
        'account_id',
        'description',
        'type',
        'amount',
        'date',
        'contact_type',
        
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function account()
    {
        return $this->belongsTo(AcMaster::class, 'account_id');
    }
}

<?php

namespace Modules\Tracker\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'dm_sectors';

    protected $fillable = ['name', 'phone', 'description', 'business_id', 'province_id', 'user_id'];


    public function province(): BelongsTo
    {
       return $this->belongsTo(Province::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}

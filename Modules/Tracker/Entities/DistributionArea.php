<?php

namespace Modules\Tracker\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DistributionArea extends Model
{
    use HasFactory;

    protected $table = 'dm_distribution_areas';

    protected $fillable = ['name', 'phone', 'description', 'sector_id', 'user_id', 'business_id', 'points'];

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

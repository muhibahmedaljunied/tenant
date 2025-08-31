<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZatcaResponse extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $fillable = ['response','status'];
    public function responser(){
        return $this->morphTo();  
    }
}

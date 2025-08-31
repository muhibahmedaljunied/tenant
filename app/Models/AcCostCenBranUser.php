<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcCostCenBranUser extends Model
{
    use SoftDeletes;
    protected $table = 'ac_cost_cen_bran_users';
    protected $fillable = ['user_id','ac_cost_branches_id','business_id'];

    public function ac_cost_cen_branches(){
        return $this->belongsTo(AcCostCenBranche::class , 'ac_cost_branches_id');
    }

}

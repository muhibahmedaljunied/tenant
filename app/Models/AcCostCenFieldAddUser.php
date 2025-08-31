<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcCostCenFieldAddUser extends Model
{
    use SoftDeletes;
    protected $table = 'ac_cost_cen_fil_add_users';
    protected $fillable = ['user_id','ac_cost_field_id'];

}

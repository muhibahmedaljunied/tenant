<?php

namespace App\Models;

use App\Traits\{BelongsToBusiness, HasSequence};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class AcCostCenFieldAdd extends Model
{
    use SoftDeletes, BelongsToBusiness, HasSequence;
    protected $table = 'ac_cost_cen_field_add';
    protected $fillable = ['cost_code', 'cost_description', 'parent_cost_no', 'cost_level', 'business_id'];
    public $cost_centers = [];

    public function ac_cost_cen_field_adds()
    {
        return $this->belongsToMany(User::class, 'ac_cost_cen_fil_add_users', 'ac_cost_field_id', 'user_id');
    }

    public function details()
    {
        return $this->belongsTo(AcCostCenFieldAdd::class, 'parent_cost_no', 'id');
    }

    public function parents()
    {
        return $this->hasMany(AcCostCenFieldAdd::class, 'parent_cost_no', 'id');
    }
    public function getParents($cost_center)
    {
        $this->cost_centers[] = $cost_center;
        $cost_centers_top = AcCostCenFieldAdd::where('parent_cost_no', $cost_center)->get();
        if ($cost_centers_top) {
            foreach ($cost_centers_top as $acc) {
                $this->cost_centers[] = $acc->id;
                $this->getchiledtree($acc->parents);
            }
        } else {
            // $this->accounts = [];
        }
        return $this->cost_centers;
    }

    public function getchiledtree($parents)
    {
        if (count($parents)) {
            foreach ($parents as $par) {
                $this->cost_centers[] = $par->id;
                if (count($par->parents)) {
                    $this->getchiledtree($par->parents);
                }
            }
        }
        return $this->cost_centers;
    }

    public static function getSelfParents($id)
    {
        return self::where('id', $id)->first()->getParents($id);
    }

    public static function forDropdown($business_id)
    {
        return self::pluck('cost_description', 'id');
    }
}

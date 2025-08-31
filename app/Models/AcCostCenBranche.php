<?php

namespace App\Models;

use App\InvoiceScheme;
use App\Traits\BelongsToBusiness;
use App\Traits\HasSequence;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcCostCenBranche extends Model
{
    use SoftDeletes, BelongsToBusiness, HasSequence;
    protected $table = 'ac_cost_cen_branches';
    protected $fillable = ['cost_code', 'cost_description', 'parent_cost_no', 'cost_level', 'business_id'];
    public $cost_centers = [];

    public function ac_cost_cen_branches()
    {
        return $this->belongsToMany(User::class, 'ac_cost_cen_bran_users', 'ac_cost_branches_id', 'user_id');
    }

    public function details()
    {
        return $this->belongsTo(AcCostCenBranche::class, 'parent_cost_no', 'id');
    }

    public function parents()
    {
        return $this->hasMany(AcCostCenBranche::class, 'parent_cost_no', 'id');
    }
    public function getParents($cost_center)
    {
        $this->cost_centers[] = $cost_center;
        $cost_centers_top = self::where('parent_cost_no', $cost_center)->get();
        if ($cost_centers_top) {
            foreach ($cost_centers_top as $acc) {
                $this->cost_centers[] = $acc->id;
                $this->getchiledtree($acc->parents);
            }
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
    // public function ac_cost_cen_branches(){
    //     return $this->hasMany(AcCostCenBranUser::class , 'ac_cost_branches_id');
    // }

    public static function getSelfParents($id)
    {
        /** @var self $cost_center_details */
        return self::where('id', $id)->userHaveAccess()->first()->getParents($id);
    }

    public static function forDropdown($business_id)
    {
        return self::userHaveAccess()->where('business_id', $business_id)->pluck('cost_description', 'id');
    }
    public function scopeUserHaveAccess($query)
    {
        return $query->whereHas('ac_cost_cen_branches', function ($q) {
            $user = auth()->user();
            if (!$user->hasRole("Admin#{$user->business_id}")) {
                $q->where('user_id', $user->id);
            }
        });
    }
}

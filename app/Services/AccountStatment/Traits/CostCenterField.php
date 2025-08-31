<?php
namespace App\Services\AccountStatment\Traits;

use stdClass;
use App\Models\AcCostCenFieldAdd;

trait CostCenterField{
    private static $acCostCenFields;
    private $cost_centers;

    public function getCostCenFieldAdd()
    {
        if (!self::$acCostCenFields) {
            self::$acCostCenFields = AcCostCenFieldAdd::select('cost_description','cost_level','cost_code','parent_cost_no','sequence','id')->get();
        }
        return self::$acCostCenFields;
    }
    public function costCenterFieldFormatParents()
    {
        $accounts = $this->getCostCenFieldAdd();

        foreach ($accounts as $costCenter) {
            if (!property_exists($costCenter, 'subParents')) {
                $costCenter->subParents = $this->getCostCenFieldAdd()->where('parent_cost_no', $costCenter->id)->values();
            }
        }

        return $accounts;
    }
    public function getSelfCostCenterFieldsParents($branch_cost_center)
    {
        $costCenterBranch = $this->getCostCenFieldAdd()->where('id',$branch_cost_center)->first();
        return $this->getCostFieldParents($costCenterBranch->id);
    }
    public function costCenterParents($id)
    {
        if (is_array($id)) {
            $accounts = $this->getCostCenFieldAdd()->whereIn('id', $id)->values();
            foreach ($accounts as $costCenter) {
                if (!property_exists($costCenter, 'subParents')) {
                    $costCenter->subParents = $this->getCostCenFieldAdd()->whereIn('parent_cost_no', $costCenter->id)->values();
                }
            }

            return $accounts;
        } else {
            $account = $this->getCostCenFieldAdd()->where('id', $id)->first();
            if ($account) {
                if (!property_exists($account, 'subParents')) {
                    $account->subParents = $this->getCostCenFieldAdd()->where('parent_cost_no', $account->id)->values();
                }
                return $account->subParents;
            }
        }
        return [];
    }
    public function getCostFieldParents($cost_center)
    {
        $costCenterId = $cost_center instanceof stdClass ? $cost_center->id : $cost_center;
        $this->cost_centers[] = $costCenterId;
        $this->costCenterFieldFormatParents();

        $cost_centers_top = $this->costCenterParents($costCenterId);
        
        if (!empty($cost_centers_top)) {
            $ids = $cost_centers_top->pluck('id')->toArray();
            array_merge($this->cost_centers, $ids);

            $this->getCostFieldsChildrenTree($this->costCenterParents($cost_centers_top));
        }
        return $this->cost_centers;
    }
    public function getCostFieldsChildrenTree($parents)
    {
        if (count($parents) > 0) {
            foreach ($parents as $par) {
                $this->cost_centers[] = $par->id;
                $subParents = $this->costCenterParents($par->id);

                if (!empty($subParents)) {
                    $this->getCostFieldsChildrenTree($subParents);
                }
            }
        }
        return $this->cost_centers;
    }

}
<?php
namespace App\Services\AccountStatment\Traits;

use App\Models\AcCostCenBranche;
use Illuminate\Support\Facades\DB;
use stdClass;

trait CostCenter
{
    private static $acCostCenBranches;
    private $branch_cost_centers;

    public function getCostBranches()
    {
        if (!self::$acCostCenBranches) {
            $query = AcCostCenBranche::userHaveAccess()->select('id', 'parent_cost_no', 'cost_level', 'cost_description');
            self::$acCostCenBranches = collect(DB::select($query->toSql(), $query->getBindings()));
        }
        return self::$acCostCenBranches;
    }
    public function getSelfCostCenterBranchParents($branch_cost_center)
    {
        $costCenterBranch = $this->getCostBranches()->where('id', $branch_cost_center)->first();
        return $this->getCostBrnachesParents($costCenterBranch);
    }
    public function branchFormatParents()
    {
        $accounts = $this->getCostBranches();

        foreach ($accounts as $costCenter) {
            if (!property_exists($costCenter, 'subParents')) {
                $costCenter->subParents = $this->getCostBranches()->where('parent_cost_no', $costCenter->id)->values();
            }
        }

        return $accounts;
    }
    public function branchParents($id)
    {
        if (is_array($id)) {
            $accounts = $this->getCostBranches()->whereIn('id', $id)->values();
            foreach ($accounts as $costCenter) {
                if (!property_exists($costCenter, 'subParents')) {
                    $costCenter->subParents = $this->getCostBranches()->whereIn('parent_cost_no', $costCenter->id)->values();
                }
            }

            return $accounts;
        } else {
            $account = $this->getCostBranches()->where('id', $id)->first();
            if ($account) {
                if (!property_exists($account, 'subParents')) {
                    $account->subParents = $this->getCostBranches()->where('parent_cost_no', $account->id)->values();
                }
                return $account->subParents;
            }
        }
        return [];
    }
    public function getCostBrnachesParents($cost_center)
    {
        $costCenterId = $cost_center instanceof stdClass ? $cost_center->id : $cost_center;
        $this->branch_cost_centers[] = $costCenterId;
        $this->branchFormatParents();

        $cost_centers_top = $this->branchParents($costCenterId);

        if (!empty($cost_centers_top)) {
            $ids = $cost_centers_top->pluck('id')->toArray();
            array_merge($this->branch_cost_centers, $ids);
            $this->getCostBranchesChildrenTree($this->branchParents($cost_centers_top));
        }
        return $this->branch_cost_centers;
    }
    public function getCostBranchesChildrenTree($costParents)
    {
        if (count($costParents) > 0 ) {
            foreach ($costParents as $parent) {
                $this->branch_cost_centers[] = $parent->id;
                $subParents = $this->branchParents($parent->id);

                if ($subParents->isNotEmpty()) {
                    $this->getCostBranchesChildrenTree($subParents);
                }
            }
        }

        return $this->branch_cost_centers;
    }

}
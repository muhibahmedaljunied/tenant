<?php

namespace Modules\Tracker\Tables;

use Modules\Tracker\Entities\DistributionArea;
use Modules\Tracker\Entities\Sector;

class DistributionAreaTable extends Table
{
    protected function newQuery()
    {
        $business_id = session()->get('user.business_id');
        return DistributionArea::with('sector', 'sector.province')->where('business_id', $business_id)->get();
    }

    protected function getColumns(): array
    {
        $columns = [
            'name',
            'sector.province.name',
            'sector.name',
            'description',
            'delegate',
        ];
        if(auth()->user()->canAny(['tracker.edit_distribution_areas', 'tracker.delete_distribution_areas'])){
            $columns[] = 'action';
        }
        return $columns;
    }

    public function getDelegateContent($row){
        return $row->user ? $row->user->getUserFullNameAttribute() : "";
    }

    public function getActionContent($row){
        $output = '';
        if(auth()->user()->can('tracker.edit_distribution_areas')){
            $output .= '<button onclick="editArea(' . $row->id . ')"  class="btn btn-xs btn-primary btn-modal"><i class="glyphicon glyphicon-edit"></i> تعديل</button>';
        }
        if(auth()->user()->can('tracker.delete_distribution_areas')){
            $output .= '<button onclick="deleteArea(' . $row->id . ')" class="btn btn-xs btn-danger "><i class="glyphicon glyphicon-trash"></i> حذف</button>';
        }
        return $output;
    }
}

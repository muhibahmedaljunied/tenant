<?php

namespace Modules\Tracker\Tables;

use Modules\Tracker\Entities\Sector;

class SectorTable extends Table
{
    protected function newQuery()
    {
        $business_id = session()->get('user.business_id');
        return Sector::with('province', 'user')->where('business_id', $business_id)->get();
    }

    protected function getColumns(): array
    {
        $columns = [
            'name',
            'province.name',
            'description',
            'delegate',
        ];
        if(auth()->user()->canAny(['tracker.edit_sectors', 'tracker.delete_sectors'])){
            $columns[] = 'action';
        }
        return $columns;
    }

    public function getDelegateContent($row){
        return $row->user ? $row->user->getUserFullNameAttribute() : "";
    }

    public function getActionContent($row){
        $output = "";
        if(auth()->user()->can('tracker.edit_sectors')){
            $output .= '<button onclick="editSector(' . $row->id . ')"  class="btn btn-xs btn-primary btn-modal"><i class="glyphicon glyphicon-edit"></i> تعديل</button>';
        }
        if(auth()->user()->can('tracker.delete_sectors')){
            $output .= '<button onclick="deleteSector(' . $row->id . ')" class="btn btn-xs btn-danger "><i class="glyphicon glyphicon-trash"></i> حذف</button>';
        }
        return $output;
    }
}

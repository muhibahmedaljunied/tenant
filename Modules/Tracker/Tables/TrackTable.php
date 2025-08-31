<?php

namespace Modules\Tracker\Tables;

use Modules\Tracker\Entities\Sector;
use Modules\Tracker\Entities\Track;

class TrackTable extends Table
{
    protected function newQuery()
    {
        $business_id = session()->get('user.business_id');
        $query = Track::with('province', 'sector', 'distributionArea')->where('business_id', $business_id);
        if(request()->has('distribution_area_id')){
            $query->where('distribution_area_id', request()->input('distribution_area_id'));
        }
        if(request()->has('user_id')){
            $query->where('user_id', request()->input('user_id'));
        }
        if(request()->has('contact_id')){
            $query->whereHas('contacts', function($q){
                $q->whereIn('id', request()->input('contact_id'));
            });
        }
        if(request()->filled('query')){
            $query->where(function($q){
                $search = request()->input('query');
                $q->where('name', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
            });
        }
        return $query->get();
    }

    protected function getColumns(): array
    {
        $columns = [
            'name',
            'distribution_area.name',
            'sector.name',
            'province.name',
            'description',
            'delegate',
        ];
        if(auth()->user()->canAny(['tracker.edit_tracks', 'tracker.delete_tracks'])){
            $columns[] = 'action';
        }
        return $columns;
    }

    public function getDelegateContent($row){
        return $row->user ? $row->user->getUserFullNameAttribute() : "";
    }

    public function getActionContent($row){
        $output = "";

        $output .= '<a href="' . route("dm.tracks.show", $row->id) . '"  class="btn btn-xs btn-primary margin-left-10"><i class="glyphicon glyphicon-eye-open"></i> عرض</a>';
        if(auth()->user()->can('tracker.edit_tracks')){
            $output .= '<button onclick="editTrack(' . $row->id . ')"  class="btn btn-xs btn-primary btn-modal margin-left-10"><i class="glyphicon glyphicon-edit"></i> تعديل</button>';
        }
        if(auth()->user()->can('tracker.delete_tracks')){
            $output .= '<button onclick="deleteTrack(' . $row->id . ')" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> حذف</button>';
        }
        return $output;
    }
}

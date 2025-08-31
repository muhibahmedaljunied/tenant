<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class StoreDetail extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];




    /**
     * Return list of Stores for a business
     *
     * @param int $business_id
     * @param boolean $show_all = false
     * @param array $receipt_printer_type_attribute =
     *
     * @return mixed
     */
    public static function forDropdown(
        $business_id,

    ) {


        $query = StoreDetail::where('store_details.business_id', $business_id)
            ->join(
                'stores',
                'store_details.store_id',
                '=',
                'stores.id'
            )
            ->select();
        $result = $query->get();
        $stores = $result->pluck('name', 'id');
        return $stores;
    }
}

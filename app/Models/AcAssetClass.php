<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcAssetClass extends Model
{
    use SoftDeletes;
    protected $table = 'ac_asset_classes';
    protected $fillable = ['asset_class_name_ar','asset_class_name_en','is_depreciable','useful_life_type','useful_life','asset_account','asset_expense_account','accumulated_consumption_account','business_id'];

    public function ac_asset_classes(){
        return $this->hasMany(AcAsset::class , 'asset_classes_id');
    }
    
    public function asset_account_details(){
        return $this->belongsTo(AcMaster::class, 'asset_account','account_number');
    }
    public function asset_expense_account_details(){
        return $this->belongsTo(AcMaster::class, 'asset_expense_account','account_number');
    }
    public function accumulated_consumption_account_details(){
        return $this->belongsTo(AcMaster::class, 'accumulated_consumption_account','account_number');
    }
}

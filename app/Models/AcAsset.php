<?php

namespace App\Models;

use App\Custom\EmptyRelation;
use App\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcAsset extends Model
{
    use SoftDeletes;
    protected $table = 'ac_assets';
    protected $fillable = ['asset_name_ar','asset_name_en','asset_classes_id','asset_description','product_id','asset_account','asset_expense_account','accumulated_consumption_account','asset_value','scrap_value','current_value','business_id'];

    public function ac_asset_class(){
        return $this->belongsTo(AcAssetClass::class , 'asset_classes_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function purchase_lines(){
        if($this->product){
            return $this->product->purchase_lines;
        }
        return null;
    }

    public function transaction(){
        $purchaseLines = $this->purchase_lines();
        if($purchaseLines !== null && $purchaseLines->first()){
            return $purchaseLines->first()->transaction;
        }
        return null;
    }

    public function hasTransaction(){
        return (bool)$this->transaction();
    }


}

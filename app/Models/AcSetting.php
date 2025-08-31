<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcSetting extends Model
{
    use SoftDeletes, BelongsToBusiness;
    protected $table = 'ac_settings';
    // public $accounts = [];
    // public $found_firest = false;
    // public $found_last = false;
    protected $guarded = [];



    public function current_period_profit_loss_details()
    {
        return $this->belongsTo(AcMaster::class, 'current_period_profit_loss', 'account_number');
    }
    public function stage_period_profit_loss_details()
    {
        return $this->belongsTo(AcMaster::class, 'stage_period_profit_loss', 'account_number');
    }
    public function customers_details()
    {
        return $this->belongsTo(AcMaster::class, 'customers', 'account_number');
    }
    public function sold_goods_cost_details()
    {
        return $this->belongsTo(AcMaster::class, 'sold_goods_cost', 'account_number');
    }
    public function inventory_details()
    {
        return $this->belongsTo(AcMaster::class, 'inventory', 'account_number');
    }
    public function sales_service_revenue_details()
    {
        return $this->belongsTo(AcMaster::class, 'sales_service_revenue', 'account_number');
    }
    public function vat_due_details()
    {
        return $this->belongsTo(AcMaster::class, 'vat_due', 'account_number');
    }
    public function cash_equivalents_details()
    {
        return $this->belongsTo(AcMaster::class, 'cash_equivalents', 'account_number');
    }
    public function suppliers_details()
    {
        return $this->belongsTo(AcMaster::class, 'suppliers', 'account_number');
    }
    public function operating_income_details()
    {
        return $this->belongsTo(AcMaster::class, 'operating_income', 'account_number');
    }
    public function direct_expenses_details()
    {
        return $this->belongsTo(AcMaster::class, 'direct_expenses', 'account_number');
    }
    public function non_operating_income_details()
    {
        return $this->belongsTo(AcMaster::class, 'non_operating_income', 'account_number');
    }
    public function operating_expenses_details()
    {
        return $this->belongsTo(AcMaster::class, 'operating_expenses', 'account_number');
    }
    public function assets_details()
    {
        return $this->belongsTo(AcMaster::class, 'assets', 'account_number');
    }
    public function liabilities_details()
    {
        return $this->belongsTo(AcMaster::class, 'liabilities', 'account_number');
    }
    public function equity_details()
    {
        return $this->belongsTo(AcMaster::class, 'equity', 'account_number');
    }
}

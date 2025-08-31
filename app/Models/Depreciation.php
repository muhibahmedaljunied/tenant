<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $from
 * @property int $from_year
 * @property int $to
 * @property int $to_year
 *
 */
class Depreciation extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'reference_number',
        'asset_class_id',
        'asset_id',
        'depreciation_period',
        'from_year',
        'from',
        'to_year',
        'to'
    ];

    public function asset()
    {
        return $this->belongsTo(AcAsset::class);
    }

    public function assetClass()
    {
        return $this->belongsTo(AcAssetClass::class);
    }


    public function getDepreciationFromAttribute()
    {
        switch ($this->depreciation_period) {
            case 'weekly':
                $date = CarbonImmutable::create($this->from_year)->week($this->from - 1)->addDays(7);
                break;
            case 'monthly':
                $date = CarbonImmutable::create($this->from_year)->month($this->from)->endOfMonth();
                break;
            case 'yearly':
                $date = CarbonImmutable::create($this->from_year)->lastOfYear();
                break;
        }
        return $date;
    }

    public function getDepreciationToAttribute()
    {
        switch ($this->depreciation_period) {
            case 'weekly':
                $date = CarbonImmutable::create($this->to_year)->week($this->to - 1)->addDays(7);
                break;
            case 'monthly':
                $date = CarbonImmutable::create($this->to_year)->month($this->to)->endOfMonth();
                break;
            case 'yearly':
                $date = CarbonImmutable::create($this->to_year)->lastOfYear();
                break;
        }
        return $date;
    }
}

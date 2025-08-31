<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\BelongsToBusiness;
use App\Traits\HasSequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcJournalEntry extends Model
{
    use SoftDeletes, BelongsToBusiness, HasSequence;
    protected $table = 'ac_journal_entries';
    protected  $guarded = [];
    public static function debtAges()
    {
        return [
            '0_30' => trans('debtages.0_30'),
            '31_60' => trans('debtages.31_60'),
            '61_90' => trans('debtages.61_90'),
            '91_120' => trans('debtages.91_120'),
            '121_150' => trans('debtages.121_150'),
            '151_180' => trans('debtages.151_180'),
            '181_210' => trans('debtages.181_210'),
            '211_before' => trans('debtages.211_before')
        ];
    }
    public function ac_journal_entries()
    {
        return $this->hasMany(AcJournalEntryDetail::class, 'ac_journal_entries_id');
    }
    public function journal_entry_debt_ages()
    {
        return $this->hasMany(AcJournalEntryDebtAges::class, 'ac_journal_entries_id');
    }
    public function opening_account_details()
    {
        return $this->belongsTo(AcMaster::class, 'opening_account', 'account_number')->where('business_id', $this->business_id);
    }
    public function cost_cen_branche_details()
    {
        return $this->belongsTo(AcCostCenBranche::class, 'cost_cen_branche_id', 'id');
    }
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->journal_entry_debt_ages()->delete();
            $model->ac_journal_entries()->delete();
        });
    }
}

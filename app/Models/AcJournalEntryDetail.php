<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcJournalEntryDetail extends Model
{
    use SoftDeletes, BelongsToBusiness;
    protected $table = 'ac_journal_entry_details';
    protected  $guarded = [];
    // protected $fillable = [
    //     'account_number',
    //     'ac_journal_entries_id',
    //     'entry_desc',
    //     'cost_cen_field_id',
    //     'product_id',
    //     'location_id',
    //     'amount',
    //     'deleted_at'
    // ];
    protected $casts = [
        'amount' => 'double'
    ];
    public function ac_journal_entry()
    {
        return $this->belongsTo(AcJournalEntry::class, 'ac_journal_entries_id');
    }

    public function account_type()
    {
        return $this->belongsTo(AcMaster::class, 'account_number', 'account_number');
    }

    public function cost_cen_field()
    {
        return $this->belongsTo(AcCostCenFieldAdd::class, 'cost_cen_field_id', 'id');
    }

    public function getAccountType()
    {
        return $this->belongsTo(AcMaster::class, 'account_number', 'account_number');
    }

    public function scopeForJournalEntry(Builder $query, $date, $cost_centers)
    {
        $query->whereHas('ac_journal_entry', function ($data) use ($date, $cost_centers) {
            $q = is_array($date) ? $data->whereBetween('entry_date', $date) : $data->whereDate('entry_date', $date);
            if (count($cost_centers)) {
                $q->whereIn('cost_cen_branche_id', $cost_centers);
            }
        });
    }
    public function scopeForDebtJournalEntry(Builder $query, $date, $cost_centers)
    {
        $query->addSelect([
            'entry_date' => AcJournalEntry::select(DB::raw('IF(debtages_date IS NOT NULL, debtages_date, entry_date)'))->whereColumn('id', 'ac_journal_entry_details.ac_journal_entries_id'),
        ])->whereHas('ac_journal_entry', function ($data) use ($date, $cost_centers) {
            if (is_array($date)) {
                $q = $data->whereBetween(DB::raw('IF(debtages_date IS NOT NULL, debtages_date, entry_date) '), $date);
            } else {
                $q = $data->whereDate('entry_date', $date);
            }
            if (count($cost_centers)) {
                $q->whereIn('cost_cen_branche_id', $cost_centers);
            }
        });
    }

    public function scopeForAccountType(Builder $query, $cost_centers, $account_type = null)
    {
        $query->whereHas('account_type', function ($acMaster) use ($cost_centers, $account_type) {
            if ($account_type) {
                if (is_array($account_type)) {
                    $acMaster->whereIn('account_type', $account_type);
                } else {
                    $acMaster->where('account_type', $account_type);
                }
            } else {
                $acMaster->where('id', '>', 0);
            }
            if (count($cost_centers)) {
                $acMaster->whereIn('cost_cen_field_id', $cost_centers);
            }
        });
    }

    public function scopeForEntryDate(Builder $query, $previous_date, $cost_centers)
    {
        $query->whereHas('ac_journal_entry', function ($date) use ($previous_date, $cost_centers) {
            if (count($cost_centers)) {
                $date->whereDate('entry_date', '<=', $previous_date)->whereIn('cost_cen_branche_id', $cost_centers);
            } else {
                $date->whereDate('entry_date', '<=', $previous_date);
            }
        });
    }

}

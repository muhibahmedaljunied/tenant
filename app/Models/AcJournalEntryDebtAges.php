<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcJournalEntryDebtAges extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function journal_entry()
    {
        return $this->belongsTo(AcJournalEntry::class, 'ac_journal_entries_id');
    }

}

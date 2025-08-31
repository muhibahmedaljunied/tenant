<?php

namespace App\Exports\AcEntry;

use App\Models\AcMaster;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AcEntryExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        // $masters = AcMaster::get([
        //     'id',
        //     'parent_acct_no',
        //     'account_number',
        //     'account_level',
        //     'account_type',
        //     'account_name_ar',
        //     'account_name_en'
        // ]);

        return view('ac_master.exports.index', compact('masters'));
    }
}

<?php

namespace App\View\Components\Report;

use Illuminate\View\Component;

class DebtStatementReportComponent extends Component
{
    public $combinedData;
    public $totalDebtor;
    public $totalCreditor;
    public $dateRanges;
    public function __construct($combinedData,$totalDebtor,$totalCreditor,$dateRanges)
    {
        $this->combinedData     = $combinedData ;
        $this->totalDebtor      = $totalDebtor ;
        $this->totalCreditor    = $totalCreditor ;
        $this->dateRanges       = $dateRanges ;
    }

    public function render()
    {
        return view('components.report.debt-statement-report-component');
    }
}

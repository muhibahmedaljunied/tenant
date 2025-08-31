<?php

namespace App\Datatables;


use App\Models\Payment;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;use Yajra\DataTables\Html\Column;use Yajra\DataTables\Services\DataTable;
class PaymentsDataTable extends DataTable {
    public function dataTable($query) {
        return (new EloquentDataTable($query))->setRowId('id');
    }

    public function query(Payment $model): Builder{
        return $model->newQuery();
    }

    public function html(): HtmlBuilder {
        return $this->builder()
            ->setTableId('payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }

    public function getColumns(): array {
        return [
            Column::make('reference_number')
        ];
    }

}

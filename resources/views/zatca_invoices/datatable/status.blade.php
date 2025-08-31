@if ($status == 'error')
    <span class="badge bg-red">
        {{ trans("zatca.invoices.statuses.{$status}") }}
    </span>
@elseif($status == 'warning')
    <span class="badge bg-yellow">
        {{ trans("zatca.invoices.statuses.{$status}") }}
    </span>
@else
    <span class="badge bg-green-active">
        {{ trans("zatca.invoices.statuses.{$status}") }}
    </span>
@endif

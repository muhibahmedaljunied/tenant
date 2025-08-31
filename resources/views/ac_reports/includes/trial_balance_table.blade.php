@foreach ($accountStatementService->parents($parents->account_number) as $parent)
    @php
        $array_of_acc = null;
        $array_of_acc = $accountStatementService->getParents($parent->account_number);
    @endphp
    @if (count($array_of_acc))
        <tr>
            <td data-title="Account" colspan="3">
                <a>
                    <tab{{ $parent->account_level }}>
                        {{ $parent->account_number }} - {{ $parent->account_name_ar }}
                        </tab{{ $parent->account_level }}>
                </a>
            </td>

            @php

                $debtor_main = 0;
                $creditor_main = 0;
                $debtor_open = 0;
                $creditor_open = 0;
                $net_open = 0;
                $net_main = 0;
                $net_close = 0;
                $main_acc_type = '';
                $net_debtor_close = 0;
                $net_creditor_close = 0;
                $net_acc_type = '';
                $open_acc_type = '';

                $positive_main = $closedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                // dd(
                //     $positive_main
                // );
                $negative_main = $closedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');
                //for before start period search (open)
                $positive_open = $openedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative_open = $openedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');

                //debtor
                if ($account->account_type == 'debtor') {
                    $debtor_main = $positive_main;
                    $creditor_main = $negative_main * -1;

                    if ($debtor_main >= $creditor_main) {
                        $net_main = $debtor_main - $creditor_main;
                        $main_acc_type = 'debtor';
                    } else {
                        $net_main = $creditor_main - $debtor_main;
                        $main_acc_type = 'creditor';
                    }

                    $debtor_open = $positive_open;
                    $creditor_open = $negative_open * -1;
                    if ($debtor_open >= $creditor_open) {
                        $net_open = $debtor_open - $creditor_open;
                        $open_acc_type = 'debtor';
                    } else {
                        $net_open = $creditor_open - $debtor_open;
                        $open_acc_type = 'creditor';
                    }

                    $net_debtor_close = $debtor_open + $debtor_main;
                    $net_creditor_close = $creditor_open + $creditor_main;
                    if ($net_debtor_close >= $net_creditor_close) {
                        $net_close = $net_debtor_close - $net_creditor_close;
                        $net_acc_type = 'debtor';
                    } else {
                        $net_close = $net_creditor_close - $net_debtor_close;
                        $net_acc_type = 'creditor';
                    }
                } else {
                    //creditor
                    $creditor_main = $positive_main;
                    $debtor_main = $negative_main * -1;

                    if ($creditor_main >= $debtor_main) {
                        $net_main = $creditor_main - $debtor_main;
                        $main_acc_type = 'creditor';
                    } else {
                        $net_main = $debtor_main - $creditor_main;
                        $main_acc_type = 'debtor';
                    }

                    $creditor_open = $positive_open;
                    $debtor_open = $negative_open * -1;
                    if ($creditor_open >= $debtor_open) {
                        $net_open = $creditor_open - $debtor_open;
                        $open_acc_type = 'creditor';
                    } else {
                        $net_open = $debtor_open - $creditor_open;
                        $open_acc_type = 'debtor';
                    }

                    $net_debtor_close = $debtor_open + $debtor_main;
                    $net_creditor_close = $creditor_open + $creditor_main;
                    if ($net_creditor_close >= $net_debtor_close) {
                        $net_close = $net_creditor_close - $net_debtor_close;
                        $net_acc_type = 'creditor';
                    } else {
                        $net_close = $net_debtor_close - $net_creditor_close;
                        $net_acc_type = 'debtor';
                    }
                }

            @endphp
            <td data-title="Debit">
                <a>
                    {{ $open_acc_type == 'debtor' ? DisplayDouble($net_open) : 0 }}
                </a>
            </td>
            <td data-title="Credit">
                <a>
                    {{ $open_acc_type == 'creditor' ? DisplayDouble($net_open) : 0 }}
                </a>
            </td>
            <td data-title="Debit">
                <a>
                    {{ DisplayDouble($debtor_main) }}
                </a>
            </td>
            <td data-title="Credit">
                <a>
                    {{ DisplayDouble($creditor_main) }}
                </a>
            </td>
            <td data-title="Debit">
                <a>
                    {{ $main_acc_type == 'debtor' ? DisplayDouble($net_main) : 0 }}
                </a>
            </td>
            <td data-title="Credit">
                <a>
                    {{ $main_acc_type == 'creditor' ? DisplayDouble($net_main) : 0 }}
                </a>
            </td>
            <td data-title="Net Movement">
                {{ $net_acc_type == 'debtor' ? DisplayDouble($net_close) : 0 }}
            </td>
            <td data-title="Net Movement">
                {{ $net_acc_type == 'creditor' ? DisplayDouble($net_close) : 0 }}
            </td>
        </tr>
        @include('ac_reports.includes.trial_balance_table', [
            'parents' => $parent,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'branch_cost_centers' => $branch_cost_centers,
            'field_cost_centers' => $field_cost_centers,
        ])
    @else
        <tr>
            <td data-title="Account" colspan="3">
                <a>
                    <tab{{ $parent->account_level }}>
                        {{ $parent->account_number }} - {{ $parent->account_name_ar }}
                        {{-- - {{ $parent->account_level }} --}}
                        </tab{{ $parent->account_level }}>

                </a>
            </td>

            @php

                // $array_of_acc = null;
                // $array_of_acc = $accountStatementService->getParents($parent->account_number);
                $debtor_main = 0;
                $creditor_main = 0;
                $debtor_open = 0;
                $creditor_open = 0;
                $net_open = 0;
                $net_main = 0;
                $net_close = 0;
                $main_acc_type = '';
                $net_debtor_close = 0;
                $net_creditor_close = 0;
                $net_acc_type = '';
                $open_acc_type = '';

                //for in period search (main)
                $positive_main = $closedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative_main = $closedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');
                //for before start period search (open)
                $positive_open = $openedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative_open = $openedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');

                //debtor
                if ($account->account_type == 'debtor') {
                    $debtor_main = $positive_main;
                    $creditor_main = $negative_main * -1;
                    if ($debtor_main >= $creditor_main) {
                        $net_main = $debtor_main - $creditor_main;
                        $main_acc_type = 'debtor';
                    } else {
                        $net_main = $creditor_main - $debtor_main;
                        $main_acc_type = 'creditor';
                    }

                    $debtor_open = $positive_open;
                    $creditor_open = $negative_open * -1;
                    if ($debtor_open >= $creditor_open) {
                        $net_open = $debtor_open - $creditor_open;
                        $open_acc_type = 'debtor';
                    } else {
                        $net_open = $creditor_open - $debtor_open;
                        $open_acc_type = 'creditor';
                    }

                    $net_debtor_close = $debtor_open + $debtor_main;
                    $net_creditor_close = $creditor_open + $creditor_main;
                    if ($net_debtor_close >= $net_creditor_close) {
                        $net_close = $net_debtor_close - $net_creditor_close;
                        $net_acc_type = 'debtor';
                    } else {
                        $net_close = $net_creditor_close - $net_debtor_close;
                        $net_acc_type = 'creditor';
                    }
                } else {
                    //creditor
                    $creditor_main = $positive_main;
                    $debtor_main = $negative_main * -1;

                    if ($creditor_main >= $debtor_main) {
                        $net_main = $creditor_main - $debtor_main;
                        $main_acc_type = 'creditor';
                    } else {
                        $net_main = $debtor_main - $creditor_main;
                        $main_acc_type = 'debtor';
                    }

                    $creditor_open = $positive_open;
                    $debtor_open = $negative_open * -1;
                    if ($creditor_open >= $debtor_open) {
                        $net_open = $creditor_open - $debtor_open;
                        $open_acc_type = 'creditor';
                    } else {
                        $net_open = $debtor_open - $creditor_open;
                        $open_acc_type = 'debtor';
                    }

                    $net_debtor_close = $debtor_open + $debtor_main;
                    $net_creditor_close = $creditor_open + $creditor_main;
                    if ($net_creditor_close >= $net_debtor_close) {
                        $net_close = $net_creditor_close - $net_debtor_close;
                        $net_acc_type = 'creditor';
                    } else {
                        $net_close = $net_debtor_close - $net_creditor_close;
                        $net_acc_type = 'debtor';
                    }
                }

            @endphp
            <td data-title="Debit">
                <a>
                    {{ $open_acc_type == 'debtor' ? DisplayDouble($net_open) : 0 }}
                </a>
            </td>
            <td data-title="Credit">
                <a>
                    {{ $open_acc_type == 'creditor' ? DisplayDouble($net_open) : 0 }}
                </a>
            </td>
            <td data-title="Debit">
                <a>
                    {{ DisplayDouble($debtor_main) }}
                </a>
            </td>
            <td data-title="Credit">
                <a>
                    {{ DisplayDouble($creditor_main) }}
                </a>
            </td>
            <td data-title="Debit">
                <a>
                    {{ $main_acc_type == 'debtor' ? DisplayDouble($net_main) : 0 }}
                </a>
            </td>
            <td data-title="Credit">
                <a>
                    {{ $main_acc_type == 'creditor' ? DisplayDouble($net_main) : 0 }}
                </a>
            </td>
            <td data-title="Net Movement">
                {{ $net_acc_type == 'debtor' ? DisplayDouble($net_close) : 0 }}
            </td>
            <td data-title="Net Movement">
                {{ $net_acc_type == 'creditor' ? DisplayDouble($net_close) : 0 }}
            </td>
        </tr>
    @endif
@endforeach

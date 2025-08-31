@foreach ($accountStatementService->parents($parents->account_number) as $parent)
    @if (count($accountStatementService->parents($parent->account_number)))
        <tr>

            <td data-title="Account" colspan="3" class="" style="font-weight:bold">

                <tab{{ $parent->account_level }}>
                    {{ $parent->account_number }} - {{ $parent->account_name_ar }}
                    {{-- - {{ $parent->account_level }} --}}
                    </tab{{ $parent->account_level }}>

            </td>
            <td colspan="1" class="">
                &nbsp;
            </td>

            {{-- <td class="hide balance-ytd"></td> --}}
            @php
                $array_of_acc = null;
                $total_sub_assets = 0;
                $array_of_acc = $accountStatementService->getParents($parent->account_number);
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
                $positive_main = $closedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');

                $negative_main = $closedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');
                //for before start period search (open)
                $positive_open = $openedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative_open = $openedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');

                // if ($parent->account_number == '110201') {
                //     echo '<br>';
                //     print_r($negative_open);
                //     echo '</br>';
                //     print_r($positive_open);
                //     echo '</br>';
                //     print_r($negative_main);
                //     echo '</br>';
                //     print_r($positive_main);
                //     echo '</br>';
                // }

                //debtor
                if ($parent->account_type == 'debtor') {
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

                if ($net_acc_type == $parent->account_type) {
                    $total_sub_assets += $net_close;
                } else {
                    $total_sub_assets -= $net_close;
                }

            @endphp

            {{-- <td class="balance-ytd hide"></td> --}}
        </tr>
        @include('ac_reports.includes.balance_sheet_table', [
            'parents' => $parent,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'branch_cost_centers' => $branch_cost_centers,
            'field_cost_centers' => $field_cost_centers,
        ])

        <tr style="border-top:1pt solid black;font-weight:bold">
            <td colspan="3" class="">
                <tab{{ $parent->account_level }}>
                    مجموع {{ $parent->account_number }} - {{ $parent->account_name_ar }}
                    </tab{{ $parent->account_level }}>
            </td>
            <td colspan="1" class="text-center">
                @if ($total_sub_assets >= 0)
                    {{ DisplayDouble($total_sub_assets) }}
                @else
                    ({{ DisplayDouble($total_sub_assets * -1) }})
                @endif
            </td>
        </tr>
    @else
        <tr>
            <td data-title="Account" colspan="3" class="">
                <tab{{ $parent->account_level }}>
                    {{ $parent->account_number }} - {{ $parent->account_name_ar }}
                    </tab{{ $parent->account_level }}>
            </td>
            @php
                $array_of_acc = null;
                $total_sub_assets = 0;
                $array_of_acc = $accountStatementService->getParents($parent->account_number);

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
                $positive_main = $closedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative_main = $closedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');
                //for before start period search (open)
                $positive_open = $openedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative_open = $openedEntries->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');


                    //debtor
                if ($parent->account_type == 'debtor') {
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

                if ($net_acc_type == $parent->account_type) {
                    $total_sub_assets += $net_close;
                } else {
                    $total_sub_assets -= $net_close;
                }

            @endphp
            <td colspan="1" class="text-center" data-title="Debit">
                @if ($array_of_acc[0] == $profits_losses_current_period_acc)
                    @if ($profits_losses_current_period >= 0)
                        {{ DisplayDouble($profits_losses_current_period) }}
                    @else
                        ({{ DisplayDouble($profits_losses_current_period * -1) }})
                    @endif
                @else
                    @if ($total_sub_assets >= 0)
                        {{ DisplayDouble($total_sub_assets) }}
                    @else
                        ({{ DisplayDouble($total_sub_assets * -1) }})
                    @endif
                @endif
            </td>

        </tr>
    @endif
@endforeach
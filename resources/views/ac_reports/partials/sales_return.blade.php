<tr style="font-weight:bold">
    <td colspan="3" class="">
        {{ $sales_return->account_number }} - {{ $sales_return->account_name_ar }}
    </td>
    <td colspan="1" class="">
        &nbsp;
    </td>
</tr>
@php
    $array_of_acc = $accountStatementService->getParents($sales_return->account_number, true);
    $total_sales_return = 0;
@endphp
@foreach ($array_of_acc as $account)
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
        $account_details = $accountStatementService
            ->getAccountMaster()
            ->where('account_number', $account)
            ->first();

        $positive_main = $closedJournalEntries
            ->where('account_number', $account)
            ->where('amount', '>', 0)
            ->sum('amount');
        $negative_main = $closedJournalEntries
            ->where('account_number', $account)
            ->where('amount', '<', 0)
            ->sum('amount');
        //for before start period search (open)
        $positive_open = $openedJournalEntries
            ->where('account_number', $account)
            ->where('amount', '>', 0)
            ->sum('amount');
        $negative_open = $openedJournalEntries
            ->where('account_number', $account)
            ->where('amount', '<', 0)
            ->sum('amount');
        //debtor
        if ($account_details->account_type == 'debtor') {
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
    @if ($net_close != 0)
        <tr>
            <td colspan="3" class="">
                <tab2>
                    {{ $account_details->account_number }} -
                    {{ $account_details->account_name_ar }}
                </tab2>
            </td>
            <td colspan="1" class="text-center">
                @if ($net_acc_type == $account_details->account_type)
                    {{ $net_close }}
                    @php $total_sales_return += $net_close; @endphp
                @else
                    ({{ $net_close }})
                    @php $total_sales_return -= $net_close; @endphp
                @endif
            </td>
        </tr>
    @endif
@endforeach



<tr style="border-top:1pt solid black;font-weight:bold">
    <td colspan="3" class="">
        مجموع {{ $sales_return->account_number }} -
        {{ $sales_return->account_name_ar }}
    </td>
    <td colspan="1" class="text-center">
        @if ($total_sales_return >= 0)
            {{ $total_sales_return }}
        @else
            ({{ $total_sales_return }})
        @endif
    </td>
</tr>

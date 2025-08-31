@foreach ($accountStatementService->parents($parents->account_number) as $parent)
    @php
        $array_of_acc = null;
        $array_of_acc = $accountStatementService->getParents($parent->account_number);
    @endphp
    @if (count($array_of_acc))
        <tr>
            <td data-title="Account" colspan="3">
                <tab{{ $parent->account_level }}>
                    {{ $parent->account_number }} - {{ $parent->account_name_ar }}
                    </tab{{ $parent->account_level }}>
            </td>

            @php
                // $array_of_cost_center = [];
                $branch_cost_centers = [];
                $field_cost_centers = [];
                // if ($branch_cost_center) {
                //     $branch_cost_centers = $accountStatementService->getCostBrnachesParents($branch_cost_center);
                // }
                // if ($field_cost_center) {
                //     $field_cost_centers = $accountStatementService->getCostFieldParents($field_cost_center);
                // }
                $debtor_sub_main = 0;
                $creditor_sub_main = 0;

                $positive_sub_main = $closedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative_sub_main = $closedEntries
                    ->whereIn('account_number', $array_of_acc)
                    ->where('amount', '<', 0)
                    ->sum('amount');
                //debtor
                if ($account->account_type == 'debtor') {
                    $debtor_sub_main = $positive_sub_main;
                    $creditor_sub_main = $negative_sub_main * -1;
                } else {
                    //creditor
                    $creditor_sub_main = $positive_sub_main;
                    $debtor_sub_main = $negative_sub_main * -1;
                }

            @endphp
            <td data-title="Debit">
                {{ DisplayDouble($debtor_sub_main) }}
            </td>
            <td data-title="Credit">
                {{ DisplayDouble($creditor_sub_main) }}
            </td>
            <td data-title="Net Movement">
                {{ DisplayDouble($positive_sub_main + $negative_sub_main) }}
            </td>
            {{-- <td class="balance-ytd hide"></td> --}}
        </tr>
        @include('ac_reports.includes.tree_table', [
            'parents' => $parent,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'branch_cost_center' => $branch_cost_center,
            'field_cost_center' => $field_cost_center,
        ])
    @else
        <tr>
            <td data-title="Account" colspan="3" class="">
                <tab{{ $parent->account_level }}>
                    {{ $parent->account_number }} - {{ $parent->account_name_ar }}
                    {{-- - {{ $parent->account_level }} --}}
                    </tab{{ $parent->account_level }}>
            </td>

            {{-- <td class="hide balance-ytd"></td> --}}

            @php
                // $array_of_cost_center = [];
                // $branch_cost_centers = [];
                // $field_cost_centers = [];
                // if ($branch_cost_center) {
                //     $branch_cost_centers = $accountStatementService->getCostBranches($branch_cost_center);
                // }
                // if ($field_cost_center) {
                //     $field_cost_centers = $accountStatementService->getCostCenFieldAdd($field_cost_center);
                // }
                $debtor = 0;
                $creditor = 0;
                $positive = $closedEntries
                    ->where('account_number', $parent->account_number)
                    ->where('amount', '>', 0)
                    ->sum('amount');
                $negative = $closedEntries
                    ->where('account_number', $parent->account_number)
                    ->where('amount', '<', 0)
                    ->sum('amount');
                //debtor
                if ($parent->account_type == 'debtor') {
                    $debtor = $positive;
                    $creditor = $negative * -1;
                } else {
                    //creditor
                    $creditor = $positive;
                    $debtor = $negative * -1;
                }

            @endphp
            <td data-title="Debit">
                {{ DisplayDouble($debtor) }}
            </td>
            <td data-title="Credit">
                {{ DisplayDouble($creditor) }}
            </td>
            <td data-title="Net Movement">
                {{ DisplayDouble($positive + $negative) }}
            </td>
        </tr>
    @endif
@endforeach

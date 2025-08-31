<div class="table-responsive manage-currency-table reports-table">

    <table class="table table-bordered text-center user_tr_table" id="user_tree_table">
        <thead>
            <tr class="text-end">
                <th>{{ number_format(abs($totalDebtor - $totalCreditor), 2) }}</th>
                <th class="fs6">Due</th>
                <th class="fs6">من 210 الي ما قبل</th>
                <th class="fs6">من 180 الي 210</th>
                <th class="fs6">من 150 الي 180</th>
                <th class="fs6">من 120 الي 150</th>
                <th class="fs6">من 90 الي 120</th>
                <th class="fs6">من 60 الي 90</th>
                <th class="fs6">من 30 الي 60</th>
                <th class="fs6">من اليوم الي 30</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="fs6">الرصيد مطابق</td>
                <td><input type="radio" name="balance_is_match" id=""></td>
                @foreach (array_reverse($dateRanges) as $date)
                    <td rowspan="2" colspan="1" >
                        @if ($loop->first)
                            {{ number_format(
                                $combinedData->where('entry_date', '<=', min($date))->sum(function ($num) {
                                    return (int)$num['amount'];
                                }),
                                3
                            ) }}
                        @else
                            {{ number_format(
                                $combinedData->whereBetween('entry_date', array_reverse($date))->sum(function ($num) {
                                    return (int)$num['amount'];
                                }),
                                3
                            ) }}
                        @endif
                    </td>
                @endforeach
            </tr>
            <tr>
                <td class="fs6">الرصيد غير مطابق</td>
                <td><input type="radio" name="balance_is_match" id=""></td>
            </tr>

        </tbody>
    </table>
</div>
<table>
    <thead>
        <tr>
            <th>اسم الحساب (بالعربي)</th>
            <th>اسم الحساب (بالانجليزي)</th>
            <th>رقم الحساب</th>
            <th>رقم الحساب الاب</th>
            <th>نوع الحساب</th>
            <th>مستوى الحساب</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($masters as $account)
            <tr>
                <td>{{ $account->account_name_ar }}</td>
                <td>{{ $account->account_name_en }}</td>
                <td>{{ $account->account_number }}</td>
                <td>{{ $account->parent_acct_no }}</td>
                <td>{{ $account->account_type }}</td>
                <td>{{ $account->account_level }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@extends('layouts.app')
@section('title', __('payment.payment'))

@section('content')
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __( 'payment.all_payments' )])
            <table class='table'>
                <tr>
                    <th>الجهة</th>
                    <td>{{ $payment->contact->name }}</td>
                    <th>التاريخ</th>
                    <td>{{ $payment->date->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>الحساب</th>
                    <td>{{ $payment->account->account_name_ar }}</td>
                    <th>المبلغ</th>
                    <td>@format_currency($payment->amount)</td>
                </tr>
                <tr>
                    <th>رقم المرجع</th>
                    <td>{{ $payment->reference_number }}</td>
                    <th>النوع</th>
                    <td>{{ $payment->type }}</td>
                </tr>
                <tr>
                    <th>الوصف</th>
                    <td>{{ $payment->description }}</td>
                </tr>
            </table>
        @endcomponent
    </section>
@endsection

@extends('layouts.app')
@section('title', __('journal_entry.opening_balance'))

@section('content')
    <section class="content-header">
        <h1>@lang('product.add_new_product')</h1>
    </section>
    <section class='content'>
        <div class='row'>
            <div class='col-sm-12'>
                <div class="add_subscription_form add-products-form">
                    <div class="subscribers add_subscription">
                        <h2>{{ __("journal_entry.opening_balances.{$type}") }}</h2>
                    </div>
                    <form action="{{ route('opening_balance.store', ['type' => $type]) }}" method="POST">
                        @csrf
                        <div class='row flex-row mr-8 mt-10'>
                            <div class='col-sm-4'>
                                <label for="opening_balance_date">{{ __('sale.sale_date') }}:*</label>
                            </div>
                            <div class='col-sm-6'>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="date" id="opening_balance_date" class="date form-control" required readonly value="{{ old('date', now()->format('Y-m-d')) }}">
                                    @error('date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class='row flex-row mr-8 mt-10'>
                            <div class='col-sm-4'>
                                <label for="description">{{ __('journal_entry.description') }}:*</label>
                            </div>
                            <div class='col-sm-6'>
                                <input type="text" name="description" id="description" class="form-control" required value="{{ old('description') }}">
                            </div>
                            @error('date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class='row ml-8 mr-8 mt-10 mb-12'>
                            <div class='col-sm-4'>
                                <label for="select_location_id">{{ __('journal_entry.opening_balance_account') }}:*</label>
                            </div>
                            <div class='col-sm-6'>
                                <select name="opening_account" id="select_location_id" class="form-control select2" required autofocus>
                                    <option value="">{{ __('lang_v1.select_location') }}</option>
                                    @foreach($accounts as $key => $value)
                                        <option value="{{ $key }}" {{ old('opening_account', array_key_first($accounts)) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class='row m-2 mt-10'>
                            <div class='col-sm-12'>
                                @include("ac_journal_entry.partials.opening_balance.{$type}_row")
                                @if ($type === 'account')
                                @endif
                                <div class='flex-end'>
                                    <button class="btn btn-primary" id="add_btn" type='button'>إضافة المزيد</button>
                                </div>
                            </div>
                        </div>
                        <button class='btn btn-primary mr-8'>متابعة</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <style>
        .flex-end {
            display: flex;
            justify-content: end;
            align-items: center;
        }

        .flex-row {
            display: flex;
            justify-content: start;
            align-items: center;
        }

        .add_subscription_form.add-products-form {
            background: white;
            min-height: 280px;
        }

        .add-products-form {
            box-shadow: 0px 0px 1px 1px #d3d3d3;
            border-radius: 4px;
            padding-bottom: 45px;
        }

        .add_subscription_form {
            background: white;
            margin-bottom: 50px;
        }

        .add-products-form .add_subscription {
            border-top: 4px solid #149999;
            background-color: #706db1;
            border-bottom: 1px solid #14293C;
            padding: 12px 16px;
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
        }

        .add_subscription {
            border-bottom: 4px solid #a4dbc4;
            display: inline-block;
            width: 100%;
            padding-bottom: 8px;
            padding-top: 30px;
        }

        .add-products-form .add_subscription h2 {
            float: right;
            width: 100%;
            color: #fff !important;
        }

        .add_subscription h2 {
            font-size: 1.7em;
            font-weight: 300;
            margin-top: 0px;
            margin-bottom: 0px;
            float: left;
        }

        .subscribers h2 {
            font-size: 1.688em;
            font-weight: 300;
        }

        .sw-btn-pick-option {
            margin: 30px;
            width: 140px;
            height: 140px;
        }

        .sw-btn-pick-option {
            margin: 30px;
            width: 140px;
            height: 140px;
            font-size: 18px;
        }
    </style>
@endsection

@section('javascript')
    <script>
           $('#opening_balance_date').datepicker({
        format: moment_date_format ,
        ignoreReadonly: true,
    });
    </script>
    @include("ac_journal_entry.partials.opening_balance.{$type}_script")
@endsection

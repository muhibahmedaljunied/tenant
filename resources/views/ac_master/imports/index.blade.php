@extends('layouts.app')
@section('title', __('chart_of_accounts.import_master'))
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('chart_of_accounts.import_master')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        {{-- @if (session('notification') || !empty($notification))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if (!empty($notification['msg']))
                        {{$notification['msg']}}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            </div>  
        </div>     
    @endif --}}

        <div class="row">
            <div class="col-sm-12">
                @component('components.widget', ['class' => 'box-primary'])

                <form action="{{ route('ac.import-master.store') }}" method="POST" enctype="multipart/form-data">
    @csrf



                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="import_file">{{ __('product.file_to_import') }}:</label>
                                    <input type="file" id="import_file" name="import_file" accept=".xls, .xlsx, .csv"
                                        required>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <br>
                                <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
                            </div>
                        </div>

                    </div>

                    </form>
                    <br><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="{{ asset('files/accounting/masters.xlsx') }}" class="btn btn-success" download><i
                                    class="fa fa-download"></i> @lang('lang_v1.download_template_file')</a>
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.instructions')])
                    <strong class="mb-0">@lang('lang_v1.instruction_line1')</strong><br>
                    <p>
                        @lang('chart_of_accounts.import_master_instruction')
                    </p>
                    <hr>
                    <br>
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('lang_v1.col_no')</th>
                            <th>@lang('lang_v1.col_name')</th>
                            <th>@lang('lang_v1.instruction')</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>@lang('chart_of_accounts.import_fields.name_ar') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.name_ar_ins') <!-- Instruction for Arabic Name field --></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>@lang('chart_of_accounts.import_fields.name_en') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.name_en_ins') <!-- Instruction for English Name field --></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>@lang('chart_of_accounts.import_fields.account_number') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.account_number_ins') <!-- Instruction for Account Number field --></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>@lang('chart_of_accounts.import_fields.parent_account_number') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.parent_account_number_ins') <!-- Instruction for Parent Account Number field --></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>@lang('chart_of_accounts.import_fields.account_type') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.account_type_ins') <!-- Instruction for Account Type field, must be 'debtor' or 'creditor' -->
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>@lang('chart_of_accounts.import_fields.account_level') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.account_level_ins') <!-- Instruction for Account Level field --></td>
                        </tr>
                    </table>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

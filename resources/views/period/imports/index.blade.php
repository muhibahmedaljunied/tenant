@extends('layouts.app')
@section('title', __('chart_of_accounts.import_journal_entry'))
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('chart_of_accounts.import_journal_entry')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                @component('components.widget', ['class' => 'box-primary'])
             <form action="{{ route('ac_journal.import.post') }}" method="POST" enctype="multipart/form-data">
    @csrf



                    <div class="row">
                        <div class="col-sm-6">
                       <div class="col-sm-8">
    <div class="form-group">
        <label for="import_file">{{ __('product.file_to_import') }}:</label>
        <input type="file" id="import_file" name="import_file" accept=".xls, .xlsx, .csv" required>
    </div>
</div>


                            <div class="col-sm-4">
                                <br>
                                <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
                            </div>
                        </div>
                    </div>
               </form>
                    <br><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="{{ asset('files/accounting/journal_entries_template.xlsx') }}" class="btn btn-success"
                                download><i class="fa fa-download"></i> @lang('lang_v1.download_template_file')</a>
                        </div>
                    </div>
                    @if (session('status.info'))
                        <hr>
                        <div class="alert alert-info">
                            <p class="text-bold">
                                تم استيراد {{ session('status.info.entries_imported') }}
                                |
                                من اصل {{ session('status.info.entries_count') }}
                                |
                                وفشل {{ session('status.info.failed') }}
                            </p>
                        </div>
                        @if (count(session('status.info.errors')) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach (session('status.info.errors') as $error)
                                        <li>
                                            {{ $error['msg'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                @endcomponent

            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.instructions')])
                    <strong>@lang('chart_of_accounts.import_fields.instruction_line1')</strong><br>
                    @lang('chart_of_accounts.import_fields.instruction_line2')
                    <br><br>
                    <table class="table table-striped">
                        <tr>
                            <th>@lang('chart_of_accounts.import_fields.col_no')</th>
                            <th>@lang('chart_of_accounts.import_fields.col_name')</th>
                            <th>@lang('chart_of_accounts.import_fields.instruction')</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>@lang('chart_of_accounts.import_fields.comments') <small class="text-muted">(@lang('chart_of_accounts.import_fields.optional'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.comments_ins')</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>@lang('chart_of_accounts.import_fields.credit') <small class="text-muted">(@lang('chart_of_accounts.import_fields.conditional'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.credit_ins')</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>@lang('chart_of_accounts.import_fields.debit') <small class="text-muted">(@lang('chart_of_accounts.import_fields.conditional'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.debit_ins')</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>@lang('chart_of_accounts.import_fields.account_number') <small class="text-muted">(@lang('chart_of_accounts.import_fields.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.account_number_exists_ins')</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>@lang('chart_of_accounts.import_fields.entry_description') <small class="text-muted">(@lang('chart_of_accounts.import_fields.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.entry_description_ins')</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>@lang('chart_of_accounts.import_fields.date') <small class="text-muted">(@lang('chart_of_accounts.import_fields.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.date_ins')</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>@lang('chart_of_accounts.import_fields.sequence_number') <small class="text-muted">(@lang('chart_of_accounts.import_fields.required'))</small></td>
                            <td>@lang('chart_of_accounts.import_fields.sequence_number_ins')</td>
                        </tr>
                    </table>
                    <hr>
                    <div>
                        <p class="text-muted">
                            1- تسلسل القيد لا يعبر عن رقم القيد وإنما للتفرقة بين اطراف القيود التي سيتم إضافتها
                            <br>
                            2- رقم الحساب يجب أن يعبر عن حسابات غير مرتبطة بحسابات فرعية أخرى أو حسابات في المستوى الأول أو
                            الثاني
                            <br>
                            3- يجب تساوي خانة المدين مع الدائن لكل تسلسل قيد
                            <br>
                            {{-- 4- يجب أن تكون القيود بعد تاريخ إقفال الحسابات “إذا تم تعبئته في الإعدادات العامة”
                            <br> --}}
                            4- لكل تسلسل قيد يتم تعبئة السطر الأول فقط لخانة التاريخ ووصف القيد ولا يتم النظر لأي تاريخ في السطر
                            الثاني ومابعده لكل تسلسل
                        </p>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

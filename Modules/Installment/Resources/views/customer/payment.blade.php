<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ action('\\Modules\\Installment\\Http\\Controllers\\InstallmentController@storepayment', $data->id) }}" method="POST" id="storepayment">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">تحصيل قسط</h4>
            </div>
            <div class="modal-body">

                <input type="hidden" name="contact_id" value="{{$data->contact_id}}" >
                <input type="hidden" name="installment_id" value="{{$data->id}}" >

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="number"> إسم العميل :</label>
                            <input type="text" name="number" id="number" value="{{ $contact->name }}" class="form-control integr" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="number"> رقم القسط :</label>
                            <input type="text" name="number" id="number" value="{{ $data->installment_number }}" class="form-control integr" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="number"> تاريخ الأستحقاق:</label>
                            <input type="text" name="number" id="number" value="{{ $data->installmentdate }}" class="form-control integr" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="installment_value"> قيمة القسط:</label>
                            <input type="text" name="installment_value" id="installment_value" value="{{$data->installment_value}}" class="form-control decimal intallparameter" readonly >
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="benefit_value"> الفائدة :</label>
                            <input type="text" name="benefit_value" id="benefit_value" value="{{ $data->benefit_value }}" class="form-control integr intallparameter" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="period"> التأخير :</label>
                            <input type="text" name="period" id="period" value="{{ $daylats }}" class="form-control integr" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="period"> مدة :</label>
                            <input type="text" name="period" id="period" value="{{ __('installment::lang.'.$data->latfinestype) }}" class="form-control integr intallparameter" readonly>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="period">نسبة الغرامة :</label>
                            <input type="text" name="period" id="period" value="{{ $data->latfines }}" class="form-control integr" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="installmentdate">تاريخ السداد: </label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" name="installmentdate" id="installmentdate" value="{{ Carbon::now()->format('Y-m-d') }}" class="form-control date-picker" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="latfines">غرامة التأخير :</label>
                            <input type="text" name="latfines" id="latfines" value="{{ $latfines_value }}" class="form-control decimal intallparameter" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="totallpaid"> الإجمالي:</label>
                            <input type="text" name="totallpaid" id="totallpaid" value="{{ $latfines_value+$data->benefit_value+$data->installment_value }}" class="form-control decimal intallparameter" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="account_id"> الحساب:</label>
                            <select name="account_id" id="account_id" class="form-control select2 getinstallment" style="width:100%">
                                <option value="">-- اختر الحساب --</option>
                                @foreach($accounts as $key => $value)
                                    <option value="{{ $key }}" {{ old('account_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn  btn-primary " > <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div>

<script>

$(document).on('keyup','#latfines',function () {
    var latfines=$('#latfines').val();
    var benefit_value=$('#benefit_value').val();
    var installment_value=$('#installment_value').val();

    var total=(installment_value*1+benefit_value*1+latfines*1).toFixed(2);
    $('#totallpaid').val(total);
});

    $('.date-picker').datepicker({
        autoclose: true,
        format:'yyyy-m-d',
    });

</script>

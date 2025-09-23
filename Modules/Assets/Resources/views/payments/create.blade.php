<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ route('payments-store') }}" method="post" id="addpayment">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">تسجيل مدفوعات شريك</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="partner_id">إسم الشريك :*</label>
                    <select name="partner_id" id="partner_id" class="form-control select2" style="width:100%;height: 40px;">
                        @foreach($Assets as $key => $value)
                            <option value="{{ $key }}" {{ old('partner_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="value"> القيمة :*</label>
                    <input type="text" name="value" id="value" class="form-control" required placeholder="قيمة العملية بالجنية" value="{{ old('value') }}">
                </div>

                <div class="form-group">
                    <label for="type">نوع العملية</label>
                    <select name="type" id="type" class="form-control select2" style="width:100%;height: 40px;">
                        <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>سحب</option>
                        <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>إيداع</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">تاريخ العملية :</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="date" id="date" class="form-control date-picker" required placeholder="{{ __('Assets.purchasedate') }}" readonly value="{{ old('date') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes"> الملاحظات :</label>
                    <input type="text" name="notes" id="notes" class="form-control" placeholder="ملاحظات" value="{{ old('notes') }}">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
    $('.date-picker').datepicker({
        autoclose: true,
        endDate: 'today',
        format:'yyyy-m-d',
    });
</script>
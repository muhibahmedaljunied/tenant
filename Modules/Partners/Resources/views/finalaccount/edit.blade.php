<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ action('\\Modules\\Partners\\Http\\Controllers\\FinalAccountController@update', $data->id) }}" id="edit" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">تعديل حساب ختامي</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="profite">قيمة الأرباح الموزعة :</label>
                    <input type="text" name="profite" id="profite" value="{{ $data->profite }}" class="form-control decimal" required placeholder="القيمة بالجنية">
                </div>
                <div class="form-group">
                    <label for="sharenumber">إجمالي عدد الأسهم :</label>
                    <input type="text" name="sharenumber" id="sharenumber" value="{{ $data->sharenumber }}" class="form-control" readonly placeholder="">
                </div>
                <div class="form-group">
                    <label for="shareval">قيمة السهم :</label>
                    <input type="text" name="shareval" id="shareval" value="{{ number_format($data->profite/$data->sharenumber,2) }}" class="form-control" readonly placeholder="">
                </div>
                <div class="form-group">
                    <label for="startdate">عن المدة من :</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="startdate" id="startdate" value="{{ $data->startdate }}" class="form-control date-picker" required placeholder="بداية المدة">
                    </div>
                </div>
                <div class="form-group">
                    <label for="enddate">إلي :</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <input type="text" name="enddate" id="enddate" value="{{ $data->enddate }}" class="form-control date-picker" required placeholder="نهاية المدة">
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">ملاحظات :</label>
                    <input type="text" name="notes" id="notes" value="{{ $data->notes }}" class="form-control">
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
        format:'yyyy-m-d',
    });


    $("#profite").on('keyup',function () {
        var total=$(this).val();
        var number=$('#sharenumber').val();
        var sharval=(total/number).toFixed(2);
        $('#shareval').val(sharval);

        $('.share').each(function (index,item) {
            var id = $(this).attr('id');
            var remval=$(this).val()*sharval -$('#value_'+id).val();
            $('#rem_'+id).val(remval.toFixed(2));

        });


    });

</script>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('partners-store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">إضافة شريك</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">إسم الشريك :*</label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="الإسم">
                </div>
                <div class="form-group">
                    <label for="address">العنوان :*</label>
                    <input type="text" name="address" id="address" class="form-control" required placeholder="العنوان">
                </div>
                <div class="form-group">
                    <label for="mobile">رقم الموبيل :</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="رقم المبيل">
                </div>
                <div class="form-group">
                    <label for="capital">قيمة رأس المال :</label>
                    <input type="text" name="capital" id="capital" class="form-control" placeholder="قيمة رأس المال">
                </div>
                <div class="form-group">
                    <label for="share">عدد الأسهم :</label>
                    <input type="text" name="share" id="share" class="form-control" placeholder="عدد الأسهم">
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
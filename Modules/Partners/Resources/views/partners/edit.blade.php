<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ action('\\Modules\\Partners\\Http\\Controllers\\PartnersController@update', $partner->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">تعديل بينانات شريك</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">إسم الشريك :*</label>
                    <input type="text" name="name" id="name" value="{{ $partner->name }}" class="form-control" required placeholder="الإسم">
                </div>
                <div class="form-group">
                    <label for="address">العنوان :*</label>
                    <input type="text" name="address" id="address" value="{{ $partner->address }}" class="form-control" required placeholder="العنوان">
                </div>
                <div class="form-group">
                    <label for="mobile">رقم الموبيل :</label>
                    <input type="text" name="mobile" id="mobile" value="{{ $partner->mobile }}" class="form-control" placeholder="رقم المبيل">
                </div>
                <div class="form-group">
                    <label for="capital">قيمة رأس المال :</label>
                    <input type="text" name="capital" id="capital" value="{{ $partner->capital }}" class="form-control" placeholder="قيمة رأس المال">
                </div>
                <div class="form-group">
                    <label for="share">عدد الأسهم :</label>
                    <input type="text" name="share" id="share" value="{{ $partner->share }}" class="form-control" placeholder="عدد الأسهم">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

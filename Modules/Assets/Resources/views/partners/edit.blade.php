<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ route('assets-update', $partner->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">تعديل بينانات شريك</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name">إسم الشريك :*</label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="الإسم" value="{{ old('name', $partner->name) }}">
                </div>

                <div class="form-group">
                    <label for="address"> العنوان :*</label>
                    <input type="text" name="address" id="address" class="form-control" required placeholder="العنوان" value="{{ old('address', $partner->address) }}">
                </div>

                <div class="form-group">
                    <label for="mobile"> رقم الموبيل :</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" placeholder="رقم المبيل" value="{{ old('mobile', $partner->mobile) }}">
                </div>

                <div class="form-group">
                    <label for="capital">قيمة رأس المال :</label>
                    <input type="text" name="capital" id="capital" class="form-control" placeholder="قيمة رأس المال" value="{{ old('capital', $partner->capital) }}">
                </div>
                <div class="form-group">
                    <label for="share">عدد الأسهم :</label>
                    <input type="text" name="share" id="share" class="form-control" placeholder="عدد الأسهم" value="{{ old('share', $partner->share) }}">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

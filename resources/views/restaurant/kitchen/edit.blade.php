<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form method="POST" action="{{ action('Restaurant\\KitchenController@update', $kitchen->id) }}" id="kitchen_edit">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">تعديل بيانات</h4>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <input type="hidden" name="id" value="{{$kitchen->id}}">

                    <label for="name">{{ 'إسم القسم' . ':*' }}</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $kitchen->name }}" required>
                </div>
                <div class="form-group">
                    <label for="location_id">{{ 'الفرع:' }}</label>
                    <select name="location_id" id="location_id" class="form-control">
                        @foreach($business_locations as $key => $value)
                            <option value="{{ $key }}" @if($kitchen->location_id == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">{{ 'الوصف:' }}</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ $kitchen->description }}">
                </div>
                <div class="form-group">
                    <label for="printer_id">{{ 'الطابعه:' }}</label>
                    <select name="printer_id" id="printer_id" class="form-control">
                        @foreach($printers as $key => $value)
                            <option value="{{ $key }}" @if($kitchen->printer_id == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
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

</script>
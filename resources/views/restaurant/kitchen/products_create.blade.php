<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form method="POST" action="{{ action('Restaurant\\KitchenController@store') }}" id="addproduct">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">إضافة صنف إلي  مطبخ</h4>
            </div>

            <div class="modal-body">
                <input type="hidden" name="product_id" value="{{$product}}">
                <div class="form-group">
                    <label for="kitchen_id">{{ 'المطبخ:' }}</label>
                    <select name="kitchen_id" id="kitchen_id" class="form-control" style="width:80%">
                        @foreach($kitchen as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
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
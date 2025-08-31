<div class="modal-dialog" role="document">
    <div class="modal-content">

   <form action="{{ action('Restaurant\KitchenController@store') }}" method="post" id="kitchen_create">
    @csrf
    <!-- Your form fields go here -->



        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">إضافة قسم</h4>
        </div>

        <div class="modal-body">

        <div class="form-group">
    <label for="name">إسم القسم:*</label>
    <input type="text" id="name" name="name" class="form-control" required>
</div>

<div class="form-group">
    <label for="location_id">الفرع:</label>
    <select id="location_id" name="location_id" class="form-control">
        @foreach($business_locations as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="description">الوصف:</label>
    <input type="text" id="description" name="description" class="form-control">
</div>

<div class="form-group">
    <label for="printer_id">الطابعه:</label>
    <select id="printer_id" name="printer_id" class="form-control">
        @foreach($printers as $key => $value)
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
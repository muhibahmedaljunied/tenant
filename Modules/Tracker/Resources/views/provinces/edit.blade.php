<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ route('province-update', ['province' => $province]) }}" method="POST" id="editprovince">
            @csrf
            @method('PUT')

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">تعديل إقليم</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" name="name" id="name" class="form-control" required placeholder="" value="{{ old('name', $province->name) }}">
            </div>
            <div class="form-group">
                <label for="phone">موبايل</label>
                <input type="text" name="phone" id="phone" class="form-control" required placeholder="" value="{{ old('phone', $province->phone) }}">
            </div>
            <div class="form-group">
                <label for="user_id">المسئول</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value=""></option>
                    @foreach($users as $key => $value)
                        <option value="{{ $key }}" {{ old('user_id', $province->user_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="description">الوصف</label>
                <textarea name="description" id="description" class="form-control" required placeholder="">{{ old('description', $province->description) }}</textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
        </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

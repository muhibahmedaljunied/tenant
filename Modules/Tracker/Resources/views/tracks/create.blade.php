<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('\\Modules\\Tracker\\Http\\Controllers\\TrackController@store') }}" method="POST" id="addtrack">
            @csrf

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">تسجيل خط سير جديد</h4>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label for="name">الاسم</label>
                <input type="text" name="name" id="name" class="form-control" required placeholder="" value="{{ old('name') }}">
            </div>

            <div style="display: flex;">
                <div class="form-group" style="margin-left: 5px; width: 33%">
                    <label for="distribution_area_id">منطقة التوزيع</label>
                    <select name="distribution_area_id" id="distribution_area_id" class="form-control" required>
                        <option value=""></option>
                        @foreach($distributionAreas as $key => $value)
                            <option value="{{ $key }}" {{ old('distribution_area_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-right: 5px; width: 33%">
                    <label for="sector_id">قطاع</label>
                    <input type="text" name="sector_id" id="sector_id" class="form-control" required placeholder="" value="{{ old('sector_id') }}" readonly>
                </div>
                <div class="form-group" style="margin-right: 5px; width: 33%">
                    <label for="province_id">إقليم</label>
                    <input type="text" name="province_id" id="province_id" class="form-control" required placeholder="" value="{{ old('province_id') }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="user_id">المسئول</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value=""></option>
                    @foreach($users as $key => $value)
                        <option value="{{ $key }}" {{ old('user_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="contact_ids">العميل</label>
                <select name="contact_ids[]" id="contacts" class="form-control" multiple>
                    @foreach($contacts as $key => $contact)
                        <option value="{{ $key }}">{{ $contact }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="description">الوصف</label>
                <textarea name="description" id="description" class="form-control" required placeholder="">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
        </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('\\Modules\\Tracker\\Http\\Controllers\\DistributionAreaController@store') }}" method="POST" id="addarea">
            @csrf

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">تسجيل منطقة توزيع جديدة</h4>
        </div>

        <div class="modal-body">
            <div style="display: flex;">
                <div class="form-group width-50" style="margin-left: 5px;">
                    <label for="name">الاسم</label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="" value="{{ old('name') }}">
                </div>
                <div class="form-group width-50" style="margin-right: 5px;">
                    <label for="phone">موبايل</label>
                    <input type="text" name="phone" id="phone" class="form-control" required placeholder="" value="{{ old('phone') }}">
                </div>
            </div>
            <div style="display: flex;">
                <div class="form-group width-50" style="margin-left: 5px;">
                    <label for="sector_id">قطاع</label>
                    <select name="sector_id" id="sector_id" class="form-control" required>
                        <option value=""></option>
                        @foreach($sectors as $key => $value)
                            <option value="{{ $key }}" {{ old('sector_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group width-50" style="margin-right: 5px;">
                    <label for="province_id">إقليم</label>
                    <input type="text" name="province_id" id="province_id" class="form-control" readonly value="{{ old('province_id') }}">
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
                <label for="description">الوصف</label>
                <textarea name="description" id="description" class="form-control" required placeholder="">{{ old('description') }}</textarea>
            </div>
            <input name="points" id="points" type="hidden"/>
            <div class="form-group">
                <label for="point">الخريطة</label>
                <div id="map"></div>
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
    initMap();
    document.querySelector('#sector_id').addEventListener('input', function(e){
        const sector = sectors.find(sector => sector.id === parseInt(e.target.value));
        document.querySelector('#province_id').value = sector.province.name;
    })
</script>
<style>
    #map {
        width: 100%;
        height: 300px;
    }
</style>

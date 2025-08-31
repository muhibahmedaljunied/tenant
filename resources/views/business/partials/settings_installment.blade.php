<div class="pos-tab-content">
     <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="composite_fine">{{ __('نسبة الغرامة المركبة للتأخير') . ':*' }}</label>
                <div class="input-group">
                <span class="input-group-addon">
                    <i class="fas fa-dollar-sign"></i>
                </span>
                <input type="number" name="composite_fine" id="composite_fine" class="form-control" value="{{ $business->composite_fine }}" required>
                <span class="input-group-addon">
                    <i class="fa fa-percent"></i>
                </span>
                </div>
            </div>
        </div>
    </div>
</div>
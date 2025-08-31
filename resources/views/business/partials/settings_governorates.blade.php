<div class="pos-tab-content">
     <div class="row">
        <div class="col-sm-4">
            <form action="" method="POST">
                @csrf
                <div class="form-group">
                    <label for="stock_expiry_alert_days">{{ __('مناطق الشحن') . ':*' }}</label>
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-calendar-times"></i>
                    </span>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <span class="input-group-addon">
                        @lang('business.days')
                    </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="tab-pane 
    @if(!empty($view_type) &&  $view_type == 'subscriptions')
        active
    @else
        ''
    @endif"
id="subscriptions_tab">

    <div class="row">
        <div class="col-md-12">
            @component('components.widget')
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="subscriptions_filter_date_range">{{ __('report.date_range') }}:</label>
                        <input type="text" name="subscriptions_filter_date_range" id="subscriptions_filter_date_range" placeholder="{{ __('lang_v1.select_a_date_range') }}" class="form-control" readonly>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('sale_pos.partials.subscriptions_table')
        </div>
    </div>
</div>
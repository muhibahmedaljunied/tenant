<div class="pos-tab-content">
    <div class="row">
        <div class="col-sm-12">
            <h4>@lang('zatca.settings_title'):</h4>
        </div>
    <div class="col-md-6">
    <div class="form-group">
        <label for="zatca_phase">@lang('zatca.phase'):</label>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
            </span>
            <select name="zatca_phase" id="zatca_phase" class="form-control select2" style="width:100%;" required>
                <option value="" disabled selected>@lang('zatca.phase_placeholder')</option>
                <option value="phase_1" {{ ($business->zatca_phase ?? 'phase_1') == 'phase_1' ? 'selected' : '' }}>
                    @lang('zatca.phase_1')
                </option>
                <option value="phase_2" {{ ($business->zatca_phase ?? 'phase_1') == 'phase_2' ? 'selected' : '' }}>
                    @lang('zatca.phase_2')
                </option>
            </select>
        </div>
    </div>
</div>

        <hr>
        <div class="col-12">
            <div class="zatca_inputs">
                @include('business.partials.zatca_form_settings')
            </div>
        </div>
    </div>
</div>

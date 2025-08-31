<div class="modal-dialog" role="document">
    <div class="modal-content">

   <form action="{{ action('TypesOfServiceController@store') }}" method="post" id="types_of_service_form">



        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('lang_v1.add_type_of_service')</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="name">@lang('tax_rate.name'):</label>
                    <input type="text" name="name" id="name" class="form-control" required
                        placeholder="@lang('tax_rate.name')">
                </div>


                <div class="form-group col-md-12">
                    <label for="description">@lang('lang_v1.description'):</label>
                    <textarea name="description" id="description" class="form-control" placeholder="@lang('lang_v1.description')" rows="3"></textarea>
                </div>



                <div class="form-group col-md-12">
                    <table class="table table-slim">
                        <thead>
                            <tr>
                                <th>@lang('sale.location')</th>
                                <th>@lang('lang_v1.price_group')</th>
                            </tr>
                            @foreach ($locations as $key => $value)
                                <tr>
                                    <td>{{ $value }}</td>
                                    <td>
                                        <select name="location_price_group[{{ $key }}]"
                                            class="form-control input-sm select2" style="width: 100%;">
                                            @foreach ($price_groups as $group_key => $group_value)
                                                <option value="{{ $group_key }}">{{ $group_value }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </thead>

                    </table>
                </div>
                <div class="form-group col-md-6">
                    <label for="packing_charge_type">@lang('lang_v1.packing_charge_type'):</label>
                    <select name="packing_charge_type" id="packing_charge_type" class="form-control">
                        <option value="fixed">@lang('lang_v1.fixed')</option>
                        <option value="percent">@lang('lang_v1.percentage')</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="packing_charge">@lang('lang_v1.packing_charge'):</label>
                    <input type="text" name="packing_charge" id="packing_charge" class="form-control input_number"
                        placeholder="@lang('lang_v1.packing_charge')">
                </div>

                <div class="form-group col-md-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="enable_custom_fields" value="1"> @lang('lang_v1.enable_custom_fields')
                        </label>
                        @show_tooltip(__('lang_v1.types_of_service_custom_field_help'))
                    </div>
                </div>

            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        </form>

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

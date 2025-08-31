<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ action('\\Modules\\Repair\\Http\\Controllers\\DeviceModelController@store') }}" method="POST" id="device_model">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    @lang('repair::lang.add_device_model')
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       <div class="form-group">
                            <label for="name">{{ __('repair::lang.model_name') }}:*</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                       <div class="form-group">
                            <label for="model_brand_id">{{ __('product.brand') }}:</label>
                            <select name="brand_id" id="model_brand_id" class="form-control select2" style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($brands as $id => $brand)
                                    <option value="{{ $id }}">{{ $brand }}</option>
                                @endforeach
                            </select>
                       </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                            <label for="model_device_id">{{ __('repair::lang.device') }}:</label>
                            <select name="device_id" id="model_device_id" class="form-control select2" style="width: 100%;">
                                <option value="">{{ __('messages.please_select') }}</option>
                                @foreach($devices as $id => $device)
                                    <option value="{{ $id }}">{{ $device }}</option>
                                @endforeach
                            </select>
                       </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="repair_checklist">{{ __('repair::lang.repair_checklist') }}:</label> @show_tooltip(__('repair::lang.repair_checklist_tooltip'))
                            <textarea name="repair_checklist" id="repair_checklist" class="form-control" rows="3">الكاميرا ,البلوتوث, وايفاي ,البطارية ,الشاشة, تاتش ,الشحن,سوفت وير , بصمة ,الشبكة, بيت الخط بيت, الكرت المومري	</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('messages.close')
                </button>
                <button type="submit" class="btn btn-primary">
                    @lang('messages.save')
                </button>
            </div>
        </form>
    </div>
</div>
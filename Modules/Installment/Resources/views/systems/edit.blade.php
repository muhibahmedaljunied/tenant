<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('installmentSystem-update', ['system'=>$data->id]) }}" method="POST" id="edit_installment_system">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> تعديل بيانات نظام تقسيط</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">إسم النظام :*</label>
                    <input type="text" name="name" id="name" value="{{ $data->name }}" class="form-control" required placeholder="الإسم">
                </div>
                <div class="form-group">
                    <label for="number"> عدد الأقساط :*</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="number" id="number" value="{{ $data->number }}" class="form-control integr" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="period"> فترة السداد كل:</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="period" id="period" value="{{ $data->period }}" class="form-control integr" required>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" name="type">
                                <option value="day" @if($data->type =='day') selected @endif> @lang('installment::lang.day')</option>
                                <option value="month" @if($data->type =='month') selected @endif >@lang('installment::lang.month')</option>
                                <option value="year" @if($data->type =='year') selected @endif >@lang('installment::lang.year')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="benefit"> نسبة الفائدة %:</label>
                            <input type="text" name="benefit" id="benefit" value="{{ $data->benefit }}" class="form-control decimal" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="benefit_type">نوع الفائدة :</label>
                            <select class="form-control" name="benefit_type" id="benefit_type">
                                <option value="simple" @if($data->benefit_type =='simple') selected @endif  >@lang('installment::lang.simple')</option>
                                <option value="complex" @if($data->benefit_type =='complex') selected @endif >@lang('installment::lang.complex')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="latfines">غرامة التأخير % :</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="latfines" id="latfines" value="{{ $data->latfines }}" class="form-control decimal" required>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" name="latfinestype">
                                <option value="day" @if($data->latfinestype =='day') selected @endif> @lang('installment::lang.day')</option>
                                <option value="month" @if($data->latfinestype =='month') selected @endif >@lang('installment::lang.month')</option>
                                <option value="year" @if($data->latfinestype =='year') selected @endif >@lang('installment::lang.year')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">الوصف  :</label>
                    <input type="text" name="description" id="description" value="{{ $data->description }}" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >@lang( 'messages.save' )</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>

</script>
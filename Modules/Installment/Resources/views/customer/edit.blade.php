<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="{{ route('installmentSystem-update', ['id'=>$data->id]) }}" method="post" id="edit_installment_system">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> تعديل بيانات نظام تقسيط</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">إسم النظام :*</label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="الإسم" value="{{ old('name', $data->name) }}">
                </div>
                <div class="form-group">
                    <label for="number"> عدد الأقساط :*</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="number" id="number" class="form-control" required value="{{ old('number', $data->number) }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="period"> فترة السداد كل:</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="period" id="period" class="form-control" required value="{{ old('period', $data->period) }}">
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control" name="type" id="type">
                                <option value="day" {{ old('type', $data->type) == 'day' ? 'selected' : '' }}>@lang('installment::lang.day')</option>
                                <option value="month" {{ old('type', $data->type) == 'month' ? 'selected' : '' }}>@lang('installment::lang.month')</option>
                                <option value="year" {{ old('type', $data->type) == 'year' ? 'selected' : '' }}>@lang('installment::lang.year')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                   <div class="row">
                        <div class="col-lg-4">
                            <label for="benefit"> نسبة الفائدة %:</label>
                            <input type="text" name="benefit" id="benefit" class="form-control" required value="{{ old('benefit', $data->benefit) }}">
                        </div>
                        <div class="col-lg-4">
                            <label for="benefit_type">نوع الفائدة :</label>
                            <select class="form-control" name="benefit_type" id="benefit_type">
                                <option value="simple" {{ old('benefit_type', $data->benefit_type) == 'simple' ? 'selected' : '' }}>@lang('installment::lang.simple')</option>
                                <option value="complex" {{ old('benefit_type', $data->benefit_type) == 'complex' ? 'selected' : '' }}>@lang('installment::lang.complex')</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">الوصف  :</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $data->description) }}">
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

</script>
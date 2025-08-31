<div class="modal-dialog" role="document">
    <div class="modal-content">

        <form action="{{ action('\Modules\Installment\Http\Controllers\InstallmentSystemController@store') }}"
            method="POST"
            id="add_installment_system">
            @csrf

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">إضافة نظام تقسيط</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name">إسم النظام :*</label>
                    <input type="text" name="name" class="form-control" required placeholder="الإسم">
                </div>

                <div class="form-group">
                    <label for="number">عدد الأقساط :*</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="number" class="form-control integr" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="period">معدل السداد:</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="period" class="form-control integr" required>
                        </div>
                        <div class="col-lg-6">
                            <select name="type" class="form-control">
                                <option value="day">{{ __('installment::lang.day') }}</option>
                                <option value="month">{{ __('installment::lang.month') }}</option>
                                <option value="year">{{ __('installment::lang.year') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="benefit">نسبة الفائدة %:</label>
                            <input type="text" name="benefit" class="form-control" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="benefit_type">نوع الفائدة :</label>
                            <select name="benefit_type" class="form-control">
                                <option value="simple">{{ __('installment::lang.simple') }}</option>
                                <option value="complex">{{ __('installment::lang.complex') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="latfines">غرامة التأخير % :</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="text" name="latfines" class="form-control decimal" required>
                        </div>
                        <div class="col-lg-6">
                            <select name="latfinestype" class="form-control">
                                <option value="day">{{ __('installment::lang.day') }}</option>
                                <option value="month">{{ __('installment::lang.month') }}</option>
                                <option value="year">{{ __('installment::lang.year') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">الوصف :</label>
                    <input type="text" name="description" class="form-control">
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
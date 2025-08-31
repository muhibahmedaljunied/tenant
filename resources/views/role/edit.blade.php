@extends('layouts.app')
@if ($role->id)
    @section('title', __('role.edit_role'))
@else
    @section('title', __('role.add_role'))
@endif
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        @if ($role->id)
            <h1>@lang('role.edit_role')</h1>
        @else
            <h1>@lang('role.add_role')</h1>
        @endif

    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary'])
       @if ($role->id)
    <form action="{{ action('RoleController@update', [$role->id]) }}" id="role_form" method="POST">
        <input name="_method" type="hidden" value="PUT">
@else
    <form action="{{ action('RoleController@store') }}" id="role_add_form" method="POST">
        <input name="_method" type="hidden" value="post">
@endif


            <div class="row">
              <div class="col-md-4">
    <div class="form-group">
        <label for="name">@lang('user.role_name'):</label>
        <input type="text" name="name" id="name" class="form-control" required placeholder="@lang('user.role_name')" value="{{ str_replace('#' . auth()->user()->business_id, '', $role->name) }}">
    </div>
</div>

            </div>
            @if (in_array('service_staff', $enabled_modules))
              <div class="row">
    <div class="col-md-2">
        <h4>@lang('lang_v1.user_type')</h4>
    </div>
    <div class="col-md-9 col-md-offset-1">
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_service_staff" value="1" class="input-icheck" {{ $role->is_service_staff ? 'checked' : '' }}>
                    @lang('restaurant.service_staff')
                </label>
                @show_tooltip(__('restaurant.tooltip_service_staff'))
            </div>
        </div>
    </div>
</div>

            @endif
            <div class="row">
                <div class="col-md-3">
                    <label>@lang('user.permissions'):</label>
                </div>
            </div>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.user')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
              <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="user.view" class="input-icheck" {{ in_array('user.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.user.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="user.create" class="input-icheck" {{ in_array('user.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.user.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="user.update" class="input-icheck" {{ in_array('user.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.user.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="user.delete" class="input-icheck" {{ in_array('user.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.user.delete')
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('user.roles')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="roles.view" class="input-icheck" {{ in_array('roles.view', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_role')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="roles.create" class="input-icheck" {{ in_array('roles.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.add_role')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="roles.update" class="input-icheck" {{ in_array('roles.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.edit_role')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="roles.delete" class="input-icheck" {{ in_array('roles.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.delete_role')
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.supplier')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
              <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="supplier.view" class="input-icheck" {{ in_array('supplier.view', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_all_supplier')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="supplier.view_own" class="input-icheck" {{ in_array('supplier.view_own', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_own_supplier')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="supplier.create" class="input-icheck" {{ in_array('supplier.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.supplier.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="supplier.update" class="input-icheck" {{ in_array('supplier.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.supplier.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="supplier.delete" class="input-icheck" {{ in_array('supplier.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.supplier.delete')
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.customer')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
            <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="customer.view" class="input-icheck" {{ in_array('customer.view', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_all_customer')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="customer.view_own" class="input-icheck" {{ in_array('customer.view_own', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_own_customer')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="customer.create" class="input-icheck" {{ in_array('customer.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.customer.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="customer.update" class="input-icheck" {{ in_array('customer.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.customer.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="customer.delete" class="input-icheck" {{ in_array('customer.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.customer.delete')
        </label>
    </div>
</div>

                </div>
            </div>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.zatca.settings')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">

              <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="zatca.settings.index" class="input-icheck" {{ in_array('zatca.settings.index', $role_permissions) ? 'checked' : '' }}>
            عرض
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="zatca.settings.create" class="input-icheck" {{ in_array('zatca.settings.create', $role_permissions) ? 'checked' : '' }}>
            اضافة
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="zatca.settings.edit" class="input-icheck" {{ in_array('zatca.settings.edit', $role_permissions) ? 'checked' : '' }}>
            تعديل
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="zatca.settings.delete" class="input-icheck" {{ in_array('zatca.settings.delete', $role_permissions) ? 'checked' : '' }}>
            حذف
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('business.product')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                  <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="product.view" class="input-icheck" {{ in_array('product.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.product.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="product.create" class="input-icheck" {{ in_array('product.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.product.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="product.update" class="input-icheck" {{ in_array('product.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.product.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="product.delete" class="input-icheck" {{ in_array('product.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.product.delete')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="product.opening_stock" class="input-icheck" {{ in_array('product.opening_stock', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.add_opening_stock')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stcok_compares" class="input-icheck" {{ in_array('stcok_compares', $role_permissions) ? 'checked' : '' }}>
            مقارنة المخازن
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="view_purchase_price" class="input-icheck" {{ in_array('view_purchase_price', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_purchase_price')
        </label>
        @show_tooltip(__('lang_v1.view_purchase_price_tooltip'))
    </div>
</div>

                </div>
            </div>

            @if (in_array('purchases', $enabled_modules) || in_array('stock_adjustment', $enabled_modules))
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>@lang('role.purchase')</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">


                 <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.view" class="input-icheck" {{ in_array('purchase.view', $role_permissions) ? 'checked' : '' }}>
            عرض المشتريات
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.porduct_qty_setting" class="input-icheck" {{ in_array('purchase.porduct_qty_setting', $role_permissions) ? 'checked' : '' }}>
            ضبط كمية المنتجات والمخزون الهالك
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.create" class="input-icheck" {{ in_array('purchase.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.purchase.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.edit_composeit_discount" class="input-icheck" {{ in_array('purchase.edit_composeit_discount', $role_permissions) ? 'checked' : '' }}>
            تعديل قيم الخصم المركب اثناء الشراء
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.update" class="input-icheck" {{ in_array('purchase.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.purchase.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.delete" class="input-icheck" {{ in_array('purchase.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.purchase.delete')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.payments" class="input-icheck" {{ in_array('purchase.payments', $role_permissions) ? 'checked' : '' }}>
            اضافة دفع شراء
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase_payment.edit" class="input-icheck" {{ in_array('purchase_payment.edit', $role_permissions) ? 'checked' : '' }}>
            تعديل دفع الشراء
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase_payment.delete" class="input-icheck" {{ in_array('purchase_payment.delete', $role_permissions) ? 'checked' : '' }}>
            حذف دفع الشراء
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase.update_status" class="input-icheck" {{ in_array('purchase.update_status', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.update_status')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="view_own_purchase" class="input-icheck" {{ in_array('view_own_purchase', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_own_purchase')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase_return.view" class="input-icheck" {{ in_array('purchase_return.view', $role_permissions) ? 'checked' : '' }}>
            مرتجع المشتريات
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase_return.create" class="input-icheck" {{ in_array('purchase_return.create', $role_permissions) ? 'checked' : '' }}>
            اضافة مرتجع المشتريات
        </label>
    </div>
</div>

                    </div>
                </div>

                <!-----------------for stocktransfer -------------------->
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>تحويلات المخازن </h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stock_transfer" class="input-icheck" {{ in_array('stock_transfer', $role_permissions) ? 'checked' : '' }}>
            عرض تحويلات المخازن
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stock_transfer.create_pending" class="input-icheck" {{ in_array('stock_transfer.create_pending', $role_permissions) ? 'checked' : '' }}>
            انشاء وتعديل تحويل مخازن -طلب -
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stock_transfer.create_confirmed" class="input-icheck" {{ in_array('stock_transfer.create_confirmed', $role_permissions) ? 'checked' : '' }}>
            انشاء وتعديل تحويل مخازن - تأكيد-
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stock_transfer.create_in_transit" class="input-icheck" {{ in_array('stock_transfer.create_in_transit', $role_permissions) ? 'checked' : '' }}>
            انشاء وتعديل تحويل مخازن - تسليم -
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stock_transfer.create_completed" class="input-icheck" {{ in_array('stock_transfer.create_completed', $role_permissions) ? 'checked' : '' }}>
            انشاء وتعديل تحويل مخازن - استلام --
        </label>
    </div>
</div>


                    </div>
                </div>

                <!-----------------for stocktacking -------------------->
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>جرد المخازن</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
                    <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.view" class="input-icheck" {{ in_array('stocktacking.view', $role_permissions) ? 'checked' : '' }}>
            اظهار صفحة الجرد
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.show_qty_available" class="input-icheck" {{ in_array('stocktacking.show_qty_available', $role_permissions) ? 'checked' : '' }}>
            اظهار الكمية الحالية أثناء الجرد
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.create" class="input-icheck" {{ in_array('stocktacking.create', $role_permissions) ? 'checked' : '' }}>
            انشاء جرد
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.changeStatus" class="input-icheck" {{ in_array('stocktacking.changeStatus', $role_permissions) ? 'checked' : '' }}>
            تغير حالة الجرد
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.products" class="input-icheck" {{ in_array('stocktacking.products', $role_permissions) ? 'checked' : '' }}>
            جرد المنتجات
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.delete_form_stocktacking" class="input-icheck" {{ in_array('stocktacking.delete_form_stocktacking', $role_permissions) ? 'checked' : '' }}>
            حذف منتج من الجرد
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.report" class="input-icheck" {{ in_array('stocktacking.report', $role_permissions) ? 'checked' : '' }}>
            عرض تقارير المنتجات المجرودة
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stocktacking.liquidation" class="input-icheck" {{ in_array('stocktacking.liquidation', $role_permissions) ? 'checked' : '' }}>
            عمل تصفية
        </label>
    </div>
</div>



                    </div>
                </div>
            @endif

            {{-- Sells Permissions --}}
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('sale.sale')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>

                <div class="col-md-9">
                   <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.print_invoice" class="input-icheck" {{ in_array('sales.print_invoice', $role_permissions) ? 'checked' : '' }}>
            طباعة الفاتورة
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.pos_meswada" class="input-icheck" {{ in_array('sales.pos_meswada', $role_permissions) ? 'checked' : '' }}>
            عمل مسودة
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.edit_composite_discount" class="input-icheck" {{ in_array('sales.edit_composite_discount', $role_permissions) ? 'checked' : '' }}>
            تعديل الخصم المركب في نقطة البيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.price_offer" class="input-icheck" {{ in_array('sales.price_offer', $role_permissions) ? 'checked' : '' }}>
            عمل عرض سعر
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.puse_sell" class="input-icheck" {{ in_array('sales.puse_sell', $role_permissions) ? 'checked' : '' }}>
            تعليق عملية بيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.puse_show" class="input-icheck" {{ in_array('sales.puse_show', $role_permissions) ? 'checked' : '' }}>
            عرض عمليات البيع المعلقة
        </label>
    </div>
</div>

{{-- --------------------------------- --}}
            <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.sell_agel" class="input-icheck" {{ in_array('sales.sell_agel', $role_permissions) ? 'checked' : '' }}>
            بيع اجل
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.pay_card" class="input-icheck" {{ in_array('sales.pay_card', $role_permissions) ? 'checked' : '' }}>
            بطاقة دفع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.multi_pay_ways" class="input-icheck" {{ in_array('sales.multi_pay_ways', $role_permissions) ? 'checked' : '' }}>
            طرق تحصيل متعددة
        </label>
    </div>
</div>

{{-- --------------------------------- --}}

                  <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.sell_in_cash" class="input-icheck" {{ in_array('sales.sell_in_cash', $role_permissions) ? 'checked' : '' }}>
            بيع كاش
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.less_than_purchase_price" class="input-icheck" {{ in_array('sales.less_than_purchase_price', $role_permissions) ? 'checked' : '' }}>
            البيع باقل من سعر الشراء
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.show" class="input-icheck" {{ in_array('sales.show', $role_permissions) ? 'checked' : '' }}>
            عرض قائمة المبيعات
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.show_current_stock_in_pos" class="input-icheck" {{ in_array('sales.show_current_stock_in_pos', $role_permissions) ? 'checked' : '' }}>
            عرض المخزون الحالي في نقطة البيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales.show_purchase_price_in_pos" class="input-icheck" {{ in_array('sales.show_purchase_price_in_pos', $role_permissions) ? 'checked' : '' }}>
            عرض سعر الشراء في نقطة البيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="today_sells_total.show" class="input-icheck" {{ in_array('today_sells_total.show', $role_permissions) ? 'checked' : '' }}>
            عرض الاجمالي اليومي
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell.view" class="input-icheck" {{ in_array('sell.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.sell.view')
        </label>
    </div>
</div>

                    {{-- --------------------------------- --}}
                  <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell.installment" class="input-icheck" {{ in_array('sell.installment', $role_permissions) ? 'checked' : '' }}>
            جميع الاقساط
        </label>
    </div>
</div>

@if (in_array('pos_sale', $enabled_modules))
<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell.create" class="input-icheck" {{ in_array('sell.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.sell.create')
        </label>
    </div>
</div>
@endif

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell.update" class="input-icheck" {{ in_array('sell.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.sell.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell.delete" class="input-icheck" {{ in_array('sell.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.sell.delete')
        </label>
    </div>
</div>

@if (in_array('add_sale', $enabled_modules))
<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="direct_sell.access" class="input-icheck" {{ in_array('direct_sell.access', $role_permissions) ? 'checked' : '' }}>
            @lang('role.direct_sell.access')
        </label>
    </div>
</div>
@endif

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="list_drafts" class="input-icheck" {{ in_array('list_drafts', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.list_drafts')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="list_quotations" class="input-icheck" {{ in_array('list_quotations', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.list_quotations')
        </label>
    </div>
</div>

                    {{-- --------------------------------- --}}
               <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="view_own_sell_only" class="input-icheck" {{ in_array('view_own_sell_only', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_own_sell_only')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell.payments" class="input-icheck" {{ in_array('sell.payments', $role_permissions) ? 'checked' : '' }}>
            اضافة دفع بيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell_payment.edit" class="input-icheck" {{ in_array('sell_payment.edit', $role_permissions) ? 'checked' : '' }}>
            تعديل دفع البيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell_payment.delete" class="input-icheck" {{ in_array('sell_payment.delete', $role_permissions) ? 'checked' : '' }}>
            حذف دفع البيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="edit_product_price_from_sale_screen" class="input-icheck" {{ in_array('edit_product_price_from_sale_screen', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.edit_product_price_from_sale_screen')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="edit_product_price_from_pos_screen" class="input-icheck" {{ in_array('edit_product_price_from_pos_screen', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.edit_product_price_from_pos_screen')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="edit_product_discount_from_sale_screen" class="input-icheck" {{ in_array('edit_product_discount_from_sale_screen', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.edit_product_discount_from_sale_screen')
        </label>
    </div>
</div>

                    {{-- --------------------------------- --}}
               <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="edit_product_discount_from_pos_screen" class="input-icheck" {{ in_array('edit_product_discount_from_pos_screen', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.edit_product_discount_from_pos_screen')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="discount.access" class="input-icheck" {{ in_array('discount.access', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.discount.access')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="access_shipping" class="input-icheck" {{ in_array('access_shipping', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.access_shipping')
        </label>
    </div>
</div>

@if (in_array('types_of_service', $enabled_modules))
<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="access_types_of_service" class="input-icheck" {{ in_array('access_types_of_service', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.access_types_of_service')
        </label>
    </div>
</div>
@endif

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="access_sell_return" class="input-icheck" {{ in_array('access_sell_return', $role_permissions) ? 'checked' : '' }}>
            مرتجع المبيعات
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="importsales.create" class="input-icheck" {{ in_array('importsales.create', $role_permissions) ? 'checked' : '' }}>
            استيراد مبيعات من نظام اخر
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="recent_transaction.view" class="input-icheck" {{ in_array('recent_transaction.view', $role_permissions) ? 'checked' : '' }}>
            اخر المعاملات في شاشة نقاط البيع
        </label>
    </div>
</div>

                    {{-- --------------------------------- --}}
               <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="customer_balance_due_in_pos" class="input-icheck" {{ in_array('customer_balance_due_in_pos', $role_permissions) ? 'checked' : '' }}>
            عرض الرصيد المستحق علي العميل في شاشة نقاط البيع
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="pos_lite" class="input-icheck" {{ in_array('pos_lite', $role_permissions) ? 'checked' : '' }}>
            نقطة بيع لايت
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="pos_repair" class="input-icheck" {{ in_array('pos_repair', $role_permissions) ? 'checked' : '' }}>
            نقطة بيع الصيانة
        </label>
    </div>
</div>



                </div>
            </div>


            {{-- Expenses --}}
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.expenses')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="expenses.view" class="input-icheck" {{ in_array('expenses.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.expenses.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="expense.categories" class="input-icheck" {{ in_array('expenses.categories', $role_permissions) ? 'checked' : '' }}>
            @lang('role.expenses.categories')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="expense.create" class="input-icheck" {{ in_array('expenses.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.expenses.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="expense.edit" class="input-icheck" {{ in_array('expenses.edit', $role_permissions) ? 'checked' : '' }}>
            @lang('role.expenses.edit')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="expense.delete" class="input-icheck" {{ in_array('expenses.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.expenses.delete')
        </label>
    </div>
</div>

                </div>
            </div>


            {{-- End of Expense --}}





            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('cash_register.cash_register')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="view_cash_register" class="input-icheck" {{ in_array('view_cash_register', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_cash_register')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="close_cash_register" class="input-icheck" {{ in_array('close_cash_register', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.close_cash_register')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="register_payment_details.view" class="input-icheck" {{ in_array('register_payment_details.view', $role_permissions) ? 'checked' : '' }}>
            عرض تفاصيل الدفعات في كشف الوردية
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="register_product_details.view" class="input-icheck" {{ in_array('register_product_details.view', $role_permissions) ? 'checked' : '' }}>
            عرض تفاصيل المنتجات المباعة في كشف الوردية
        </label>
    </div>
</div>

                </div>
            </div>


            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.brand')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
            <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="brand.view" class="input-icheck" {{ in_array('brand.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.brand.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="brand.create" class="input-icheck" {{ in_array('brand.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.brand.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="brand.update" class="input-icheck" {{ in_array('brand.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.brand.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="brand.delete" class="input-icheck" {{ in_array('brand.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.brand.delete')
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.tax_rate')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
             <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="tax_rate.view" class="input-icheck" {{ in_array('tax_rate.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.tax_rate.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="tax_rate.create" class="input-icheck" {{ in_array('tax_rate.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.tax_rate.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="tax_rate.update" class="input-icheck" {{ in_array('tax_rate.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.tax_rate.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="tax_rate.delete" class="input-icheck" {{ in_array('tax_rate.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.tax_rate.delete')
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.unit')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
             <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="unit.view" class="input-icheck" {{ in_array('unit.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.unit.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="unit.create" class="input-icheck" {{ in_array('unit.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.unit.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="unit.update" class="input-icheck" {{ in_array('unit.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.unit.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="unit.delete" class="input-icheck" {{ in_array('unit.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.unit.delete')
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('category.category')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
               <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="category.view" class="input-icheck" {{ in_array('category.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.category.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="category.create" class="input-icheck" {{ in_array('category.create', $role_permissions) ? 'checked' : '' }}>
            @lang('role.category.create')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="category.update" class="input-icheck" {{ in_array('category.update', $role_permissions) ? 'checked' : '' }}>
            @lang('role.category.update')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="category.delete" class="input-icheck" {{ in_array('category.delete', $role_permissions) ? 'checked' : '' }}>
            @lang('role.category.delete')
        </label>
    </div>
</div>

                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.report')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
             @if (in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules))
<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="purchase_n_sell_report.view" class="input-icheck" {{ in_array('purchase_n_sell_report.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.purchase_n_sell_report.view')
        </label>
    </div>
</div>
@endif

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="tax_report.view" class="input-icheck" {{ in_array('tax_report.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.tax_report.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="contacts_report.view" class="input-icheck" {{ in_array('contacts_report.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.contacts_report.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="profit_loss_report.view" class="input-icheck" {{ in_array('profit_loss_report.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.profit_loss_report.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stock_report.view" class="input-icheck" {{ in_array('stock_report.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.stock_report.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="stock_missing_report.view" class="input-icheck" {{ in_array('stock_missing_report.view', $role_permissions) ? 'checked' : '' }}>
            تقرير النواقص
        </label>
    </div>
</div>

{{-- ------------- --}}
               <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="trending_product_report.view" class="input-icheck" {{ in_array('trending_product_report.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.trending_product_report.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="register_report.view" class="input-icheck" {{ in_array('register_report.view', $role_permissions) ? 'checked' : '' }}>
            تقرير الوردية
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sales_representative.view" class="input-icheck" {{ in_array('sales_representative.view', $role_permissions) ? 'checked' : '' }}>
            @lang('role.sales_representative.view')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="view_product_stock_value" class="input-icheck" {{ in_array('view_product_stock_value', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.view_product_stock_value')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="less_trending_product_report.view" class="input-icheck" {{ in_array('less_trending_product_report.view', $role_permissions) ? 'checked' : '' }}>
            المنتجات الراكدة
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="sell_purchase_lines_report.view" class="input-icheck" {{ in_array('sell_purchase_lines_report.view', $role_permissions) ? 'checked' : '' }}>
            تقرير حركة صنف
        </label>
    </div>
</div>


                </div>
            </div>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.settings')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">

              <div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="business_settings.backup_database" class="input-icheck" {{ in_array('business_settings.backup_database', $role_permissions) ? 'checked' : '' }}>
            نسخ احتياطي
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="business_settings.access" class="input-icheck" {{ in_array('business_settings.access', $role_permissions) ? 'checked' : '' }}>
            @lang('role.business_settings.access')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="barcode_settings.access" class="input-icheck" {{ in_array('barcode_settings.access', $role_permissions) ? 'checked' : '' }}>
            @lang('role.barcode_settings.access')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="invoice_settings.access" class="input-icheck" {{ in_array('invoice_settings.access', $role_permissions) ? 'checked' : '' }}>
            @lang('role.invoice_settings.access')
        </label>
    </div>
</div>

<div class="col-md-12">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="permissions[]" value="access_printers" class="input-icheck" {{ in_array('access_printers', $role_permissions) ? 'checked' : '' }}>
            @lang('lang_v1.access_printers')
        </label>
    </div>
</div>

                </div>
            </div>

            <div>
                {{-- dashboard --}}
                {{-- <div class="row check_group">
                    <div class="col-md-3">
                        <h4>@lang( 'role.dashboard' )</h4>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'dashboard.data', in_array('dashboard.data', $role_permissions),
                                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.dashboard.data' ) }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- account.account --}}
          <div class="row check_group">
    <div class="col-md-3">
        <h4>@lang('account.account')</h4>
    </div>
    <div class="col-md-9">
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="account.access" class="input-icheck" {{ in_array('account.access', $role_permissions) ? 'checked' : '' }}>
                    @lang('lang_v1.access_accounts')
                </label>
            </div>
        </div>
    </div>
</div>

                <div class="row check_group">
                    <div class="col-md-3">
                        <h4>@lang('chart_of_accounts.general_accounts')</h4>
                    </div>
                    <div class="col-md-9">
               <div class='row check_group'>
    <div class="col-md-3">
        <h4>@lang('chart_of_accounts.master_tree')</h4>
    </div>
    <div class='col-md-9'>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="chartofaccounts.view" class="" {{ in_array('chartofaccounts.view', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.view')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="chartofaccounts.create" class="" {{ in_array('chartofaccounts.create', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.create')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="chartofaccounts.edit" class="" {{ in_array('chartofaccounts.edit', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.edit')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="chartofaccounts.delete" class="" {{ in_array('chartofaccounts.delete', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.delete')
                </label>
            </div>
        </div>
    </div>
</div>

<div class='row check_group'>
    <div class="col-md-3">
        <h4>@lang('chart_of_accounts.branch_cost_centers')</h4>
    </div>
    <div class='col-md-9'>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="branch_cost_center.view" class="" {{ in_array('branch_cost_center.view', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.branch__cost_center.view')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="branch_cost_center.create" class="" {{ in_array('branch_cost_center.create', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.branch__cost_center.create')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="branch_cost_center.edit" class="" {{ in_array('branch_cost_center.edit', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.branch__cost_center.edit')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="branch_cost_center.delete" class="" {{ in_array('branch_cost_center.delete', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.branch__cost_center.delete')
                </label>
            </div>
        </div>
    </div>
</div>

                      <div class='row check_group'>
    <div class="col-md-3">
        <h4>@lang('chart_of_accounts.extra_cost_centers')</h4>
    </div>
    <div class='col-md-9'>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="extra_cost_center.view" class="" {{ in_array('extra_cost_center.view', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.extra_cost_center.view')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="extra_cost_center.create" class="" {{ in_array('extra_cost_center.create', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.extra_cost_center.create')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="extra_cost_center.edit" class="" {{ in_array('extra_cost_center.edit', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.extra_cost_center.edit')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="extra_cost_center.delete" class="" {{ in_array('extra_cost_center.delete', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.extra_cost_center.delete')
                </label>
            </div>
        </div>
    </div>
</div>

                        {{-- ----- --}}
                     <div class='row check_group'>
    <div class="col-md-3">
        <h4>@lang('chart_of_accounts.journal_entries')</h4>
    </div>
    <div class='col-md-9'>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="journal_entry.view" class="" {{ in_array('journal_entry.view', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.journal_entry.view')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="journal_entry.create" class="" {{ in_array('journal_entry.create', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.journal_entry.create')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="journal_entry.edit" class="" {{ in_array('journal_entry.edit', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.journal_entry.edit')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="journal_entry.delete" class="" {{ in_array('journal_entry.delete', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.journal_entry.delete')
                </label>
            </div>
        </div>
    </div>
</div>

<div class='row check_group'>
    <div class="col-md-3">
        <h4>@lang('chart_of_accounts.others')</h4>
    </div>
    <div class='col-md-9'>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="journal_ledger.access" class="" {{ in_array('journal_ledger.access', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.other.access_journal_ledger')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="account_statement.access" class="" {{ in_array('account_statement.access', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.other.access_account_statement')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="trial_balance.access" class="" {{ in_array('trial_balance.access', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.other.access_trial_balance')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="income_statement.access" class="" {{ in_array('income_statement.access', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.other.access_income_statement')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="balance_sheet.access" class="" {{ in_array('balance_sheet.access', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.other.access_balance_sheet')
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="accounts_routing.access" class="" {{ in_array('accounts_routing.access', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.other.access_accounts_routing')
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="accounts_routing.update" class="" {{ in_array('accounts_routing.update', $role_permissions) ? 'checked' : '' }}>
                    @lang('chart_of_accounts.other.update_accounts_routing')
                </label>
            </div>
        </div>
    </div>
</div>

                    </div>
                </div>
            </div>


            @if (in_array('booking', $enabled_modules))
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>@lang('restaurant.bookings')</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                   <div class="col-md-9">
    <div class="col-md-12">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="permissions[]" value="crud_all_bookings" class="input-icheck" {{ in_array('crud_all_bookings', $role_permissions) ? 'checked' : '' }}>
                @lang('restaurant.add_edit_view_all_booking')
            </label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="permissions[]" value="crud_own_bookings" class="input-icheck" {{ in_array('crud_own_bookings', $role_permissions) ? 'checked' : '' }}>
                @lang('restaurant.add_edit_view_own_booking')
            </label>
        </div>
    </div>
</div>

                </div>
            @endif
            <div class="row check_group ">
                <div class="col-md-3">
                    <h4>@lang('lang_v1.access_selling_price_groups')</h4>
                </div>
           <div class="col-md-9">
    <div class="col-md-12">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="permissions[]" value="access_default_selling_price" class="input-icheck" {{ in_array('access_default_selling_price', $role_permissions) ? 'checked' : '' }}>
                @lang('lang_v1.default_selling_price')
            </label>
        </div>
    </div>
    @if (count($selling_price_groups) > 0)
        @foreach ($selling_price_groups as $selling_price_group)
            <div class="col-md-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="spg_permissions[]" value="selling_price_group.{{ $selling_price_group->id }}" class="input-icheck" {{ in_array('selling_price_group.' . $selling_price_group->id, $role_permissions) ? 'checked' : '' }}>
                        {{ $selling_price_group->name }}
                    </label>
                </div>
            </div>
        @endforeach
    @endif
</div>

            </div>

            {{-- Projects Setting --}}
            {{--  <div class="row">
                  <div class="col-md-3">
                      <h4> Projects Setting </h4>
                  </div>
                  <div class="col-md-9">
                      <div class="col-md-12">
                          <div class="checkbox">
                              <label>
                                  {!! Form::checkbox('permissions[]', 'projcts.show', in_array('projcts.show', $role_permissions),
                                  [ 'class' => 'input-icheck']); !!} عرض المشروعات
                              </label>
                          </div>
                      </div>
                  </div>

              </div>
              <div class="row">
                  <div class="col-md-3">
                      <h4> مديول كاتالوج </h4>
                  </div>
                  <div class="col-md-9">
                      <div class="col-md-12">
                          <div class="checkbox">
                              <label>
                                  {!! Form::checkbox('permissions[]', 'catalouge.show', in_array('catalouge.show', $role_permissions),
                                  [ 'class' => 'input-icheck']); !!} عرض الكتالوج
                              </label>
                          </div>
                      </div>
                  </div>

              </div> --}}
            {{-- Repair --}}
            {{--    <div class="row">
                    <div class="col-md-3">
                        <h4> مديول الصيانة </h4>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'repair_setting.view', in_array('repair_setting.view', $role_permissions),
                                    [ 'class' => 'input-icheck']); !!} الاعدادت
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'repair_device_model.create', in_array('repair_device_model.create', $role_permissions),
                                    [ 'class' => 'input-icheck']); !!} اضافة موديل جهاز
                                </label>
                            </div>
                        </div>
                    </div>
                </div> --}}

            {{-- HRM Setting --}}
            {{-- <div class="row check_group">
                 <div class="col-md-3">
                     <h4> HRM Setting </h4>
                 </div>
                 <div class="col-md-9">
                     <div class="col-md-12">
                         <div class="checkbox">
                             <label>
                                 {!! Form::checkbox('permissions[]', 'hrm_show', in_array('hrm_show', $role_permissions),
                                 [ 'class' => 'input-icheck']); !!} HRM
                             </label>
                         </div>
                     </div>
                 </div>

             </div> --}}
         @if (in_array('tables', $enabled_modules))
<div class="row">
    <div class="col-md-3">
        <h4>@lang('restaurant.restaurant')</h4>
    </div>
    <div class="col-md-9">
        <div class="col-md-12">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="permissions[]" value="access_tables" class="input-icheck" {{ in_array('access_tables', $role_permissions) ? 'checked' : '' }}>
                    @lang('lang_v1.access_tables')
                </label>
            </div>
        </div>
    </div>
</div>
@endif


            @include('role.partials.module_permissions')
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
                </div>
            </div>
</form>
        @endcomponent
    </section>
    <!-- /.content -->
@endsection

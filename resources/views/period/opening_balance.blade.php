@extends('layouts.app')
@section('title', __('journal_entry.opening_balance'))

@section('content')
    <section class="content-header">
        <h1>@lang('product.add_new_product')</h1>
    </section>
    <section class='content'>
        <div class='row'>
            <div class='col-sm-12'>
                <div class="add_subscription_form add-products-form">
                    <div class="subscribers add_subscription">
                        <h2>يرجى الاختيار</h2>
                    </div>
                    <div style="text-align:center;padding-top:30px; padding-bottom:30px">
                        <h3>اختر نوع القيد</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <a class="btn btn-default sw-btn-pick-option"
                                   href="{{ route('opening_balance.create', ['type' => 'accounts']) }}">
                                    <i>
                                    <svg width='72' height='72' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <defs><style>.fa-secondary{opacity:.4}</style></defs>
                                        <path class="fa-primary" d="M400 96C426.5 96 448 117.5 448 144V432C448 458.5 426.5 480 400 480H368C341.5 480 320 458.5 320 432V144C320 117.5 341.5 96 368 96H400zM80 224C106.5 224 128 245.5 128 272V432C128 458.5 106.5 480 80 480H48C21.49 480 0 458.5 0 432V272C0 245.5 21.49 224 48 224H80z"/><path class="fa-secondary" d="M160 80C160 53.49 181.5 32 208 32H240C266.5 32 288 53.49 288 80V432C288 458.5 266.5 480 240 480H208C181.5 480 160 458.5 160 432V80z"/></svg>
                                    </i>
                                    <br><br>الحسابات
                                </a> <a class="btn btn-default sw-btn-pick-option"
                                        href="{{ route('opening_balance.create', ['type' => 'products']) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" height='72' width='72'><defs><style>.fa-secondary{opacity:.4}</style></defs><path class="fa-primary" d="M160 64C160 28.65 188.7 0 224 0C259.3 0 288 28.65 288 64C288 99.35 259.3 128 224 128C188.7 128 160 99.35 160 64zM512 128C512 92.65 540.7 64 576 64C611.3 64 640 92.65 640 128C640 163.3 611.3 192 576 192C540.7 192 512 163.3 512 128zM640 448C640 483.3 611.3 512 576 512C540.7 512 512 483.3 512 448C512 412.7 540.7 384 576 384C611.3 384 640 412.7 640 448zM0 304C0 268.7 28.65 240 64 240C99.35 240 128 268.7 128 304C128 339.3 99.35 368 64 368C28.65 368 0 339.3 0 304z"/><path class="fa-secondary" d="M305.6 163.2C315.4 161.1 325.6 159.1 336 159.1C372.1 159.1 405 173.3 430.3 195.2L512.4 134.1C514.9 158.1 529.7 177.6 550.2 186.6L468.2 246.8C475.8 264.3 480 283.7 480 303.1C480 319.6 477.5 334.7 472.9 348.8L544.9 392C525.6 402.8 512.4 423.3 512 446.9L439.9 403.7C413.7 430.1 376.8 448 336 448C267.5 448 210.1 400.1 195.6 336H119.4C124.9 326.6 128 315.7 128 304C128 292.3 124.9 281.4 119.4 271.1H195.6C203 239 221.8 210.4 247.6 190.3L218.4 127.8C220.3 127.9 222.1 128 224 128C245.7 128 264.9 117.2 276.4 100.7L305.6 163.2zM336 384C380.2 384 416 348.2 416 303.1C416 259.8 380.2 223.1 336 223.1C291.8 223.1 255.1 259.8 255.1 303.1C255.1 348.2 291.8 384 336 384z"/></svg>
                                    <br><br>المنتجات
                                    والتكاليف
                                </a></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <a class="btn btn-default sw-btn-pick-option"
                                   href="{{ route('opening_balance.create', ['type' => 'customers']) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width='72' height='72'><defs><style>.fa-secondary{opacity:.4}</style></defs><path class="fa-primary" d="M319.9 320c57.41 0 103.1-46.56 103.1-104c0-57.44-46.54-104-103.1-104c-57.41 0-103.1 46.56-103.1 104C215.9 273.4 262.5 320 319.9 320zM369.9 352H270.1C191.6 352 128 411.7 128 485.3C128 500.1 140.7 512 156.4 512h327.2C499.3 512 512 500.1 512 485.3C512 411.7 448.4 352 369.9 352z"/><path class="fa-secondary" d="M128 160c44.18 0 80-35.82 80-80S172.2 0 128 0C83.82 0 48 35.82 48 80S83.82 160 128 160zM512 160c44.18 0 80-35.82 80-80S556.2 0 512 0c-44.18 0-80 35.82-80 80S467.8 160 512 160zM551.9 192h-61.84c-12.8 0-24.88 3.037-35.86 8.24C454.8 205.5 455.8 210.6 455.8 216c0 33.71-12.78 64.21-33.16 88h199.7C632.1 304 640 295.6 640 285.3C640 233.8 600.6 192 551.9 192zM185.5 200.1C174.6 194.1 162.6 192 149.9 192H88.08C39.44 192 0 233.8 0 285.3C0 295.6 7.887 304 17.62 304h199.5c-20.38-23.79-33.16-54.29-33.16-88C183.9 210.6 184.9 205.4 185.5 200.1z"/></svg>
                                    <br><br>العملاء
                                </a> <a class="btn btn-default sw-btn-pick-option"
                                        href="{{ route('opening_balance.create', ['type' => 'vendors']) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width='72' height='72'><defs><style>.fa-secondary{opacity:.4}</style></defs><path class="fa-primary" d="M319.9 320c57.41 0 103.1-46.56 103.1-104c0-57.44-46.54-104-103.1-104c-57.41 0-103.1 46.56-103.1 104C215.9 273.4 262.5 320 319.9 320zM369.9 352H270.1C191.6 352 128 411.7 128 485.3C128 500.1 140.7 512 156.4 512h327.2C499.3 512 512 500.1 512 485.3C512 411.7 448.4 352 369.9 352z"/><path class="fa-secondary" d="M128 160c44.18 0 80-35.82 80-80S172.2 0 128 0C83.82 0 48 35.82 48 80S83.82 160 128 160zM512 160c44.18 0 80-35.82 80-80S556.2 0 512 0c-44.18 0-80 35.82-80 80S467.8 160 512 160zM551.9 192h-61.84c-12.8 0-24.88 3.037-35.86 8.24C454.8 205.5 455.8 210.6 455.8 216c0 33.71-12.78 64.21-33.16 88h199.7C632.1 304 640 295.6 640 285.3C640 233.8 600.6 192 551.9 192zM185.5 200.1C174.6 194.1 162.6 192 149.9 192H88.08C39.44 192 0 233.8 0 285.3C0 295.6 7.887 304 17.62 304h199.5c-20.38-23.79-33.16-54.29-33.16-88C183.9 210.6 184.9 205.4 185.5 200.1z"/></svg>
                                    <br><br>الموردين
                                </a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .add_subscription_form.add-products-form {
    background: white;
    min-height: 280px;
}

.add-products-form {
    box-shadow: 0px 0px 1px 1px #d3d3d3;
    border-radius: 4px;
    padding-bottom: 45px;
}
.add_subscription_form {
    background: white;
    margin-bottom: 50px;
}
        .add-products-form .add_subscription {
    border-top: 4px solid #149999;
    background-color: #706db1;
    border-bottom: 1px solid #14293C;
    padding: 12px 16px;
    border-top-right-radius: 4px;
    border-top-left-radius: 4px;
}

.add_subscription {
    border-bottom: 4px solid #a4dbc4;
    display: inline-block;
    width: 100%;
    padding-bottom: 8px;
    padding-top: 30px;
}
        .add-products-form .add_subscription h2 {
    float: right;
    width: 100%;
            color: #fff!important;
}
.add_subscription h2 {
    font-size: 1.7em;
    font-weight: 300;
    margin-top: 0px;
    margin-bottom: 0px;
    float: left;
}
.subscribers h2 {
    font-size: 1.688em;
    font-weight: 300;
}
.sw-btn-pick-option {
    margin: 30px;
    width: 140px;
    height: 140px;
}
.sw-btn-pick-option {
    margin: 30px;
    width: 140px;
    height: 140px;font-size: 18px;
}
    </style>
@endsection

<section>
    <div class="col-md-12">
        @if ($zatcaSetting->isZatcaConfigured())
            <p class="text-right d-flex">
                <a href="#" class="edit_zatca" data-toggle="modal" data-target=".zatca_settings_modal">
                    <i class="fa fa-edit"></i>
                    Edit
                </a>
                <br />
                <span>
                    <i class="fa fa-check-circle text-success"></i>
                    Connected With Zatca
                </span>
            </p>
        @else
            @include('zatca_setting.partials.form')
        @endif
    </div>
</section>

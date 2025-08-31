 {{-- @can('extra_cost_center.edit') --}}
 <a target="_blank" href="{{ action('Zatca\ZatcaSettingsController@edit', $zatca->id) }}"
     class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> @lang('messages.edit') </a>
 <button data-href="{{ action('Zatca\ZatcaSettingsController@show', $zatca->id) }}"
     class="btn btn-xs btn-success m-2 btn-modal" data-container=".zatca_show"><i class="fas fa-eye"></i> @lang('messages.view') </a>
 {{-- @endcan --}}

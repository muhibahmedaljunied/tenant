 @if(count($module_permissions) > 0)
  @php
    $module_role_permissions = [];
    if(!empty($role_permissions)) {
      $module_role_permissions = $role_permissions;
    }
  @endphp
  @foreach($module_permissions as $key => $value)

  <div class="row check_group">
    <div class="col-md-3">
      <h4>@lang(strtolower($key).'::lang.'.strtolower($key))</h4>
    </div>
    <div class="col-md-9">
      @foreach($value as $module_permission)
      @php
        if(empty($role_permissions) && $module_permission['default']) {
          $module_role_permissions[] = $module_permission['value'];
        }
      @endphp
      <div class="col-md-12">
<div class="checkbox">
    <label>
        <input type="checkbox" name="permissions[]" value="{{ $module_permission['value'] }}" class="input-icheck" {{ in_array($module_permission['value'], $module_role_permissions) ? 'checked' : '' }}>
        {{ $module_permission['label'] }}
    </label>
</div>

      </div>
      @endforeach
    </div>
  </div>
  @endforeach
@endif
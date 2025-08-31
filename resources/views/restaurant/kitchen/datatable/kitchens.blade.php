@foreach ($kitchens as $kitchen)
<tr id="{{ $kitchen->id }}">
    <td>{{ $kitchen->location }}</td>
    <td>{{ $kitchen->name }}</td>
    <td>{{ $kitchen->description }}</td>
    <td>{{ optional($kitchen->printer)->name }}</td>;
    <td>
        @can('kitchen.delete')
        <button onclick="edit({{ $kitchen->id }})" class="btn btn-xs btn-primary btn-modal"><i
                class="glyphicon glyphicon-edit"></i>@lang('messages.edit')</button>
        <button onclick="deleterow({{ $kitchen->id }})" class="btn btn-xs btn-danger "><i
                class="glyphicon glyphicon-trash"></i>@lang('messages.delete')</button>
        @endcan
    </td>
</tr>
@endforeach
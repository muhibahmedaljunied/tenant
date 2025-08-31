@if ($isOwner)
    @can('customer.update')
        @if ($dt->entry_type == 'daily')
            <a target="_blank" href="{{ action('AcJournalEntryController@edit', [$dt]) }} " class="btn btn-xs btn-primary"><i
                    class="fas fa-edit"></i> @lang('messages.edit') </a>
        @else
            <a target="_blank" href="{{ action('AcJournalEntryController@editOpenAccountEntry', [$dt]) }} "
                class="btn btn-xs btn-primary"><i class="fas fa-edit"></i> @lang('messages.edit') </a>
        @endif
    @endcan
@endif

@can('customer.view')
    <a target="_blank" href="{{ action('AcJournalEntryController@show', [$dt]) }}" class="btn btn-xs btn-info"><i
            class="fa fa-eye"></i> @lang('messages.view')</a>
    &nbsp;
@endcan

@can('customer.delete')
    <button data-href="{{ action('AcJournalEntryController@destroy', [$dt]) }}"
        class="btn btn-xs btn-danger delete_ac_journal_entry_button">
        <i class="glyphicon glyphicon-trash"></i> @lang('messages.delete')</button>
@endcan
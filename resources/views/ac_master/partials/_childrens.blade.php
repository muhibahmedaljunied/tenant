@foreach ($parents as $par)
    @php
        $max_account_children = optional(
            $accountMasters->where('parent_acct_no', $par->account_number)
            ->sortByDesc('account_number')
            ->first()
            )->account_number ?? null;
        $next_children =  "{$par->account_number}01";
        if (!empty($max_account_children)) {
            $next_children = $max_account_children + 1;
        }
        $level = $par->account_level + 1;

        $masterParents = $accountMasters->where('parent_acct_no', $par->account_number);
        
    @endphp
    @if ($masterParents->count())
        <li><span class='folder'><span class="tree_icon"></span>{{ $par->account_name_ar }}({{ $par->account_number }})</span>
            @can('chartofaccounts.create')
                <button class='btn btn-xs btn-info fa fa-plus'
                    onclick="addTree('{{ $par->account_number }}','{{$level}}','{{$next_children}}','{{$par->account_name_ar}}','{{$par->account_type}}')">
                </button>
            @endcan
            @can('chartofaccounts.edit')
                <a href="{{ route('master.edit', $par->id) }}" class='edit_master_button'> <i
                        class='btn btn-xs btn-primary glyphicon glyphicon-edit'></i></a>
            @endcan
            
            <ul style='display: none' class="chillld">
                @include('ac_master.partials._childrens', [
                    'parents' => $masterParents,
                    'accountMasters' => $accountMasters,
                ])

            </ul>
        </li>
    @else
        <li class='last_branch'>
            {{ $par->account_name_ar }}({{ $par->account_number }})
            @can('chartofaccounts.create')
                <button class='btn btn-xs btn-info  fa fa-plus'
                    onclick="addTree('{{ $par->account_number }}','{{$level}}','{{$next_children}}','{{$par->account_name_ar}}','{{$par->account_type}}')">
                </button>
            @endcan
            @can('chartofaccounts.edit')
                <a href="{{ route('master.edit', $par->id) }}" class='edit_master_button'> <i
                        class='btn btn-xs btn-primary glyphicon glyphicon-edit'></i></a>
            @endcan

            @can('chartofaccounts.delete')
                <button data-href="{{ route('master.destroy', $par->id) }}"
                    data-href_redirect="{{ route('master.index') }}"
                    class='delete_master_button btn btn-xs btn-danger glyphicon glyphicon-trash'> </button>
            @endcan
        </li>
    @endif
@endforeach

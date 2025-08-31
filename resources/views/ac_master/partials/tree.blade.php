@foreach ($accountMasters->filter(fn ($item) => empty($item->parent_acct_no)) as $par)
    @php
        $masterParents = $accountMasters->where('parent_acct_no', $par->account_number);
        $max_account_children1 = optional($accountMasters
            ->where('parent_acct_no', $par->account_number)
            ->sortByDesc('account_number')
            ->first())->account_number;

        $next_children1 = $par->account_number . '1';
        if (!empty($max_account_children1)) {
            $next_children1 = $max_account_children1 + 1;
        }
        $level = $par->account_level + 1;
    @endphp

    @if ($masterParents->count() > 0)
        <li>
            <span class='folder'>
                <i class="tree_icon"></i>{{ $par->account_name_ar }}({{ $par->account_number }})
            </span>
            @can('chartofaccounts.create')
                <button class='btn btn-xs btn-info fa fa-plus'
                    onclick="addTree('{{ $par->account_number }}','{{$level}}','{{$next_children1}}','{{$par->account_name_ar}}','{{$par->account_type}}')">
                </button>
            @endcan

            @can('chartofaccounts.edit')
                <a href="{{ route('master.edit', $par->id) }}" class='edit_master_button'> <i
                        class='btn btn-xs btn-primary glyphicon glyphicon-edit'></i></a>
            @endcan
            @if ($masterParents->count() > 0)
                <ul style='display: none'>
                    @include('ac_master.partials._childrens', [
                        'parents' => $masterParents,
                        'accountMasters' => $accountMasters,
                    ])
                </ul>
            @endif
        </li>
    @else
        <li class='last_branch'>{{ $par->account_name_ar }}({{ $par->account_number }})
            <button class='btn btn-xs btn-info fa fa-plus'
                onclick="addTree('{{ $par->account_number }}','{{$level}}','{{$next_children1}}','{{$par->account_name_ar}}','{{$par->account_type}}')">
            </button>
            <a href="{{ route('master.edit', $par->id) }}" class='edit_master_button'>
                <i class='btn btn-xs btn-primary glyphicon glyphicon-edit'></i>
            </a>
        </li>
    @endif
@endforeach

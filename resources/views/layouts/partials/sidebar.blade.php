<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">

        <a href="{{ route('home') }}" class="logo">

            <span class="logo-lg">{{ Session::get('business.name') }}</span>
        </a>


        <ul class="sidebar-menu tree" data-widget="tree">
            @foreach ($menuItems as $category => $menus)

                @if (!empty($menus) && is_array($menus))
                    <li class="treeview">


                        @if (!empty($menus[0]['sub']) && is_array($menus[0]['sub']))
                            <a href="#" data-bs-target="#menu_{{ $category }}" aria-expanded="true">
                                <i class="{{ $menus[0]['icon'] ?? 'fa fas fa-folder' }}"></i>
                                {{ $menus[0]['title'] ?? ($menus[0]['name'] ?? $category) }}
                                @if (!empty($menus[0]['sub']) && is_array($menus[0]['sub']))
                                    <span>
                                        <i class="fa fa-chevron-up ms-2"></i> {{-- Arrow for child menus --}}

                                    </span>
                                @endif
                            </a>

                            <ul class="treeview-menu" id="menu_{{ $category }}">
                                @foreach ($menus[0]['sub'] as $subItem)
                                    <li> {{-- Indents submenu items --}}
                                        <a href="{{ $subItem['url'] ?? '#' }}">
                                            <!-- <i class="{{ $subItem['icon'] ?? 'fa fas fa-folder' }}"></i> -->
                                            <i class="fa fas fa-list"></i>

                                            {{ $subItem['title'] ?? ($subItem['name'] ?? 'Unnamed') }}
                                        </a>


                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $menus[0]['url'] ?? '#' }}" data-bs-target="#menu_{{ $category }}"
                                aria-expanded="true">
                                <i class="{{ $menus[0]['icon'] ?? 'fa fas fa-folder' }}"></i>
                                {{ $menus[0]['title'] ?? ($menus[0]['name'] ?? $category) }}
                                @if (!empty($menus[0]['sub']) && is_array($menus[0]['sub']))
                                    <span>
                                        <i class="fa fa-chevron-up ms-2"></i> {{-- Arrow for child menus --}}

                                    </span>

                            </a>
                        @endif
                    </li>
                @endif
                </li>
            @endif
            @endforeach
        </ul>



    </section>
</aside>

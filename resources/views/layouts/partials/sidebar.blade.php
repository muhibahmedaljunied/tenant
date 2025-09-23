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
                            <a href="#">
                                <i class="{{ $menus[0]['icon'] ?? 'fa fas fa-folder' }}"></i>
                                <span>{{ $menus[0]['title'] ?? ($menus[0]['name'] ?? $category) }}</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" id="menu_{{ $category }}">
                                @foreach ($menus[0]['sub'] as $subItem)
                                    <li>
                                        <a href="{{ $subItem['url'] ?? '#' }}">
                                            <i class="fa fas fa-list"></i>
                                            <span>{{ $subItem['title'] ?? ($subItem['name'] ?? 'Unnamed') }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $menus[0]['url'] ?? '#' }}">
                                <i class="{{ $menus[0]['icon'] ?? 'fa fas fa-folder' }}"></i>
                                <span>{{ $menus[0]['title'] ?? ($menus[0]['name'] ?? $category) }}</span>
                            </a>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </section>
</aside>

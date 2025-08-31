<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="nav navbar-nav">
            @foreach ($menuItems as $category => $menus)
                @if (!empty($menus) && is_array($menus))
                    <li class="nav-item active">
                        <a href="#" class="nav-link" data-bs-toggle="collapse"
                            data-bs-target="#menu_{{ $category }}" aria-expanded="true">
                            <i class="{{ $menus[0]['icon'] ?? 'fa fas fa-folder' }}"></i>
                            {{ $menus[0]['title'] ?? ($menus[0]['name'] ?? $category) }}
                            @if (!empty($menus[0]['sub']) && is_array($menus[0]['sub']))
                                <i class="fa fa-chevron-down ms-2"></i> {{-- Arrow for child menus --}}
                            @endif
                        </a>

                        @if (!empty($menus[0]['sub']) && is_array($menus[0]['sub']))
                            <ul class="list-unstyled ms-3  collapse" id="menu_{{ $category }}">
                                @foreach ($menus[0]['sub'] as $subItem)
                                    <li class="ps-4"> {{-- Indents submenu items --}}
                                        <a href="{{ $subItem['url'] ?? '#' }}"
                                            class="dropdown-item {{ '' }}">
                                            <!-- <i class="{{ $subItem['icon'] ?? 'fa fas fa-folder' }}"></i> -->
                                            {{ $subItem['title'] ?? ($subItem['name'] ?? 'Unnamed') }}
                                        </a>

                                        {{-- Second-Level Submenus (Nested) --}}
                                        @if (!empty($subItem['sub']) && is_array($subItem['sub']))
                                            <ul class="collapse list-unstyled ms-4">
                                                @foreach ($subItem['sub'] as $childItem)
                                                    <li class="ps-5">
                                                        <a href="{{ $childItem['url'] ?? '#' }}">
                                                            <!-- <i
                                                                class="{{ $childItem['icon'] ?? 'fa fas fa-folder' }}"></i> -->
                                                            {{ $childItem['title'] ?? ($childItem['name'] ?? 'Unnamed') }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </section>
</aside>





<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".nav-link[data-bs-toggle='collapse']").forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent default behavior

            let target = document.querySelector(this.getAttribute("data-bs-target"));

            // Check if the target exists before modifying it
            if (!target) {
                console.error("Target element not found:", this.getAttribute("data-bs-target"));
                return;
            }

            let arrow = this.querySelector(".fa-chevron-down");

            // Toggle the menu with smooth transition
            target.classList.toggle("show");

            // Rotate arrow smoothly
            if (arrow) arrow.classList.toggle("rotate");
        });
    });
});


</script>

<style>
    .nav-item>.nav-link {
        font-weight: bold;
        /* Highlights parent items */
    }

    .nav-item ul {
        padding-left: 15px;
        /* Indents child items */
        margin-right: 33px;
    }

.nav-item ul li {
    font-size: 10px; /* Reduce font size for child items */
}

.nav-item ul .dropdown-item {
    padding-left: 20px; /* Reduce indentation */
}

 .nav-item .fa-chevron-down {
    transition: transform 0.3s ease-in-out;
}

.nav-item .nav-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

.collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease-in-out;
}

.collapse.show {
    max-height: 500px; /* Adjust this value as needed */
}

</style>

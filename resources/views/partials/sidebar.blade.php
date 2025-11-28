  <div class="app-brand demo">
            <a href="#" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/branding/eventnexus-logo.svg') }}" alt="logo" width="30">
              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2">EventNexus</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            
            @if(Auth::user()->hasRole('admin'))
            <!-- Dashboards -->
            <li class="menu-item  {{ request()->routeIs('admin.dashboard') ? 'active open' : '' }}">
              <a href="{{route('admin.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.users') ? 'active open' : '' }}">
              <a href="{{route('admin.users')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate" data-i18n="Users">Users</div>
              </a>
            </li>

            @elseif(Auth::user()->hasRole('organizer'))
            <!-- Dashboards -->
            <li class="menu-item  {{ request()->routeIs('organizer.dashboard') ? 'active open' : '' }}">
              <a href="{{route('organizer.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('organizer.events') ? 'active open' : '' }}">
              <a href="{{route('organizer.events')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate" data-i18n="Events">Events</div>
              </a>
            </li>

            @elseif(Auth::user()->hasRole('client'))
            <!-- Dashboards -->
            <li class="menu-item  {{ request()->routeIs('client.dashboard') ? 'active open' : '' }}">
              <a href="{{route('client.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('client.events') ? 'active open' : '' }}">
              <a href="{{route('client.events')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate" data-i18n="Events">Events</div>
              </a>
            </li>

            @elseif(Auth::user()->hasRole('guest'))
            <!-- Dashboards -->
            <li class="menu-item  {{ request()->routeIs('guest.dashboard') ? 'active open' : '' }}">
              <a href="{{route('guest.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('guest.events') ? 'active open' : '' }}">
              <a href="{{route('guest.events')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                <div class="text-truncate" data-i18n="Invitations">Invitations</div>
              </a>
            </li>
            @endif
          </ul>
        </aside>
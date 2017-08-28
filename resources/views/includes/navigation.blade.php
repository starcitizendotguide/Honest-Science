<nav class="nav nav-modified has-shadow">
    <div class="container">
        <div class="nav-left">
            <a href="{{ route('home.index') }}" class="nav-item is-tab is-hidden-mobile m-l-10">Home</a>
            <a href="#" class="nav-item is-tab is-hidden-mobile">About</a>
            <a href="#" class="nav-item is-tab is-hidden-mobile">FAQ</a>
        </div>

        <div class="nav-right" style="overflow: visible;">
            @if (Auth::guest())
                <a href="{{ route('login') }}" class="nav-item is-tab">Login</a>
                <a href="{{ route('register') }}" class="nav-item is-tab">Sign Up</a>
            @else
                <b-dropdown v-model="navigation" position="is-bottom-left" class="dropdown nav-item is-tab">
                    <a class="nav-item" slot="trigger">
                        <span class="m-r-5">{{ Auth::user()->name }}</span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </a>

                    <b-dropdown-option class="account-verified">
                        <span class="icon is-clearfix"><p>Verified</p> <i class="column is-one-third fa fa-check-square"></i></span>
                    </b-dropdown-option>
                    <div class="seperator"></div>
                    <b-dropdown-option class="has-text-centered"><a href="#">Profile</a></b-dropdown-option>
                    <b-dropdown-option class="has-text-centered"><a href="{{ route('settings.index') }}">Settings</a></b-dropdown-option>
                    @if(Laratrust::can('read-managment'))
                    <b-dropdown-option class="has-text-centered"><a href="{{ route('manage.dashboard') }}">Management</a></b-dropdown-option>
                    @endif
                    <div class="seperator"></div>
                    <b-dropdown-option class="has-text-centered">
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </b-dropdown-option>
                </b-dropdown>
            @endif
        </div>

    </div>
</nav>

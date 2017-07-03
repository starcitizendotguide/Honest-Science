<nav class="nav has-shadow">
    <div class="container">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="nav-item">
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo">
            </a>
            <a href="#" class="nav-item is-tab is-hidden-mobile m-l-10">Home</a>
            <a href="#" class="nav-item is-tab is-hidden-mobile">About</a>
            <a href="#" class="nav-item is-tab is-hidden-mobile">FAQ</a>
        </div>

        <div class="nav-right" style="overflow: visible;">
            @if (Auth::guest())
                <a href="#" class="nav-item is-tab">Login</a>
                <a href="#" class="nav-item is-tab">Sign Up</a>
            @else
                <!--<button >
                    Hey Bacon <span v-show="active" class="icon"><i class="fa fa-caret-down"></i></span>

                    <ul class="dropdown-menu">
                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Management</a></li>
                        <li class="seperator"></li>
                        <li><a href="">Logout</a></li>
                    </ul>
                </button>-->
                <b-dropdown id="menu-dropdown" class="dropdown nav-item is-tab">
                    <button class="button" slot="trigger">
                        <span>Hey Bacon <span v-show="active" class="icon"><i class="fa fa-caret-down"></i></span></span>
                    </button>

                    <div class="dropdown-menu">
                        <b-dropdown-option><a href="#">Profile</a></b-dropdown-option>
                        <b-dropdown-option><a href="#">Settings</a></b-dropdown-option>
                        <b-dropdown-option><a href="#">Management</a></b-dropdown-option>
                        <b-dropdown-option class="seperator"></b-dropdown-option>
                        <b-dropdown-option><a href="#"><a href="">Logout</a></a></b-dropdown-option>
                    </div>
                </b-dropdown>
            @endif
        </div>
    </div>
</nav>

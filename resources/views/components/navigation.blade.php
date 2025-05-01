<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ route('system-overview') }}" class="navbar-item">
                {{ config('app.name') }}
            </a>
        </div>

        <div class="navbar-menu">
            @auth
                <div class="navbar-end">
                    <a href="{{ route('system-overview') }}" class="navbar-item">Dashboard</a>
                    
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('user-management') }}" class="navbar-item">Users</a>
                        <a href="{{ route('sensor-management') }}" class="navbar-item">Sensors</a>
                        <a href="{{ route('simulation') }}" class="navbar-item">Simulation</a>
                    @endif
                    
                    <a href="{{ route('map') }}" class="navbar-item">Map</a>
                    <a href="{{ route('historical-data') }}" class="navbar-item">Historical Data</a>
                    <a href="{{ route('alerts') }}" class="navbar-item">Alerts</a>
                    
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            {{ Auth::user()->name }}
                        </a>

                        <div class="navbar-dropdown">
                            <a href="{{ route('profile.edit') }}" class="navbar-item">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="navbar-item">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

            @guest
                <div class="navbar-end">
                    <a href="{{ route('login') }}" class="navbar-item">Login</a>
                    <a href="{{ route('register') }}" class="navbar-item">Register</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
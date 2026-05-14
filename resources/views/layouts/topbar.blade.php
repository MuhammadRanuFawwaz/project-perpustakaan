<div class="topbar">

    {{-- TOGGLE --}}
    <button class="toggle-btn" id="toggleSidebar">
        ☰
    </button>

    {{-- RIGHT --}}
    <div class="topbar-right">

        <div class="profile-dropdown">

            <button class="profile-btn" id="profileBtn">

                <div class="user-info">

                    <div class="user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="user-role">
                        Administrator
                    </div>

                </div>

                @if(auth()->user()->photo)

                <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                    class="user-avatar"
                    alt="Foto Profile">

                @else

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                @endif

            </button>

            {{-- DROPDOWN --}}
            <div class="dropdown-menu" id="dropdownMenu">

                <button type="button"
                    class="dropdown-link"
                    onclick="openProfileModal()">

                    Edit Akun

                </button>

                <form method="POST"
                    action="{{ route('logout') }}">

                    @csrf

                    <button type="submit"
                        class="logout-btn">

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
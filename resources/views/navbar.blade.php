<nav class="navbar navbar-expand-md shadow-sm">
    <div class="container main-navbar">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('asset/logo-itdept.png') }}" width="70" height="70" alt="Logo" class="rounded">
            {{ config('app.name', 'Routine Report') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('tasks*') ? 'active' : '' }}"
                            href="{{ route('tasks.index') }}">Task</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Menu Birokrasi
                        </a>

                        <div class="dropdown-menu dropdown-menu-end main-navbar" aria-labelledby="navbarDropdown">
                            <a class="nav-link {{ request()->is('assignments*') ? 'active' : '' }}"
                                href="{{ route('assignments.index') }}">Penugasan</a>
                            <a class="nav-link {{ request()->is('permissionrequest*') ? 'active' : '' }}"
                                href="{{ route('permissionrequest.index') }}">Perizinan</a>
                            <a class="nav-link {{ request()->is('meetings*') ? 'active' : '' }}"
                                href="{{ route('meetings.index') }}">Rapat</a>
                            <a class="nav-link {{ request()->is('jobdesks*') ? 'active' : '' }}"
                                href="{{ route('jobdesks.index') }}">Job Desk</a>
                            <a class="nav-link {{ request()->is('unit*') ? 'active' : '' }}"
                                href="{{ route('Unit.index') }}">Unit</a>

                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('agendas*') ? 'active' : '' }}"
                            href="{{ route('agendas.index') }}">Agenda</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" aria-current="page"
                            href="{{ route('home') }}">Home</a>
                    </li> --}}

                </ul>
            @endauth
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a href="{{ route('announcements.index') }}"
                            class="nav-link {{ request()->is('announcements*') ? 'active' : '' }} position-relative">
                            <i class="bi bi-megaphone"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <span class="visually-hidden">belum dibaca</span>
                            </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile.index') }}">{{ __('Profile') }}</a>
                            <a class="dropdown-item" href="{{ route('employment-detail.show', Auth::id()) }}">
                                {{ __('Detail Kepegawaian') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

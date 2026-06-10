<div class="sidebar" id="sidebar">

    <div class="sidebar-header">

        <a href="{{ route('home') }}" class="sidebar-logo">
            <span class="sidebar-logo-text">
                {{ config('app.name') }}
            </span>
        </a>

        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

    </div>

    @auth

        <ul class="sidebar-menu">

            <li>
                <a href="{{ route('tasks.index') }}" class="{{ request()->is('tasks*') ? 'active' : '' }}">
                    <i class="bi bi-check2-square"></i>
                    <span class="menu-text">Task</span>
                </a>
            </li>

            <li>
                <a href="{{ route('agendas.index') }}" class="{{ request()->is('agendas*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    <span class="menu-text">Agenda</span>
                </a>
            </li>

            <li class="menu-title">
                Menu Birokrasi
            </li>

            <li>
                <a href="{{ route('assignments.index') }}" class="{{ request()->is('assignments*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span class="menu-text">Penugasan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('permissionrequest.index') }}"
                    class="{{ request()->is('permissionrequest*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span class="menu-text">Perizinan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('meetings.index') }}" class="{{ request()->is('meetings*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Rapat</span>
                </a>
            </li>

            <li>
                <a href="{{ route('jobdesks.index') }}" class="{{ request()->is('jobdesks*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase"></i>
                    <span class="menu-text">Job Desk</span>
                </a>
            </li>

            <li>
                <a href="{{ route('Unit.index') }}" class="{{ request()->is('unit*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span class="menu-text">Unit</span>
                </a>
            </li>

            <li>
                <a href="{{ route('keterlambatan.index') }}"
                    class="{{ request()->is('keterlambatan*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span class="menu-text">Terlambat</span>
                </a>
            </li>

            <li>
                <a href="{{ route('work-programs.index') }}"
                    class="{{ request()->is('work-programs*') ? 'active' : '' }}">
                    <i class="bi bi-kanban"></i>
                    <span class="menu-text">Program Kerja</span>
                </a>
            </li>

            <li>
                <a href="{{ route('piket.index') }}" class="{{ request()->is('piket*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i>
                    <span class="menu-text">Piket</span>
                </a>
            </li>

            @if (Auth::user()->isKepalaUnit() || Auth::user()->isHRD())
                <li>
                    <a href="{{ route('absensi.index') }}" class="{{ request()->is('absensi*') ? 'active' : '' }}">
                        <i class="bi bi-person-check"></i>
                        <span class="menu-text">Absensi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('renungan-absensi.index') }}"
                        class="{{ request()->is('renungan-absensi*') ? 'active' : '' }}">
                        <i class="bi bi-book"></i>
                        <span class="menu-text">Renungan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('login-logs.index') }}" class="{{ request()->is('login-logs*') ? 'active' : '' }}">
                        <i class="bi bi-activity"></i>
                        <span class="menu-text">Aktivitas Unit</span>
                    </a>
                </li>
            @endif

        </ul>


        <div class="sidebar-footer">
            <div class="sidebar-user">
                Halo, {{ Auth::user()->name }}
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="{{ route('profile.index') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span class="menu-text">Profile</span>
                    </a>
                </li>

                @if (Auth::user()->employmentDetail && Auth::user()->role !== 'admin')
                    <li>
                        <a href="{{ route('employment-detail.show', Auth::user()->employmentDetail) }}">
                            <i class="bi bi-file-earmark-person"></i>
                            <span class="menu-text">Detail Kepegawaian</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->role == 'hrd')
                    <li>
                        <a href="{{ asset('attachments/Panduan-HRD.pdf') }}" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span class="menu-text">Panduan HRD</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ asset('attachments/Panduan-Pegawai.pdf') }}" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span class="menu-text">Panduan Pegawai</span>
                        </a>
                    </li>
                @endif

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="sidebar-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="menu-text">Logout</span>
                        </button>
                    </form>
                </li>

            </ul>

        </div>

    @endauth

</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<button class="mobile-toggle" id="mobileToggle">
    <i class="bi bi-list"></i>
</button>

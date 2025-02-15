<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            CEK<span> Antrian</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
        <li class="nav-item nav-category">Main</li>
        @if (Auth::user()->ref_group_id == '1')

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#dashboard" role="button" aria-expanded="false" aria-controls="dashboard">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="dashboard">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('admin.index') }}" class="nav-link" >
                                Main
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rekap_pasien') }}" class="nav-link">
                                Rekap Pasien
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rekap_per_pkm') }}" class="nav-link">
                               Rekap Per PKM
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ route('users') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Users</span>
                </a>
            </li>
        @elseif (Auth::user()->ref_group_id == '2')
            <li class="nav-item">
                <a href="{{ route('pasien.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pasien.rekap') }}" class="nav-link">
                    <i class="link-icon" data-feather="book"></i>
                    <span class="link-title">Pasien</span>
                </a>
            </li>
        @endif
    </div>
</nav>

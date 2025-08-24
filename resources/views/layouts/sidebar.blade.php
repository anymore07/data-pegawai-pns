<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img src="{{ asset('assets/images/logo-icon.svg') }}" width="48" height="48" alt="Iconic">
        </div>
        <p>Please wait...</p>
    </div>
</div>

<!-- Top navbar div start -->
<nav class="navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-brand">
            <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-bars"></i></button>
            <button type="button" class="btn-toggle-fullwidth"><i class="fa fa-bars"></i></button>
            {{-- <a href="index.html">ICONIC</a> --}}
        </div>

        <div class="navbar-right">
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button>
                                <i class="fa fa-power-off"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- main left menu -->
<div id="left-sidebar" class="sidebar">
    <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-arrow-left"></i></button>
    <div class="sidebar-scroll">
        <div class="user-account">
            <img src="{{ asset('assets/images/user.png') }}" class="rounded-circle user-photo"
                alt="User Profile Picture">
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="user-name"><strong>Super
                        Admin</strong></a>
            </div>
            <hr>
        </div>


        <!-- Tab panes -->
        <div class="tab-content padding-0">
            <div class="tab-pane active" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu li_animation_delay">
                        <li>
                            <a href="{{ url(path: '/') }}" class="{{ $title == 'Dashboard' ? 'active' : '' }}"><i
                                    class="fa fa-dashboard"></i><span>Dashboard</span></a>
                        </li>
                        <li class="{{ in_array($title, ['Jabatan', 'Unit Kerja']) ? 'active' : '' }}">
                            <a href="#App" class="has-arrow"><i class="fa fa-th-large"></i><span>Master
                                    Data</span></a>
                            <ul>
                                <li class="{{ $title == 'Jabatan' ? 'active' : '' }}">
                                    <a href="{{ url('/jabatan') }}">Jabatan</a>
                                </li>
                                <li class="{{ $title == 'Unit Kerja' ? 'active' : '' }}">
                                    <a href="{{ url('/unit-kerja') }}">Unit Kerja</a>
                                </li>
                                <li class="{{ $title == 'Golongan' ? 'active' : '' }}">
                                    <a href="{{ url('/golongan') }}">Golongan</a>
                                </li>
                                <li class="{{ $title == 'Eselon' ? 'active' : '' }}">
                                    <a href="{{ url('/eselon') }}">Eselon</a>
                                </li>
                                <li class="{{ $title == 'Kota' ? 'active' : '' }}">
                                    <a href="{{ url('/kota') }}">Kota</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ url('/pegawai') }}" class="{{ $title == 'Pegawai' ? 'active' : '' }}"><i
                                    class="fa fa-user"></i><span>Pegawai</span></a>
                        </li>
                        <li>
                            <a href="{{ url('/article-tool') }}" class="{{ $title == 'Article' ? 'active' : '' }}"><i
                                    class="fa fa-file-text"></i><span>Article</span></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '.has-arrow', function (e) {
        e.preventDefault();

        const $link = $(this);
        const $li = $link.closest('li');
        const $submenu = $link.next('ul');

        $('.has-arrow').not($link).each(function () {
            const otherSub = $(this).next('ul');
            otherSub.removeClass('in').css('height', '0px').attr('aria-expanded', 'false');
            $(this).closest('li').removeClass('active');
        });

        if ($submenu.hasClass('in')) {
            $submenu.removeClass('in').css('height', '0px').attr('aria-expanded', 'false');
            $li.removeClass('active');
        } else {
            $submenu.addClass('in').css('height', 'auto').attr('aria-expanded', 'true');
            $li.addClass('active');
        }
    });
</script>
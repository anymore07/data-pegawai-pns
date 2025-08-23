@include('layouts.sidebar')

<div id="main-content">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h2>Dashboard</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i></a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- ====== WELCOME / HERO CARD ====== --}}
        <div class="row clearfix">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="background-color:#6f42c1; color:#fff;">
                    <div class="body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="mb-2">
                                <div id="greeting" class="text-uppercase small-12 text-light">Selamat datang</div>
                                <h3 class="mb-1 text-white">
                                    {{ auth()->user()->name ?? 'Admin' }}
                                </h3>
                                <div class="text-light">
                                    Kamu login sebagai <strong>{{ $kpi['role'] ?? 'Admin' }}</strong>.
                                    Kelola master data dan pantau aktivitas terakhir di bawah ini.
                                </div>
                            </div>
                            <div class="mb-2">
                                <a href="{{ url('pegawai') }}" class="btn btn-light mr-2">
                                    <i class="fa fa-users"></i> Kelola Pegawai
                                </a>
                                <a href="{{ url('jabatan') }}" class="btn btn-outline-light">
                                    <i class="fa fa-briefcase"></i> Kelola Jabatan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ====== QUICK ACTIONS ====== --}}
        <div class="row clearfix">
            <div class="col-lg-8">
                <div class="card">
                    <div class="header">
                        <h2>Quick Actions</h2>
                        <ul class="header-dropdown">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ url('pegawai') }}">Pegawai</a></li>
                                    <li><a href="{{ url('jabatan') }}">Jabatan</a></li>
                                    <li><a href="{{ url('unit-kerja') }}">Unit Kerja</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ url('pegawai') }}" class="btn btn-block btn-outline-primary">
                                    <i class="fa fa-user-plus"></i> Tambah Pegawai
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ url('pegawai') }}" class="btn btn-block btn-outline-info">
                                    <i class="fa fa-file-excel-o"></i> Export Pegawai
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ url('kota') }}" class="btn btn-block btn-outline-secondary">
                                    <i class="fa fa-map-marker"></i> Master Kota
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ url('jabatan') }}" class="btn btn-block btn-outline-warning">
                                    <i class="fa fa-briefcase"></i> Master Jabatan
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ url('unit-kerja') }}" class="btn btn-block btn-outline-success">
                                    <i class="fa fa-sitemap"></i> Master Unit Kerja
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-block btn-outline-danger">
                                        <i class="fa fa-sign-out"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <hr>
                        <small class="text-muted">Tips: gunakan tombol di atas untuk akses cepat ke menu yang paling
                            sering dipakai.</small>
                    </div>
                </div>
            </div>
            {{-- ====== ACTIVITY / ANNOUNCEMENTS ====== --}}
            <div class="col-lg-4">

                <div class="card">
                    <div class="header">
                        <h2>Pengumuman</h2>
                    </div>
                    <div class="body">
                        <div class="alert alert-info">
                            <i class="fa fa-bullhorn"></i> Silakan perbarui data pegawai setiap awal bulan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Greeting dinamis (pagi/siang/sore/malam) --}}
<script>
    (function () {
        var h = new Date().getHours(), g = 'Selamat datang';
        if (h >= 4 && h < 11) g = 'Selamat pagi';
        else if (h >= 11 && h < 15) g = 'Selamat siang';
        else if (h >= 15 && h < 19) g = 'Selamat sore';
        else g = 'Selamat malam';
        var el = document.getElementById('greeting');
        if (el) el.textContent = g;
    })();
</script>
@include('layouts.footer')
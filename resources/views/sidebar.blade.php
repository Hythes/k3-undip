@section('sidebar')
<div class="sidebar" data-color="purple" data-background-color="black"
    data-image="{{asset('assets/img/sidebar-2.jpg')}}">
    <div class="logo">
        <a href="" class="simple-text logo-normal">
            K3 Undip
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="dashboard">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/dataK3') ? 'active' : '' }}">
                <a class="nav-link" href="dataK3">
                    <i class="material-icons">storage</i>
                    <p>Data K3</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/pelapor') ? 'active' : '' }}">
                <a class="nav-link" href="pelapor">
                    <i class="material-icons">account_box</i>
                    <p>Data Pelapor</p>
                </a>
            </li>
            <li class="nav-item {{ Request::is('admin/dataAdmin') ? 'active' : '' }}">
                <a class="nav-link" href="dataAdmin">
                    <i class="material-icons">admin_panel_settings</i>
                    <p>Data Admin</p>
                </a>
            </li>
            <hr>
            <a class="btn btn-danger text-white btn-block active" href="logout">
                <p>Logout</p>
            </a>

        </ul>
    </div>
</div>
@endsection
<div class="vertical-menu">

    <div data-simplebar="" class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="bx bx-home-circle"></i><span class="badge badge-pill badge-info float-right">03</span>
                        <span>Dashboards</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="index.html">Default</a></li>
                        <li><a href="dashboard-saas.html">Saas</a></li>
                        <li><a href="dashboard-crypto.html">Crypto</a></li>
                    </ul>
                </li>

                <li class="menu-title">Apps</li>

                <li>
                    <a href="{{ route('categories.index') }}" class=" waves-effect">
                        <i class="nav-icon far fa-list-alt"></i>
                        <span>Quản lý thể loại</span>
                    </a>
                    <a href="{{ route('news.index') }}" class=" waves-effect">
                        <i class="fas fa-notes-medical"></i>
                        <span>Quản lý bài viết</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>

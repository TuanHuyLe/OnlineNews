<div class="vertical-menu">

    <div data-simplebar="" class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Chức năng</li>
                @can('view_category')
                    <li>
                        <a href="{{ route('categories.index') }}" class=" waves-effect">
                            <i class="nav-icon far fa-list-alt"></i>
                            <span>Thể loại</span>
                        </a>
                    </li>
                @endcan
                @can('view_new')
                    <li>
                        <a href="{{ route('news.index') }}" class=" waves-effect">
                            <i class="fas fa-notes-medical"></i>
                            <span>Bài viết</span>
                        </a>
                    </li>
                @endcan
                <li class="menu-title">Phân quyền</li>
                @can('view_member')
                    <li>
                        <a href="{{ route('users.index') }}" class=" waves-effect">
                            <i class="fas fa-user-friends"></i>
                            <span>Thành viên</span>
                        </a>
                    </li>
                @endcan
                @can('view_role')
                    <li>
                        <a href="{{ route('roles.index') }}" class=" waves-effect">
                            <i class="fas fa-user-tag"></i>
                            <span>Vai trò</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>

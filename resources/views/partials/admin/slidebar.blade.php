<div class="vertical-menu">

    <div data-simplebar="" class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li class="menu-title">Chức năng</li>

                <li>
                    <a href="{{ route('categories.index') }}" class=" waves-effect">
                        <i class="nav-icon far fa-list-alt"></i>
                        <span>Thể loại</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('news.index') }}" class=" waves-effect">
                        <i class="fas fa-notes-medical"></i>
                        <span>Bài viết</span>
                    </a>
                </li>

                <li class="menu-title">Phân quyền</li>

                <li>
                    <a href="{{ route('users.index') }}" class=" waves-effect">
                        <i class="fas fa-user-friends"></i>
                        <span>Thành viên</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('roles.index') }}" class=" waves-effect">
                        <i class="fas fa-user-tag"></i>
                        <span>Vai trò</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>

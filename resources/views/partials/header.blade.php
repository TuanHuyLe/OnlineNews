<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{route('home')}}">Tin tức online</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('home')}}">Trang chủ
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    @if(auth()->check())
                        <a class="nav-link" href="{{route('logout')}}">Đăng xuất, {{strtoupper(auth()->user()['name'])}}</a>
                    @else
                        <a class="nav-link" href="{{route('login')}}">Đăng nhập</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>

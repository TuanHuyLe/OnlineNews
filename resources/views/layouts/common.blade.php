<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('blog_home/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('blog_home/css/blog-home.css')}}" rel="stylesheet">

</head>

<body>

<!-- Navigation -->
@include('partials.home.header')

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        @yield('content')

        <!-- Sidebar Widgets Column -->
        @include('partials.home.rightmenu')

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->

<!-- Footer -->
@include('partials.home.footer')

<!-- Bootstrap core JavaScript -->
<script src="{{asset('blog_home/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('blog_home/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

</body>

</html>

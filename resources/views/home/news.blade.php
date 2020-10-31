@extends('layouts.common')
@section('title', 'Trang chủ')

@section('content')
    <div class="col-lg-8">

    @include('partials.contentheader', ['name' => 'Bài đăng', 'key' => ''])

    <!-- Title -->
        <h1 class="mt-4">{{$news->title}}</h1>

        <hr>

        <!-- Date/Time -->
        <p>Ngày đăng {{$news->created_at}}</p>

        <hr>

        <!-- Preview Image -->
        <img class="img-fluid rounded" src="{{$news->image}}" alt="">

        <hr>

        <!-- Post Content -->
        <p>{{$news->content}}</p>

        <a class="btn btn-primary mb-5" href="{{route('home')}}">Quay lại</a>

    </div>
@endsection

@extends('layouts.common')
@section('title', 'Tin tức online')

@section('content')
    <div class="col-md-8">

    @if(isset($key))
        @include('partials.home.contentheader', ['name' => 'Tìm kiếm:', 'key' => $key])
    @elseif(isset($newsCategory))
        @include('partials.home.contentheader', ['name' => 'Thể loại:', 'key' => $newsCategory->name])
    @else
        @include('partials.home.contentheader', ['name' => 'Trang chủ', 'key' => ''])
    @endif
    <!-- Blog Post -->
    @foreach($newsItem as $item)
        <div class="card mb-4">
            <img class="card-img-top" src="{{$item->image}}" alt="Card image cap">
            <div class="card-body">
                <h2 class="card-title">{{$item->title}}</h2>
                <div>{{$item->shortDescription}}</div>
                <a href="{{route('home.news', ['id' => $item->id])}}" class="mt-2 btn btn-primary">Đọc thêm
                    &rarr;</a>
            </div>
            <div class="card-footer text-muted">
                Ngày đăng {{$item->created_at}}
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="col-md-12">
        {{$newsItem->links()}}
    </div>

    </div>
@endsection


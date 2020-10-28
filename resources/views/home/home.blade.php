@extends('layouts.common')
@section('title', 'Trang chủ')

@section('content')
    <div class="col-md-8">

    @include('partials.contentheader', ['name' => 'Trang chủ', 'key' => ''])

    <!-- Blog Post -->
        @foreach($newsItem as $item)
            <div class="card mb-4">
                <img class="card-img-top" src="{{$item->image}}" alt="Card image cap">
                <div class="card-body">
                    <h2 class="card-title">{{$item->title}}</h2>
                    <div>{{$item->shortDescription}}</div>
                    <a href="{{$item->id}}" class="mt-2 btn btn-primary">Đọc thêm &rarr;</a>
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


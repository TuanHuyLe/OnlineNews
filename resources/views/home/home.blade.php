@extends('layouts.common')
@section('title', 'Tin tức online')

@section('content')
    <div class="col-md-8">

    @if(isset($key))
        @include('partials.web.content_header', ['name' => 'Tìm kiếm:', 'key' => $key])
    @elseif(isset($newsCategory))
        @include('partials.web.content_header', ['name' => 'Thể loại:', 'key' => $newsCategory->name])
    @else
        @include('partials.web.content_header', ['name' => 'Trang chủ', 'key' => ''])
    @endif
    <!-- Blog Post -->
        <div class="root"></div>
        <!-- Pagination -->
        <div class="col-md-12">
            <ul id="pagination" class="pagination-sm"></ul>
        </div>

    </div>
@endsection


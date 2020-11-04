@extends('layouts.admin')

@section('title')
    <title>Quản lý thể loại</title>
@endsection

@section('script')
    @if(isset(request()->message_code))
        <script>
            @switch(request()->get('message_code'))
            @case(1)
            toastr.success("Thêm mới thành công!");
            @break
            @case(2)
            toastr.success("Cập nhật thành công!");
            @break
            @case(-1)
            toastr.error("Có lỗi xảy ra");
            @break
            @case(-2)
            toastr.error("Tên thể loại đã tồn tại!");
            @break
            @default
            @break
            @endswitch
        </script>
    @endif
@endsection

@section('content')
    @include('partials.admin.content-header', ['name'=>'Quản lý thể loại', 'key'=>'Danh sách', 'url'=>route('categories.index')])

    <div class="row" style="height: calc(100vh - 205px);">
        <div class="col-lg-8">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-5">
                            <h3 class="card-title" id="count_news"></h3>
                        </div>
                        <div class="col-md-7">
                            <form>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-6">
                                        <input id="txt_seach"
                                               name="category_name"
                                               placeholder="Nhập tên"
                                               value="{{ empty($categoryName) ? '' : $categoryName}}"
                                               class="form-control"/>
                                    </div>
                                    <div class="col-md-5">
                                        <button id="btn_seach" title="tìm kiếm" class="btn btn-primary"><i
                                                class="fas fa-search"></i></button>
                                        <a id="btn_refresh" title="làm mới"
                                           href="{{ route('categories.index') }}"
                                           class="btn btn-secondary">
                                            <i
                                                class="fas fa-redo"></i></a>
                                        <a class="btn btn-success" title="thêm mới"
                                           href="#">
                                            <i class="far fa-plus-square"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0" style="overflow: auto; height: 300px;">
                    <table class="table-hover table">
                        <thead>
                        <tr>
                            <th style="width: 60px">Id</th>
                            <th>Tên danh mục</th>
                            <th style="width: 150px">
                                Thao tác
                            </th>
                        </tr>
                        </thead>
                        <tbody id="tbl_data">
                        @foreach($categories as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <a href="#"
                                       title="chỉnh sửa"
                                       class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <button title="xóa"
                                            data-url="#"
                                            class="btn_delete btn btn-sm btn-danger">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="background-color: transparent;">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

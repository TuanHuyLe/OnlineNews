@extends('layouts.admin')

@section('title')
    <title>Quản lý thể loại</title>
@endsection

@section('script')
    <script src="{{ asset('js/common/common.js') }}"></script>
    <script src="{{ asset('js/admin/base.js') }}"></script>
    <script src="{{ asset('js/admin/category.js') }}"></script>
@endsection

@section('content')
    @include('partials.admin.content-header', ['name'=>'Quản lý thể loại', 'key'=>'Danh sách', 'url'=>route('categories.index')])

    <div class="row" style="height: calc(100vh - 205px);">
        <div class="col-lg-12">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-7">
                            <button title="Thêm mới" class="btn btn-success">
                                <i class="far fa-plus-square"></i>
                            </button>
                            <button title="chỉnh sửa" class="btn btn-warning">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button title="xóa" data-url="#" class="btn_delete btn btn-danger">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="col-md-5">
                            <form>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <input id="txt_seach"
                                               name="category_name"
                                               placeholder="Nhập tên"
                                               value="{{ empty($categoryName) ? '' : $categoryName}}"
                                               class="form-control"/>
                                    </div>
                                    <div class="col-md-3">
                                        <button id="btn_seach" title="tìm kiếm" class="btn btn-primary"><i
                                                class="fas fa-search"></i></button>
                                        <button id="btn_refresh" title="làm mới"
                                           href="{{ route('categories.index') }}"
                                           class="btn btn-secondary">
                                            <i class="fas fa-redo"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0" style="overflow: auto; height: 355px;">
                    <table id="table-data" class="table-hover table">
                        <thead>
                            <tr keyId="id">
                                <th style="width: 60px" fieldname="id">Id</th>
                                <th fieldname="name">Tên danh mục</th>
                                <th fieldname="created_at" format="date">Ngày tạo</th>
                                <th fieldname="updated_at" format="date">Ngày sửa gần nhất</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-data">
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-top" style="position: relative; display: flex; background-color: transparent; border-top: 2px solid #bbb;">
                    <div class="btn-group">
                        <button id="btn-page-first" class="btn btn-outline-secondary">
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button id="btn-page-prev" class="btn btn-outline-secondary">
                            <i class="fas fa-angle-left"></i>
                        </button>
                    </div>
                    <input id="current-page" class="form-control ml-1" type="text" style="border-color: #74788d; width: 60px; margin-left: 1px; margin-right: 1px;">
                    <span id="total-page" style="padding: 0 5px; line-height: 35px;">trên  </span>
                    <div class="btn-group">
                        <button id="btn-page-next" class="btn btn-outline-secondary">
                            <i class="fas fa-angle-right"></i>
                        </button>
                        <button id="btn-page-last" class="btn btn-outline-secondary">
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </div>
                    <select id="page-size">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>

                    <div class="right-paging">Hiển thị&nbsp;<span>1 - 15</span>&nbsp;trên&nbsp;<span>15</span>&nbsp;kết quả</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

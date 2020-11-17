@extends('layouts.admin')

@section('title')
    <title>Quản lý bài viết</title>
@endsection

@section('script')
    <script src="{{ asset('js/common/common.js') }}"></script>
    <script src="{{ asset('js/admin/base.js') }}"></script>
    <script src="{{ asset('js/admin/new.js') }}"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="{{ asset('blog_admin/tinymce/tinymce.js') }}"></script>
@endsection

@section('content')
    @include('partials.admin.content-header', ['name'=>'Quản lý bài viết', 'key'=>'Danh sách', 'url'=>route('news.index')])

    <div class="row" style="height: calc(100vh - 205px);">
        <div class="col-lg-12">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-7">
                            @can('add_new')
                                <button id="btn-add" title="Thêm mới" class="btn btn-success waves-effect waves-light">
                                    <i class="far fa-plus-square"></i> Thêm mới
                                </button>
                            @endcan
                            @can('edit_new')
                                <button id="btn-edit" disabled title="chỉnh sửa"
                                        class="btn btn-warning waves-effect waves-light">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </button>
                            @endcan
                            @can('delete_new')
                                <button id="btn-delete" disabled title="xóa" data-url="#"
                                        class="btn_delete btn btn-danger waves-effect waves-light">
                                    <i class="far fa-trash-alt"></i> Xóa
                                </button>
                            @endcan
                            <input id="display-dialog" type="hidden" data-toggle="modal"
                                   data-target=".bs-example-modal-center"/>
                        </div>
                        <div class="col-md-5">
                            <form>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <input id="seach"
                                               placeholder="Nhập tên bài viết"
                                               class="form-control"/>
                                    </div>
                                    <div class="col-md-3">
                                        <button id="btn-seach" title="tìm kiếm" class="btn btn-primary"><i
                                                class="fas fa-search"></i></button>
                                        <button id="btn-refresh" title="làm mới"
                                                class="btn btn-secondary">
                                            <i class="fas fa-redo"></i>
                                        </button>
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
                            <th fieldname="title" format="limit_string">Tiêu đề</th>
                            <th fieldname="category" format="limit_string">Thể loại</th>
                            <th fieldname="shortDescription" format="limit_string">Mô tả</th>
                            <th fieldname="created_at" format="date">Ngày tạo</th>
                            <th fieldname="updated_at" format="date">Ngày sửa gần nhất</th>
                        </tr>
                        </thead>
                        <tbody id="tbl-data">
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-top"
                     style="position: relative; display: flex; background-color: transparent; border-top: 2px solid #bbb;">
                    <div class="btn-group">
                        <button id="btn-page-first" class="btn btn-outline-secondary">
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                        <button id="btn-page-prev" class="btn btn-outline-secondary">
                            <i class="fas fa-angle-left"></i>
                        </button>
                    </div>
                    <input id="current-page" class="form-control ml-1" type="text"
                           style="border-color: #74788d; width: 60px; margin-left: 1px; margin-right: 1px;">
                    <span id="total-page" class="span-number-text">trên  </span>
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

                    <div class="right-paging">Hiển thị&nbsp;<span>1 - 15</span>&nbsp;trên&nbsp;<span>15</span>&nbsp;kết
                        quả
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-xl-3">
        <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0">Thông tin bài viết</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-data" class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="txtUsername">Tiêu đề</label>
                                        <input type="text" name="title" placeholder="Nhập tên tiêu đề"
                                               class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtUsername">Mô tả ngắn</label>
                                        <input type="text" name="shortDescription" placeholder="Nhập mô tả"
                                               class="form-control"/>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtUsername">Thể loại</label>
                                                <select class="form-control" name="category_id" fetch data-id="id"
                                                        data-name="name" data-url="CATEGORY_API">
                                                    <option value="">-- Chọn thể loại --</option>
                                                </select>
                                                {{--                                                <input type="text" name="category_id" placeholder="Nhập tên thể loại" class="form-control" />--}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ảnh thumbnail</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="image-input">
                                                    <input type="hidden" name="image_base64">
                                                    <input type="hidden" name="image_name">
                                                    <label id="image-name" class="custom-file-label"
                                                           for="customFile"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtEmail">Nội dung</label>
                                        <textarea id="content" class="form-control my-editor" placeholder="Nhập mô tả"
                                                  name="contents" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btn-cancel" data-dismiss="modal" title="hủy bỏ"
                                class="btn btn-danger waves-effect waves-light">
                            <i class="fas fa-times"></i> Hủy bỏ
                        </button>
                        <button id="btn-save" title="lưu" class="btn btn-primary waves-effect waves-light">
                            <i class="far fa-save"></i> Lưu
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
@endsection

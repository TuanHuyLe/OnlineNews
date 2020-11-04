@extends('layouts.admin')

@section('title')
    <title>Home</title>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    @include('partials.admin.content-header', ['name'=>'Quản lý sản phẩm', 'key'=>'Thêm mới', 'url'=>'/products'])
    <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <h1>Home</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.app')

@section('title')
    Liên  hệ
@endsection

@section('sub-title')
    danh sách
@endsection

@section('content')
    <div class="row">
        @include('admin.partial.log.err_log',['name' => 'delete'])
        @include('admin.partial.log.success_log',['name' => 'success'])
        <div class="col-lg-12">
            @include('admin.table', [
                'id' => 'contact',
                'title' => [
                    'caption' => 'Dữ liệu phản hồi người dùng',
                    'icon' => 'fa fa-table',
                    'class' => 'portlet box green',
                ],
                'url' => url("/ajax/feedback?type=contact"),
                'columns' => [
                    ['data' => 'id', 'title' => 'Mã'],
                    ['data' => 'name', 'title' => 'Tên'],
                    ['data' => 'email', 'title' => 'Email'],
                    ['data' => 'phone', 'title' => 'Số điện thoại'],
                    ['data' => 'subject', 'title' => 'Chủ đề'],
                    ['data' => 'content', 'title' => 'Nội dung'],
                    ['data' => 'created_at', 'title' => 'Ngày phản hồi'],
                    ['data' => 'action', 'title' => 'Hành động'],
                ]
                ])
        </div>
    </div>
@endsection

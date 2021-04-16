@extends('admin.app')

@section('title')
  Quản lý Kho
@endsection

@section('sub-title')
    danh sách
@endsection

@section('content')
    <div class="row">
        @include('admin.partial.log.err_log',['name' => 'delete'])
        @include('admin.partial.log.success_log',['name' => 'success'])
        <div class="well" style="padding-left: 0px">
            <button type="button" class="btn btn-success " id="btnAddNew"><i class="fa fa-plus"
                    aria-hidden="true"></i> Thêm mới</button>
        </div>
        <div class="col-lg-12">
            @include('admin.table_paging', [
               'id' => 'warehouse',
               'title' => [
                       'caption' => 'Dữ liệu kho',
                       'icon' => 'fa fa-table',
                       'class' => 'portlet box green',
               ],
               'url' => url("/ajax/get_user/warehouse"),
               'columns' => [
                ['data' => 'uuid', 'title' => 'ID'],
                        ['data' => 'name', 'title' => 'Tên'],
                        ['data' => 'avatar', 'title' => 'Ảnh đại diện'],
                        ['data' => 'email', 'title' => 'Email'],
                        ['data' => 'full_address', 'title' => 'Địa chỉ'],
                        ['data' => 'phone_number', 'title' => 'Hotline'],
                        ['data' => 'action', 'title' => 'Hành động', 'orderable' => false]
                   ]
               ])
        </div>
    </div>
   
      <!-- Modal user -->
   @include('admin.partial.modal.user')
   @include('admin.partial.modal.detail_img')
@endsection
@push('script')
    <script src="{{ asset('public/js/action-user.js') }}"></script>
    <script>
        $("#modalUser #btnSave").on("click", function (event) {
            var id = $("#modalUser .action").attr('id');
            if( id == "addUser"){
                actionUser("warehouse","store");
            }else{
                actionUser( "warehouse", "update");
            }
        })
    </script>
@endpush



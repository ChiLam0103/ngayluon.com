@extends('admin.app')

@section('title')
   Phân công đơn hàng
@endsection

@section('sub-title')
    danh sách
@endsection

@section('content')
    <div class="row">
        @include('admin.partial.log.err_log',['name' => 'delete'])
        @include('admin.partial.log.success_log',['name' => 'success'])
        <div class="well" style="padding-left: 0px">
            <div class="row">
                    
                    <div class="col-lg-12" style="margin-top: 5px">
                       
                        <div class="row">
                            <div class="col-lg-12" style="margin-top: 5px">
                                <button type="button" id="quick-assign" class="btn btn-primary">
                                    <i class="fa fa-motorcycle" aria-hidden="true"></i> Phân công đơn hàng
                                </button>
                                
                                <button type="button" class="btn btn-primary " id="btnAddNewBooking"><i class="fa fa-plus"
                                    aria-hidden="true"></i> Thêm mới</button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12" id="table_booking">
            <legend style="font-size:20px;color:red">Bộ lọc</legend>
            <div class="row">
                <div class="col-lg-3">
                    <label>Danh sách Shipper:</label>
                    {{ Form::select('shipper', \App\Models\User::getUserOption('shipper'), old('name_id_fr'), ['class' => 'form-control', 'style' => 'width:100%', 'id' => 'shipper_id', 'onchange' => 'loadCustomerFr()']) }}
                </div>
                <div class="col-lg-3">
                    <label>Hành động:</label>
                    <select class="form-control" id="status">
                        <option value="processing">Đã phân</option>
                        <option value="delay">Hoãn </option>
                        <option value="completed">Đã hoàn thành</option>
                        <option value="deny">Từ chối</option>
                        <option value="cancel">Hủy</option>
                      
                    </select>
                </div>
                <div class="col-lg-3">
                    <label>Trạng thái:</label>
                    <select class="form-control" id="category">
                        <option value="receive">Lấy hàng</option>
                        <option value="send">Giao hàng</option>
                        <option value="return">Trả lại hàng</option>
                        <option value="receive-and-send">Vừa lấy vừa giao</option>
                        <option value="move">Giao lại</option>
                    </select>
                </div>
                <div class="col-lg-3"> <label></label> <button type="button" id="btnView" class="btn btn-primary form-control">
                        <i class="fa fa-eye" aria-hidden="true"></i> Xem 
                    </button></div>
                <div class="col-lg-12" id="table_booking" style="margin-top: 2em">
                    <legend style="font-size:20px;color:red">Danh sách</legend>
                    <table id="list_table_assign_booking" class="display boder portlet box green" width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Modal  -->
    @include('admin.partial.modal.detail_img')
    @include('admin.partial.modal.detail_booking')
    @include('admin.partial.modal.booking')
    @include('admin.partial.modal.assign_booking')
@endsection
@push('script')
    <script src="{{ asset('public/js/action-booking.js') }}"></script>
    <script>
     viewQuickAssign();
    </script>
  
@endpush
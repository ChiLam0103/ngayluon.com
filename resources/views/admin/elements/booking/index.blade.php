@extends('admin.app')

@section('title')
    Đơn hàng
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
                            <button type="button" id="shipper-change-status" class="btn btn-primary">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Shipper-Thay đổi trạng thái Đ/H
                            </button>
                            <button type="button" id="warehouse-import-export " class="btn btn-primary">
                                <i class="fa fa-home" aria-hidden="true"></i> Kho-Nhập/xuất Đ/H
                            </button>
                            <button type="button" id="quick-assign" class="btn btn-primary">
                                <i class="fa fa-motorcycle" aria-hidden="true"></i> Admin-Phân công đơn hàng
                            </button>
                            <button type="button" class="btn btn-primary " id="btnAddNewBooking"><i class="fa fa-plus"
                                    aria-hidden="true"></i> Thêm mới</button>
                            <form style="display: inline;" action="{{ url('admin/booking/importBooking') }}" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}">
                                <label class="importFileExcel btn"><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    Thêm
                                    mới
                                    (Excel)
                                    <input type="file" size="60" name="file" onchange="form.submit()" />
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-2" id="status_booking">
            <h5><b>Trạng thái đơn hàng</b></h5>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="all">Tất cả (<span
                        id="status_all"></span>)</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="new">Chờ xử lý (<span
                        id="status_new"></span>)</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="warehouse">Trong kho
                    (<span id="status_warehouse"></span>)</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="taking">Lấy hàng (<span
                        id="status_taking"></span>)</label></div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="sending">Giao hàng
                    (<span id="status_sending"></span>)</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="completed">Giao thành
                    công (<span id="status_completed"></span>)</label></div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="split">Tách hàng (<span
                        id="status_return"></span>)</label></div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_transfer">Chuyển
                    hoàn (<span id="status_move"></span>)</label></div>

            <div><label><input type="radio" class="option-input radio" name="booking_status" value="cancel">Đã huỷ (<span
                        id="status_cancel"></span>)</label></div>
        </div>

        <div class="col-lg-10 col-md-10" id="table_booking">
            <table id="list_booking" class="display boder portlet box green" width="100%">
            </table>
        </div>
    </div>

    <!-- Modal  -->
    @include('admin.partial.modal.detail_img')
    @include('admin.partial.modal.detail_booking')
    @include('admin.partial.modal.booking')
    @include('admin.partial.modal.assign_booking')
    @include('admin.partial.modal.shipper_change_status')
@endsection
@push('script')
    <script src="{{ asset('public/js/action-booking.js') }}"></script>
    <script>


    </script>

@endpush

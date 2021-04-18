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
                <form action="{!! url('admin/booking/exportAdvance') !!}" method="get">
                    <input type="hidden" name="status[]" value="new">
                    <input type="hidden" name="status[]" value="taking">
                    <input type="hidden" name="sub_status[]" value="none">
                    <div class="col-lg-8">
                        <div class="input-group">
                             <span class="input-group-addon" id="sizing-addon2"><span
                                         class="glyphicon glyphicon-calendar"> </span> Từ ngày</span>
                            <input type="date" id="date_from" name="date_from" class="form-control"
                                   aria-describedby="sizing-addon2" value="{!! $time_from !!}">
                            <span class="input-group-addon" id="sizing-addon2"><span
                                        class="glyphicon glyphicon-calendar"> </span> Đến ngày</span>
                            <input type="date" id="date_to" name="date_to" class="form-control"
                                   aria-describedby="sizing-addon2" value="{{\Carbon\Carbon::today()->toDateString()}}">
                            <span class="input-group-addon">Số điện thoại</span>
                            <input style="min-width: 180px" type="text" id="phone" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12" style="margin-top: 5px">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <span class="input-group-addon" id="sizing-addon2">Quận / Huyện</span>
                                    <select style="min-width: 180px" id="district" onchange="loadWard(this.value)" name="district_id"
                                            class="form-control">
                                        <option value="-1" selected>Tất cả</option>
                                    </select>
                                    <span class="input-group-addon">Phường / xã</span>
                                    <select style="min-width: 180px" id="ward" name="ward_id" class="form-control">
                                        <option value="-1" selected>Tất cả</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-lg-12" style="margin-top: 5px">
                                <button type="submit" class="btn btn-circle btn-primary"><i
                                            class="fa fa-print"
                                            aria-hidden="true"></i>
                                    Xuất dữ liệu
                                </button>
                                <button type="button" id="quick-assign" class="btn btn-circle btn-primary">
                                    Phân công hàng loạt
                                </button>
                                <button type="button" class="btn btn-success " id="btnAddNewBooking"><i class="fa fa-plus"
                                    aria-hidden="true"></i> Thêm mới</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-2" id="status_booking">
            <h5><b>Trạng thái đơn hàng</b></h5>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="new">Chờ xử lý</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="warehouse">Trong kho</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="taking">Lấy hàng</label></div>
            <div class=" sub_radio">
                <div> <label><input type="radio" class="option-input radio" name="booking_status" value="taking_doing">Đang lấy hàng</label>  </div>
                <div> <label><input type="radio" class="option-input radio" name="booking_status" value="taking_waiting">Chờ lấy hàng</label></div>
                <div> <label><input type="radio" class="option-input radio" name="booking_status" value="taking_finish">Đã lấy hàng</label></div>
            </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="sending">Giao hàng</label> </div>
            <div class=" sub_radio">
                <div><label><input type="radio" class="option-input radio" name="booking_status" value="sending_doing">Đang giao hàng</label></div>
                <div><label><input type="radio" class="option-input radio" name="booking_status" value="sending_waiting">Chờ giao lại</label></div>
            </div>
            <div ><label><input type="radio" class="option-input radio" name="booking_status" value="completed">Giao thành công</label></div>
            <div ><label><input type="radio" class="option-input radio" name="booking_status" value="split">Tách hàng</label></div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_transfer">Chuyển hoàn</label></div>
            <div class=" sub_radio">
              <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_waiting">Chờ chuyển hoàn</label></div>
              <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_doing">Đang chuyển hoàn</label></div>
              <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_waiting_again">Chờ chuyển hoàn lại</label></div>
            </div>
            <div ><label><input type="radio" class="option-input radio" name="booking_status" value="refunded">Đã chuyển hoàn</label></div>
            <div ><label><input type="radio" class="option-input radio" name="booking_status" value="cancel">Đã huỷ</label></div>
        </div>
       
        <div class="col-lg-10" id="table_booking">
            <table id="list_booking" class="display boder portlet box green" width="100%">
            </table>
        </div>
    </div>

    <!-- Modal Phân công hàng loạt-->
    <form action="" method="POST" id="form-quick-assign">
        {!! csrf_field() !!}
        <div class="modal fade" id="quickAssignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Phân công hàng loạt</h4>
              </div>
              <div class="modal-body" style="max-height: 450px; overflow-y: scroll;" >
                <div style="font-size: 12px;">
                    - Chức năng này có nhiệm vụ phân công hàng loạt các đơn hàng được chọn, không ảnh hưởng đến các thuộc tính khác của đơn hàng.
                    <br>- Nếu bạn phân công nhầm, có thể chọn "Đã phân công" -> và phân công lại.
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h4>Danh sách shipper</h4>
                        <table id="ul-shipper" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Tên shipper</th>
                                </tr>
                            </thead>
                            <tbody style="max-height: 400px; overflow-y: scroll;"></tbody>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <div>
                            <h4 style="display: inline-block;">Danh sách đơn hàng</h4>
                            <select name="" id="type-assign" class="form-control" style="display: inline-block; width: 200px;">
                                <option value="no_assign" selected="">Chưa phân công</option>
                                <option value="assigned">Đã phân công</option>
                            </select>
                        </div>
                        <table id="ul-book" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="check-all"></th>
                                    <th>Mã ĐH</th>
                                    <th>Tên ĐH</th>
                                    <th>Người gửi</th>
                                    <th>Số ĐT người gửi</th>
                                    <th>Địa chỉ người gửi</th>
                                    <th>Shipper đã phân công</th>
                                </tr>
                            </thead>
                            <tbody style="max-height: 400px; overflow-y: scroll;"></tbody>
                        </table>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <span id="msg-error" style="color: red"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Thoát</button>
                <button type="button" class="btn btn-primary" id="save-quick-assign">Đồng ý</button>
              </div>
            </div>
          </div>
        </div>
    </form>

    <!-- Modal  -->
    @include('admin.partial.modal.detail_img')
    @include('admin.partial.modal.detail_booking')
    @include('admin.partial.modal.booking')
@endsection
@push('script')
    <script src="{{ asset('public/js/action-booking.js') }}"></script>
    <script>
    //     var t = $('#example').DataTable();
    // var counter = 1;

    // $('#addRow').on( 'click', function () {
    //     $("#all_booking").DataTable().row.add( [
    //         counter +'.1',
    //         counter +'.2',
    //         counter +'.3',
    //         counter +'.4',
    //         counter +'.5'
    //     ] ).draw( false );

    //     counter++;
    // } );
    
    // Automatically add a first row of data

    // function loadWard(id) {
    //         $("#ward option[value!='-1']").remove();
    //         $.ajax({
    //             type: "GET",
    //             url: '{{url('/ajax/get_ward/')}}/' + id
    //         }).done(function (msg) {
    //             var i;
    //             for (i = 0; i < msg.length; i++) {
    //                 if (msg[i]['id'] == '{{@$user->ward_id}}' || msg[i]['id'] == '{{old('ward_id')}}') {
    //                     $('select[name="ward_id"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
    //                 } else {
    //                     $('select[name="ward_id"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
    //                 }
    //             }
    //         });

    // }

    // function changeUrl(data) {
    //         var href = $('#owe_submit').attr('href');
    //         if ($('#owe').is(':checked')){
    //             if (href.indexOf('?owe=0') > -1){
    //                 $('#owe_submit').attr("href", href.replace('?owe=0', '?owe=1'));
    //             }else{
    //                 $('#owe_submit').attr("href", href+'?owe=1');
    //             }
    //         }else {
    //             $('#owe_submit').attr("href",  href.replace('?owe=1', '?owe=0'));
    //         }
    // }
    function loadListBoook(type_assign) {
            $.ajax({
                type: "GET",
                url: "{{ route('get_quick_assign_new') }}",
                data: {
                    province_id: $('#province').val(),
                    district_id: $('#district').val(),
                    phone: $('#phone').val(),
                    ward_id: $('#ward').val(),
                    type_assign: type_assign
                },
                dataType: "JSON"
            }).done(function (msg) {
                console.log(msg);
                $('#quickAssignModal #ul-shipper tbody').html('');
                if (msg.shippers.length > 0) {
                    $( msg.shippers ).each(function( index, value ) {
                        var shipperLi = '';
                        shipperLi += '<tr>';
                        if (index == 0) {
                            shipperLi += '<td><input type="radio" value="' + value.id + '" name="shipper" checked></td>';
                        } else {
                            shipperLi += '<td><input type="radio" value="' + value.id + '" name="shipper"></td>';    
                        }
                        shipperLi += '<td>' + value.name + '</td>';
                        shipperLi += '</tr>';
                        $('#quickAssignModal #ul-shipper tbody').append(shipperLi);
                    });
                }
                $('#quickAssignModal #ul-book tbody').html('');
                if (msg.books.length > 0) {
                    $( msg.books ).each(function( index, value ) {
                        var bookLi = '';
                        bookLi += '<tr>';
                        bookLi += '<td><input type="checkbox" value="' + value.id + '" name="books"></td>';
                        bookLi += '<td>' + value.uuid + '</td>';
                        bookLi += '<td>' + value.name + '</td>';
                        bookLi += '<td>' + value.send_name + '</td>';
                        bookLi += '<td>' + value.send_phone + '</td>';
                        bookLi += '<td>' + value.send_full_address + '</td>';
                        bookLi += '<td>' + value.shipper_name + '</td>';
                        bookLi += '</tr>';
                        $('#quickAssignModal #ul-book tbody').append(bookLi);
                    });
                }
                $('#quickAssignModal').modal('show');
            });
    }
    $(document).ready(function(){
            
            // var province = 50;
            // $("#district option[value!='-1']").remove();
            // $.ajax({
            //     type: "GET",
            //     url: "{{url('/ajax/get_district/')}}/" + province
            // }).done(function (msg) {
            //     var i;
            //     for (i = 0; i < msg.length; i++) {
            //         if (msg[i]['id'] == '{{@$user->district_id}}' || msg[i]['id'] == '{{old('district_id')}}') {
            //             $('select[name="district_id"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
            //         } else {
            //             $('select[name="district_id"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
            //         }
            //     }
            //     if (typeof $('select[name=district_id]').val() !== 'undefined') {
            //         loadWard($('select[name=district_id]').val());
            //     } else if ("{{old('district_id')}}") {
            //         loadWard('{{old('district_id')}}');
            //     } else {
            //         loadWard(msg[0]['id']);

            //     }
            // });
            
    });
       
    </script>
  
@endpush
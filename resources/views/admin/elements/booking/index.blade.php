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
                {{-- <form action="{!! url('admin/booking/exportAdvance') !!}" method="get">
                    <input type="hidden" name="status[]" value="new">
                    <input type="hidden" name="status[]" value="taking">
                    <input type="hidden" name="sub_status[]" value="none"> --}}
                    {{-- <div class="col-lg-8">
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
                    </div> --}}
                    <div class="col-lg-12" style="margin-top: 5px">
                        {{-- <div class="row">
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
                            
                        </div> --}}
                        <div class="row">
                            <div class="col-lg-12" style="margin-top: 5px">
                                {{-- <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-print"
                                            aria-hidden="true"></i>
                                    Xuất dữ liệu
                                </button> --}}
                                {{-- <button type="button" id="view-quick-assign" class="btn btn-primary">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Xem danh sách đã phân công
                                </button> --}}
                                <button type="button" id="quick-assign" class="btn btn-primary">
                                    <i class="fa fa-motorcycle" aria-hidden="true"></i> Phân công đơn hàng
                                </button>
                                
                                <button type="button" class="btn btn-primary " id="btnAddNewBooking"><i class="fa fa-plus"
                                    aria-hidden="true"></i> Thêm mới</button>
                            </div>
                        </div>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
        <div class="col-lg-2 col-md-2" id="status_booking">
            <h5><b>Trạng thái đơn hàng</b></h5>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="all">Tất cả (<span id="status_all"></span>)</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="new">Chờ xử lý (<span id="status_new"></span>)</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="warehouse">Trong kho (<span id="status_warehouse"></span>)</label> </div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="taking">Lấy hàng (<span id="status_taking"></span>)</label></div>
            {{-- <div class=" sub_radio">
                <div> <label><input type="radio" class="option-input radio" name="booking_status" value="taking_doing">Đang lấy hàng</label>  </div>
                <div> <label><input type="radio" class="option-input radio" name="booking_status" value="taking_waiting">Chờ lấy hàng</label></div>
                <div> <label><input type="radio" class="option-input radio" name="booking_status" value="taking_finish">Đã lấy hàng</label></div>
            </div> --}}
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="sending">Giao hàng (<span id="status_sending"></span>)</label> </div>
            {{-- <div class=" sub_radio">
                <div><label><input type="radio" class="option-input radio" name="booking_status" value="sending_doing">Đang giao hàng</label></div>
                <div><label><input type="radio" class="option-input radio" name="booking_status" value="sending_waiting">Chờ giao lại</label></div>
            </div> --}}
            <div ><label><input type="radio" class="option-input radio" name="booking_status" value="completed">Giao thành công (<span id="status_completed"></span>)</label></div>
            <div ><label><input type="radio" class="option-input radio" name="booking_status" value="split">Tách hàng (<span id="status_return"></span>)</label></div>
            <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_transfer">Chuyển hoàn (<span id="status_move"></span>)</label></div>
            {{-- <div class=" sub_radio">
              <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_waiting">Chờ chuyển hoàn</label></div>
              <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_doing">Đang chuyển hoàn</label></div>
              <div><label><input type="radio" class="option-input radio" name="booking_status" value="refund_waiting_again">Chờ chuyển hoàn lại</label></div>
            </div> --}}
            {{-- <div ><label><input type="radio" class="option-input radio" name="booking_status" value="refunded">Đã chuyển hoàn (<span id="status_new"></span>)</label></div> --}}
            <div ><label><input type="radio" class="option-input radio" name="booking_status" value="cancel">Đã huỷ (<span id="status_cancel"></span>)</label></div>
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
    // function loadListBoook(type_assign) {
    //         $.ajax({
    //             type: "GET",
    //             url: "{{ route('get_quick_assign_new') }}",
    //             data: {
    //                 province_id: $('#province').val(),
    //                 district_id: $('#district').val(),
    //                 phone: $('#phone').val(),
    //                 ward_id: $('#ward').val(),
    //                 type_assign: type_assign
    //             },
    //             dataType: "JSON"
    //         }).done(function (msg) {
    //             console.log(msg);
    //             $('#quickAssignModal #ul-shipper tbody').html('');
    //             if (msg.shippers.length > 0) {
    //                 $( msg.shippers ).each(function( index, value ) {
    //                     var shipperLi = '';
    //                     shipperLi += '<tr>';
    //                     if (index == 0) {
    //                         shipperLi += '<td><input type="radio" value="' + value.id + '" name="shipper" checked></td>';
    //                     } else {
    //                         shipperLi += '<td><input type="radio" value="' + value.id + '" name="shipper"></td>';    
    //                     }
    //                     shipperLi += '<td>' + value.name + '</td>';
    //                     shipperLi += '</tr>';
    //                     $('#quickAssignModal #ul-shipper tbody').append(shipperLi);
    //                 });
    //             }
    //             $('#quickAssignModal #ul-book tbody').html('');
    //             if (msg.books.length > 0) {
    //                 $( msg.books ).each(function( index, value ) {
    //                     var bookLi = '';
    //                     bookLi += '<tr>';
    //                     bookLi += '<td><input type="checkbox" value="' + value.id + '" name="books"></td>';
    //                     bookLi += '<td>' + value.uuid + '</td>';
    //                     bookLi += '<td>' + value.name + '</td>';
    //                     bookLi += '<td>' + value.send_name + '</td>';
    //                     bookLi += '<td>' + value.send_phone + '</td>';
    //                     bookLi += '<td>' + value.send_full_address + '</td>';
    //                     bookLi += '<td>' + value.shipper_name + '</td>';
    //                     bookLi += '</tr>';
    //                     $('#quickAssignModal #ul-book tbody').append(bookLi);
    //                 });
    //             }
    //             $('#quickAssignModal').modal('show');
    //         });
    // }
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
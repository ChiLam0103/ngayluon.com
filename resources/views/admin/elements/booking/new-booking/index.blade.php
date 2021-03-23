@extends('admin.app')

@section('title')
    Đơn hàng mới
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
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12">
            @include('admin.table_paging', [
                'id' => 'new_booking',
                'title' => [
                        'caption' => 'Dữ liệu đơn hàng mới',
                        'icon' => 'fa fa-table',
                        'class' => 'portlet box green',
                ],
                'url' => url("/ajax/new_booking"),
                'columns' => [
                        ['data' => 'action', 'title' => 'Hành động', 'orderable' => false],
                        ['data' => 'image_order', 'title' => 'Ảnh đơn hàng'],
                        ['data' => 'report_image', 'title' => 'Ảnh báo cáo'],
                        ['data' => 'uuid', 'title' => 'UUID'],
                        ['data' => 'created_at', 'title' => 'Ngày tạo'],
                        ['data' => 'receive_created_at', 'title' => 'Ngày đi lấy', 'orderable' => false, 'searchable' => false],
                        ['data' => 'user_create', 'title' => 'Người tạo đơn', 'orderable' => false, 'searchable' => false],
                        ['data' => 'name', 'title' => 'Tên đơn hàng'],
                        ['data' => 'send_name', 'title' => 'Người gửi'],
                        ['data' => 'send_phone', 'title' => 'Số điện thoại'],
                        ['data' => 'send_full_address', 'title' => 'Địa chỉ'],
                        ['data' => 'receive_name', 'title' => 'Người nhận'],
                        ['data' => 'receive_phone', 'title' => 'Số điện thoại'],
                        ['data' => 'receive_full_address', 'title' => 'Địa chỉ'],
                        ['data' => 'weight', 'title' => 'Khối lượng(gram)'],
                        // ['data' => 'transport_type', 'title' => 'Phương thức vận chuyển'],
                        ['data' => 'price', 'title' => 'Giá'],
                        ['data' => 'incurred', 'title' => 'Chi phí phát sinh'],
                        ['data' => 'paid', 'title' => 'Số tiền đã thanh toán'],
                        ['data' => 'COD', 'title' => 'Thu hộ'],
                        ['data' => 'status', 'title' => 'Trạng thái'],
                        ['data' => 'payment_type', 'title' => 'Ghi chú'],
                        ['data' => 'other_note', 'title' => 'Ghi chú khác'],
                        ['data' => 'note', 'title' => 'Ghi chú hệ thống'],
                        ['data' => 'shipper', 'title' => 'Tên Shipper'],
                ]
                ])
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
<!-- Modal image booking -->
    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Ảnh đơn hàng <span id="alt_uuid_booking"></span></span></h4>
            </div>
            <div class="modal-body">
            <img src="" id="imagepreview"  >
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
        </div>
    </div>
    <!-- Modal detail booking -->
    <div class="modal fade" id="detailBooking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Chi tiết đơn hàng <span id="uuid_booking"></span></span></h4>
                </div>
                <div class="modal-body">
                    <h3 style="color: blue;"><b>Thông tin khách hàng</b></h3>
                    <div class="info-send col-md-6">
                        <h4><b>Người gửi</b></h4>
                        <div class="content"></div>
                    </div>
                    <div class="info-receive col-md-6">
                        <h4><b>Người nhận</b></h4>
                        <div class="content"></div>
                    </div>
                    <h3 style="color: blue;"><b>Thông tin cơ bản</b></h3>
                    <div class="info-booking col-md-6">
                        <h4><b>Đơn hàng</b></h4>
                        <div class="content"></div>
                    </div>
                    <div class="info-log col-md-6">
                        <h4><b>Log đơn hàng</b></h4>
                        <div class="content"></div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:none">
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}

@endsection
@push('script')
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'showImageNumberLabel': true,
        });
        function loadWard(id) {
            $("#ward option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/get_ward/')}}/' + id
            }).done(function (msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{@$user->ward_id}}' || msg[i]['id'] == '{{old('ward_id')}}') {
                        $('select[name="ward_id"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="ward_id"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
                    }
                }
            });

        }
        setTimeout(function(){ $('[data-toggle="popover"]').popover(); }, 1000);


        function changeUrl(data) {
            var href = $('#owe_submit').attr('href');
            if ($('#owe').is(':checked')){
                if (href.indexOf('?owe=0') > -1){
                    $('#owe_submit').attr("href", href.replace('?owe=0', '?owe=1'));
                }else{
                    $('#owe_submit').attr("href", href+'?owe=1');
                }
            }else {
                $('#owe_submit').attr("href",  href.replace('?owe=1', '?owe=0'));
            }
        }

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
            var province = 50;
            $("#district option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: "{{url('/ajax/get_district/')}}/" + province
            }).done(function (msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{@$user->district_id}}' || msg[i]['id'] == '{{old('district_id')}}') {
                        $('select[name="district_id"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="district_id"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
                    }
                }
                if (typeof $('select[name=district_id]').val() !== 'undefined') {
                    loadWard($('select[name=district_id]').val());
                } else if ("{{old('district_id')}}") {
                    loadWard('{{old('district_id')}}');
                } else {
                    loadWard(msg[0]['id']);

                }
            });
            $("#quick-assign").click(function(){
                $("#type-assign").val('no_assign');
                loadListBoook('no_assign');
            });

            $( "#type-assign" ).change(function() {
                loadListBoook($(this).val());
            });

            $('#save-quick-assign').click(function(e){
                $.ajax({
                    type: "POST",
                    url: "{{ route('post_quick_assign_new') }}",
                    data: {
                        inputs: $('#form-quick-assign').serializeArray(),
                        _token: $("input[name='_token']").val(),
                        type_assign: $('#type-assign').val()
                    },
                    dataType: "JSON"
                }).done(function (msg) {
                    if (msg.status == 'success') {
                        $('#quickAssignModal').modal('hide');
                        location.reload();
                    } else {
                        $('#msg-error').html(msg.status);
                    }
                });
            })

            $('#check-all').change(function(){
                var checkboxes = $(this).closest('form').find(':checkbox');
                if($(this).prop('checked')) {
                  checkboxes.prop('checked', true);
                } else {
                  checkboxes.prop('checked', false);
                }
            });
        });
        //show img booking
        $(document).on('click','.img_booking',function(){
            $('#imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#alt_uuid_booking').text($(this).find('img').attr("alt"));
            $('#imagemodal').modal('show');   
        });
        //show detail booking
        $(document).on('click','.uuid',function(){
            var id=  $(this).attr("name");
            var empty='';
            $('#detailBooking .modal-body .content p').remove();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/detail_booking/')}}/' + id
            }).done(function (msg) {
                var payer= msg.booking.payment_type == 1 ? 'người gửi' :'người nhận';
                var status= msg.booking.status == 'new' ? 'mới' :'đang lấy';
                var shipper = msg.shipper !=null ? '<p>Shipper lấy đơn: '+msg.shipper +'</p>' :'';
                var content_send="<p>Họ tên: "+ msg.booking.send_name+"</p> <p>Số điện thoại: "+ msg.booking.send_phone+"</p> <p>Địa chỉ: "+ msg.booking.send_full_address+"</p>";
                var content_receive="<p>Họ tên: "+ msg.booking.receive_name+"</p> <p>Số điện thoại: "+ msg.booking.receive_phone+"</p> <p>Địa chỉ: "+ msg.booking.receive_full_address+"</p>";
                var content_booking="<p>Tên đơn hàng: "+ msg.booking.name+"</p> <p>Tiền thu hộ: "+ msg.booking.COD +"</p> </p> <p>Giá đơn hàng: "+ msg.booking.price +"</p>  </p> <p>Chi phí phát sinh: "+ msg.booking.incurred +"</p> <p>Số tiền thanh toán: "+ msg.booking.paid +"</p> <p>Khối lượng (gram): "+ msg.booking.weight +"</p>  <p>Ghi chú khách hàng: "+ msg.booking.other_note +"</p> <p>Ghi chú hệ thống: "+ msg.booking.note +"</p> <p>Trả cước: "+payer+"</p><p>Trạng thái: "+status+"</p> "+shipper+""
                var content_log="";
                $( msg.log ).each(function( index, value ) {
                        if(value.type_detail == "book_detail"){
                            content_log+= "<p>"+ value.title+" - "+value.created_at+"</p> "
                        }
                });
                $('#uuid_booking').text(msg.booking.uuid);
                $('#detailBooking .modal-body .info-send .content').append(content_send);
                $('#detailBooking .modal-body .info-receive .content').append(content_receive);
                $('#detailBooking .modal-body .info-booking .content').append(content_booking);
                $('#detailBooking .modal-body .info-log .content').append(content_log);
            });
            $('#detailBooking').modal('show');   
        });
    </script>
@endpush
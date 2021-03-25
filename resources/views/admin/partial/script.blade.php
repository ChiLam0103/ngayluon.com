<script src="{{asset('public/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{asset('public/assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/morris/morris.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/counterup/jquery.waypoints.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/counterup/jquery.counterup.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amcharts/amcharts.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amcharts/serial.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amcharts/pie.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amcharts/radar.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amcharts/themes/light.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amcharts/themes/patterns.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amcharts/themes/chalk.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/ammap/ammap.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/amcharts/amstockcharts/amstock.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/fullcalendar/fullcalendar.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/horizontal-timeline/horizontal-timeline.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/flot/jquery.flot.resize.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js')}}" type="text/javascript"></script>
<script src="{{asset('public/bower_components/lightbox2/src/js/lightbox.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="{{asset('public/assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{asset('public/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{asset('public/assets/pages/scripts/dashboard.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{asset('public/assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>
<script src="{{asset('public/assets/layouts/global/scripts/quick-nav.min.js')}}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script type="text/javascript" charset="utf8" src="{{ asset('public/js/jquery.dataTables.js') }}"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script src="{{ asset('public/js/bootstrap-select.min.js') }}"></script>

<!-- <script src="{{asset('/js/select2.min.js')}}"></script> -->

<script>
    $(document).ready(function()
    {
        $('#clickmewow').click(function()
        {
            $('#radio1003').attr('checked', 'checked');
        });

        // lấy thông báo
        connectNotification()
        // pushNotification();
    })

    function connectNotification() {
        $.ajax({
            url: "{{ url('ajax/notifications') }}",
            method: "get"
        }).done(function( res ) {
            if (res.notifications.length > 0) {
                $('#count-notification').html(res.countNotReaded);
                showNotification(res.notifications);     
            }
        });
    }

    function showNotification(res) {
        var str = '';
        $( res ).each(function( index, value ) {
            str += templeteNotification(value);
        });
        $('#list-notification').html(str);
    }

    function templeteNotification(value) {
        var str = '';
        var title = '<b>' + value.title + '</b>';
        var href = '{{ url("admin/booking/new") }}';
        if (value.is_readed == 1) {
            title = value.title;
        }
        if (title.indexOf('vừa được hủy') != -1) {
            href = '{{ url("admin/booking/cancel") }}';
        }
        str += '<li>';
            str += '<a href="' + href + '?notification_id=' + value.notification_id + '&is_readed=1">';
                // str += '<span class="time">3 mins</span>';
                str += '<span class="details">';
                    str += '<span class="label label-sm label-icon label-danger">';
                        str += '<i class="fa fa-bolt"></i>';
                    str += '</span>';
                    str += title;
                str += '</span>';
            str += '</a>';
        str += '</li>';
        return str;
    }

    // function pushNotification() {
    //     // Pusher.logToConsole = true;
    //     var pusher = new Pusher('dcad7c34effbe5194bf6', {
    //         cluster: 'ap1',
    //         encrypted: true
    //         // forceTLS: true
    //     });

    //     var channel = pusher.subscribe('my-channel');
    //     var notificationChannel = pusher.subscribe('notification-channel');

    //     channel.bind('App\\Events\\HelloPusherEvent', function(data) {
    //         console.log('test');
    //         console.log(data);
    //     });
    //     notificationChannel.bind('App\\Events\\NotificationPusherEvent', function(data) {
    //         console.log(data.message);
    //         var countNotificationCurrent = 0;
    //         if ($('#count-notification').html() != '') {
    //             countNotificationCurrent = parseInt($('#count-notification').html()) + 1;
    //         }
    //         $('#count-notification').html(countNotificationCurrent);
    //         var str = templeteNotification(data.message);
    //         if (countNotificationCurrent == 1) {
    //             $('#list-notification').append(str);
    //         } else {
    //             $('#list-notification > li').first().before(str);
    //         }
    //     });
    // }
    function formatCurrency(number){
        var n = number.split('').reverse().join("");
        var n2 = n.replace(/\d\d\d(?!$)/g, "$&,");    
        return  n2.split('').reverse().join('') + 'VNĐ';
    }
    function formatNumber(nStr, decSeperate, groupSeperate) {
            nStr += '';
            x = nStr.split(decSeperate);
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
            }
            return x1 + x2;
        }
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
                console.log(msg.shipper);
                var payer= msg.booking.payment_type == 1 ? 'người gửi' :'người nhận';
                var shipper = msg.shipper != null ? '<p>Shipper lấy đơn: '+msg.shipper.shipper_name +'</p>' :'';
                var content_send="<p>Họ tên: "+ msg.booking.send_name+"</p> <p>Số điện thoại: "+ msg.booking.send_phone+"</p> <p>Địa chỉ: "+ msg.booking.send_full_address+"</p>";
                var content_receive="<p>Họ tên: "+ msg.booking.receive_name+"</p> <p>Số điện thoại: "+ msg.booking.receive_phone+"</p> <p>Địa chỉ: "+ msg.booking.receive_full_address+"</p>";
                var content_booking="<p>Tên đơn hàng: "+ msg.booking.name+" -- Ngày tạo: "+msg.booking.created_at+"</p> <p>Tiền thu hộ: "+ formatNumber(msg.booking.COD, '.', ',') +"</p> </p> <p>Giá đơn hàng: "+ formatNumber(msg.booking.price, '.', ',') +"</p>  </p> <p>Chi phí phát sinh: "+ formatNumber(msg.booking.incurred, '.', ',') +"</p> <p>Số tiền thanh toán: "+ formatNumber(msg.booking.paid, '.', ',') +"</p> <p>Khối lượng (gram): "+ formatNumber(msg.booking.weight, '.', ',') +"</p>  <p>Ghi chú khách hàng: "+ msg.booking.other_note +"</p> <p>Ghi chú hệ thống: "+ msg.booking.note +"</p> <p>Trả cước: "+payer+"</p><p>Trạng thái: "+msg.booking.status+"</p> "+shipper+""
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

@stack('script')
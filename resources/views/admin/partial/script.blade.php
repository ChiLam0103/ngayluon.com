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

    //format input date, formatCurrency,formatNumber    
    $("#mask_date").inputmask("y/m/d", {
        autoUnmask: true
    });
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

    //function check formart email, phone
    function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
    }
    function checkPhoneNumber(phone) {
            var flag = false;
            phone = phone.replace('(+84)', '0');
            phone = phone.replace('+84', '0');
            phone = phone.replace('0084', '0');
            phone = phone.replace(/ /g, '');
            if (phone != '') {
                var firstNumber = phone.substring(0, 2);
                if ((firstNumber == '09' || firstNumber == '08' || firstNumber == '07' || firstNumber == '05' || firstNumber == '03') && phone.length == 10) {
                    if (phone.match(/^\d{10}/)) {
                        flag = true;
                    }
                } else if (firstNumber == '02' && phone.length == 11) {
                    if (phone.match(/^\d{11}/)) {
                        flag = true;
                    }
                }
            }
            return flag;
    }  

    //direct mask
    $('#blah').hide();
    loadDistrict();
    if ($('#oldInputFile').val()) {
        $('#blah').attr('src', '{!! url('/') !!}/' + $('#oldInputFile').val());
        $('#blah').show();
    }

    //load list districct & ward
    function loadDistrict() {
            var province = $('#province').val();
            $("#district option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{ url('/ajax/get_district/') }}/' + province
            }).done(function(msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{ @$user->district_id }}' || msg[i]['id'] ==
                        '{{ old('district_id') }}') {
                        $('select[name="district_id"]').append('<option value="' + msg[i]['id'] + '" selected>' +
                            msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="district_id"]').append('<option value="' + msg[i]['id'] + '">' + msg[i][
                            'name'
                        ] + '</option>')
                    }
                }
                if (typeof $('select[name=district_id]').val() !== 'undefined') {
                    loadWard($('select[name=district_id]').val());
                } else if ("{{ old('district_id') }}") {
                    loadWard('{{ old('district_id') }}');
                } else {
                    loadWard(msg[0]['id']);

                }
            });
    }

    function loadWard(id) {
            $("#ward option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{ url('/ajax/get_ward/') }}/' + id
            }).done(function(msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{ @$user->ward_id }}' || msg[i]['id'] == '{{ old('ward_id') }}') {
                        $('select[name="ward_id"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]
                            ['name'] + '</option>')
                    } else {
                        $('select[name="ward_id"]').append('<option value="' + msg[i]['id'] + '">' + msg[i][
                            'name'
                        ] + '</option>')
                    }
                }
            });
    }

    //show image
    function readURL(input) {
        $('#blah').hide();
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#exampleInputFile").change(function() {
        readURL(this);
        $('#blah').show();
    });
   
   // modal show img avatar user
    $(document).on('click','.img_modal',function(){
        $('#imagepreview').attr('src', $(this).find('img').attr('src'));
        $('.modal-title').text('Ảnh chi tiết');
        $('#imageModal').modal('show');   
    });
   // modal show detail booking
    $(document).on('click','.uuid',function(){
        var id=  $(this).attr("name");
        var empty='';
        $('#detailBooking .modal-body .content p').remove();
        $.ajax({
            type: "GET",
            url: '{{url('/ajax/detail_booking/')}}/' + id
        }).done(function (msg) {
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

   // modal show  add new user  
    $(document).on('click','#btnAddNew',function(){
        $('.modal-title').text('Thêm thông tin người dùng');
        $('#modalUser .action').attr('id','addUser');
        $('#modalUser').modal('show');  
        $("input[name=id]").val('');
        $("input[name=uuid]").val('');
        $("input[name=name]").val('');
        $("input[name=email]").val('');
        $("input[name=phone_number]").val('');
        $("input[name=birth_day]").val('');
        $("input[name=id_number]").val('');
        $("input[name=home_number]").val('');
        $("input[name=bank_account]").val('');
        $("input[name=bank_account_number]").val('');
        $("input[name=bank_name]").val('');
        $("input[name=bank_branch]").val('');
        $('.imgUser').attr('src','' );
        $("input[name=email]").removeAttr("disabled")
        $("input[name=phone_number]").removeAttr("disabled")
            
    });  
 //   show edit booking
    $(document).on('click','.edit_user',function(){
        var id=  $(this).attr("name");
        $('.modal-title').text('Chỉnh sửa thông tin người dùng');
        $('#modalUser .action').attr('id','editUser');
        $('#modalUser').modal('show');   
        $.ajax({
            type: "GET",
            url: "{!! url('/ajax/detail_user/') !!}/" + id,
            }).done(function (data) {
                $("input[name=id]").val(data.user.id);
                $("input[name=uuid]").val(data.user.uuid);
                $("input[name=name]").val(data.user.name);
                $("input[name=email]").val(data.user.email);
                $("input[name=phone_number]").val(data.user.phone_number);
                $("input[name=birth_day]").val(data.user.birth_day);
                $('#province option[value="'+data.user.province_id+'"]').prop('selected', true);
                $('#district option[value="'+data.user.district_id+'"]').prop('selected', true);
                $('#ward option[value="'+data.user.ward_id+'"]').prop('selected', true);
                $("input[name=id_number]").val(data.user.id_number);
                $("input[name=home_number]").val(data.user.home_number);
                $("input[name=bank_account]").val(data.user.bank_account);
                $("input[name=bank_account_number]").val(data.user.bank_account_number);
                $("input[name=bank_name]").val(data.user.bank_name);
                $("input[name=bank_branch]").val(data.user.bank_branch);
                $('.imgUser').attr('src','{!! url('/') !!}' + data.user.avatar );
                $('input[name=is_advance_money][value="'+data.user.is_advance_money+'"]').prop( 'checked', 'checked');
                $('#uuid_err').text('');
                $('#name_err').text('');
                $('#email_err').text('');
                $('#phone_number_err').text('');
                $('#birth_day_err').text('');
                $('#id_number_err').text('');
                $('#home_number_err').text('');
                $('#cf_password_err').text('');
                $('#email_err').text('');
                $('#password_err').text('');
                $('#cf_password_err').text('');
                $("input[name=email]").attr("disabled","disabled");
                $("input[name=phone_number]").attr("disabled","disabled");
        });       
    }); 

    function actionUser( role , action) {
        var id =$("input[name=id]").val();
        var name =$("input[name=name]").val();
        var email =$("input[name=email]").val();
        var phone_number =$("input[name=phone_number]").val();
        var password =$("input[name=password]").val();
        var cf_password = $("#cf_password").val();
        var birth_day =$("input[name=birth_day]").val();
        var province_id =$("#province option:selected").val();
        var district_id =$("#district option:selected").val();
        var ward_id =$("#ward option:selected").val();
        var id_number =$("input[name=id_number]").val();
        var home_number =$("input[name=home_number]").val();
        var bank_account =$("input[name=bank_account]").val();
        var bank_account_number =$("input[name=bank_account_number]").val();
        var bank_name =$("input[name=bank_name]").val();
        var bank_branch =$("input[name=bank_branch]").val();
        var avatar =  $("#exampleInputFile")[0].files[0];
        var is_advance_money = $('input[name=is_advance_money]:checked').val();
        
        var required ="Trường dữ liêu bắt buộc";
        var email_err ="Trường dữ liêu không đúng định dạng email";
        var phone_err ="Trường dữ liêu không đúng định dạng sdt";
        var unique ="Dữ liệu đã tồn tại";
        var cf_password_err ="Mật khẩu không trùng khớp";        var flag = 0;         (name == '') ? ($(' #name_err').text(required)) && (flag = 1) : ($(' #name_err').text(''));
        (email == '') ? ($(' #email_err').text(required)) && (flag = 1) : ($(' #email_err').text(''));
        (phone_number == '') ? ($(' #phone_number_err').text(required)) && (flag = 1) : ($(' #phone_number_err').text(''));
        
        (birth_day == '') ? ($(' #birth_day_err').text(required)) && (flag = 1) : ($(' #birth_day_err').text(''));
        (id_number == '') ? ($(' #id_number_err').text(required)) && (flag = 1) : ($(' #id_number_err').text(''));
        (home_number == '') ? ($(' #home_number_err').text(required)) && (flag = 1) : ($(' #home_number_err').text(''));
        (password != cf_password) ? ($(' #cf_password_err').text(cf_password_err)) && (flag = 1) : ($(' #cf_password_err').text(''));
        (isEmail(email) == false) ? ($(' #email_err').text(email_err)) && (flag = 1) : ($(' #email_err').text(''));
        (checkPhoneNumber(phone_number) == false) ? ($(' #phone_number_err').text(phone_err)) && (flag = 1) : ($(' #phone_number_err').text(''));
         
           if(id == "#modalUser #addUser"){
                (password == '') ? ($(' #password_err').text(required)) && (flag = 1) : ($(' #password_err').text(''));
                (cf_password == '') ? ($(' #cf_password_err').text(required)) && (flag = 1) : ($(' #cf_password_err').text(''));
            }
            if(action == "store"){

                $.ajax({
                type: "GET",
                url: "{!! url('/ajax/check_exist_user') !!}",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: {
                    email: email,
                    phone_number: phone_number,
                    _token: $('[name=_token]').val()
                }                
                }).done(function (data) {
                    var dataItems = "";
                    $.each(data, function (index, itemData) {
                        switch(itemData){
                            case "email_err":
                                $(' #email_err').text(unique);
                                flag = 1; 
                                break;
                            case "phone_number_err":
                                $(' #phone_number_err').text(unique);
                                flag = 1; 
                                break;
                        }
                    });
                });
            }

            if(flag == 1){
                return false;
            }else{
                var formData = new FormData()
                formData.append('_token', $(' [name=_token]').val());
                formData.append('id', id);
                formData.append('name', name);
                formData.append('email', email);
                formData.append('phone_number', phone_number);
                formData.append('password', password);
                formData.append('birth_day', birth_day);
                formData.append('province_id', province_id);
                formData.append('district_id', district_id);
                formData.append('ward_id', ward_id);
                formData.append('id_number', id_number);
                formData.append('home_number', home_number);
                formData.append('bank_account', bank_account);
                formData.append('bank_account_number', bank_account_number);
                formData.append('bank_name', bank_name);
                formData.append('bank_branch', bank_branch);
                formData.append('avatar', avatar);
                formData.append('is_advance_money', is_advance_money);
                formData.append('role', role);
                formData.append('action', action);

                $.ajax({
                    type: "post",
                    url: "{!! url('/ajax/action_user') !!}",
                    processData: false,
                    contentType: false,
                    data:formData
                }).done(function (res) {
                    location.reload();
                });
        }
    }   
    
</script>

@stack('script')
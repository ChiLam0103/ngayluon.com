@extends('admin.app')

@section('title')
    Admins
@endsection

@section('sub-title')
    danh sách
@endsection

@section('content')
    <div class="row">
        @include('admin.partial.log.err_log',['name' => 'delete'])
        @include('admin.partial.log.success_log',['name' => 'success'])
        <div class="well" style="padding-left: 0px">
            <button type="button" class="btn btn-success " data-toggle="modal" data-target="#addNew"><i class="fa fa-plus"
                    aria-hidden="true"></i> Thêm mới</button>
        </div>
        <div class="col-lg-12">
            @include('admin.table', [
            'id' => 'admin',
            'title' => [
            'caption' => 'Dữ liệu admins',
            'icon' => 'fa fa-table',
            'class' => 'portlet box green',
            ],
            'url' => url("/ajax/admins"),
            'columns' => [
            ['data' => 'name', 'title' => 'Tên admins'],
            ['data' => 'avatar', 'title' => 'Ảnh đại diện'],
            ['data' => 'email', 'title' => 'Email'],
            ['data' => 'full_address', 'title' => 'Địa chỉ'],
            ['data' => 'phone_number', 'title' => 'Hotline'],
            ['data' => 'action', 'title' => 'Hành động', 'orderable' => false]
            ]
            ])
        </div>
    </div>
    <!-- Modal store booking -->
    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Thêm admin <span id="alt_uuid_booking"></span></span>
                    </h4>
                </div>
                {{ Form::open(['url' => '/admin/admins', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                <input type="file" name="avatar" value="{!! @$user->avatar !!}" id="exampleInputFile">
                                <input type="hidden" name="old_avatar" value="public/{!! @$user->avatar !!}"
                                    id="oldInputFile">
                                <img style="margin-top: 5px" id="blah" src="#" alt="your image" width="100px" />
                                @if ($errors->has('avatar'))
                                    @foreach ($errors->get('avatar') as $error)
                                        <span style="color: red" class="help-block">{!! $error !!}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class=" form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Mã *</label>
                                        <input class="form-control spinner" value="{{ old('uuid', @$user->uuid) }}"
                                            name="uuid" type="text" placeholder="Nhập mã">
                                        <span style="color: red;" id="uuid_err" class="help-block"></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Họ tên *</label>
                                        <input class="form-control spinner" value="{{ old('name', @$user->name) }}"
                                            name="name" type="text" placeholder="Nhập tên">
                                        <span style="color: red;" id="name_err" class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class=" form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label">Email</label>
                                        <div class="input-group">
                                            <input type="email" value="{{ old('email', @$user->email) }}"
                                                class="form-control" placeholder="Địa chỉ email" name="email">
                                            <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                        </div>
                                        <span style="color: red;" id="email_err" class="help-block"></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Số điện thoại</label>
                                        <input name="phone_number"
                                            value="{{ old('phone_number', @$user->phone_number) }}"
                                            class="form-control spinner" type="text" placeholder="Nhập số điện thoại">
                                            <span style="color: red;" id="phone_number_err" class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Mật khẩu</label>
                                        <input class="form-control spinner" value="{{ old('password') }}" name="password"
                                            type="password" placeholder="Nhập mật khẩu">
                                            <span style="color: red;" id="password_err" class="help-block"></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Nhập lại mật khẩu</label>
                                        <input class="form-control spinner" value="" name="cf-password" type="password"
                                            placeholder="Nhập mật khẩu">
                                            <span style="color: red;" id="cf_password_err" class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label">Ngày sinh</label>
                                        <input name="birth_day" value="{{ old('birth_day', @$user->birth_day) }}"
                                            class="form-control" id="mask_date" type="text" />
                                        <span class="help-block">Năm/Tháng/Ngày</span>
                                        <span style="color: red;" id="birth_day_err" class="help-block"></span>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Số CMND</label>
                                        <input class="form-control spinner"
                                            value="{{ old('id_number', @$user->id_number) }}" name="id_number"
                                            placeholder="Nhập số CMND" type="number">
                                            <span style="color: red;" id="id_number_err" class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row" style="margin-top: 15px">
                                    <div class="col-lg-6">
                                        <label>Tỉnh/Thành phố</label>
                                        {{ Form::select('province_id', \App\Models\Province::getProvinceOption(), old('province_id', @$user->province_id), ['class' => 'form-control', 'style' => 'width:100%', 'id' => 'province', 'onchange' => 'loadDistrict()']) }}
                                        @if (isset($errors) && $errors->has('province_id_fr'))
                                            @foreach ($errors->get('province_id_fr') as $error)
                                                <div class="note note-error">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Quận/Huyện</label>
                                        <select id="district" onchange="loadWard(this.value)" name="district_id"
                                            class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 15px">
                                    <div class="col-lg-6">
                                        <label>Xã/Phường</label>
                                        <select id="ward" name="ward_id" class="form-control">
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Số nhà / tên đường</label>
                                        <input name="home_number" class="form-control spinner"
                                            value="{!! @$user->home_number !!}" type="text" placeholder="Nhập số nhà">
                                            <span style="color: red;" id="home_number_err" class="help-block"></span>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Tên tài khoản ngân hàng</label>
                                        <input name="bank_account" value="{{ old('bank_account', @$user->bank_account) }}"
                                            class="form-control spinner" type="text" placeholder="Nhập tên tài khoản">
                                            <span style="color: red;" id="bank_account_err" class="help-block"></span>

                                    </div>

                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Số tài khoản</label>
                                        <input name="bank_account_number"
                                            value="{{ old('bank_account_number', @$user->bank_account_number) }}"
                                            class="form-control spinner" type="number" placeholder="Nhập số tài khoản">
                                            <span style="color: red;" id="bank_account_number_err" class="help-block"></span>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Tên ngân hàng</label>
                                        <input name="bank_name" value="{{ old('bank_name', @$user->bank_name) }}"
                                            class="form-control spinner" type="text" placeholder="Nhập tên ngân hàng">
                                            <span style="color: red;" id="bank_name_err" class="help-block"></span>

                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Nhánh ngân hàng</label>
                                        <input name="bank_branch" value="{{ old('bank_branch', @$user->bank_branch) }}"
                                            class="form-control spinner" type="text" placeholder="Nhập chi nhánh">
                                            <span style="color: red;" id="bank_branch_err" class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn blue" id="btn-save"><i class="fa fa-floppy-o" aria-hidden="true" ></i> Lưu</button>
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"
                            aria-hidden="true"></i> Bỏ qua</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $("#mask_date").inputmask("y/m/d", {
            autoUnmask: true
        }); //direct mask

        $('#blah').hide();
        loadDistrict();
        if ($('#oldInputFile').val()) {
            $('#blah').attr('src', '{!! url('/') !!}/' + $('#oldInputFile').val());
            $('#blah').show();
        }

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
        $("#btn-save").on("click", function (event) {
            store();
        })
        function store() {
            var uuid =$("input[name=uuid]").val();
            var name =$("input[name=name]").val();
            var email =$("input[name=email]").val();
            var phone_number =$("input[name=phone_number]").val();
            var password =$("input[name=password]").val();
            var cf_password =$("input[name=cf-password]").val();
            var birth_day =$("input[name=birth_day]").val();
            var id_number =$("input[name=id_number]").val();
            var home_number =$("input[name=home_number]").val();

            var required ="Trường dữ liêu bắt buộc";
            var email_err ="Trường dữ liêu không đúng định dạng email";
            var phone_err ="Trường dữ liêu không đúng định dạng sdt";
            var unique ="Dữ liệu đã tồn tại";
            var cf_password ="Mật khẩu không trùng khớp";
            var flag = 0; 

            (uuid == '') ? ($('#uuid_err').text(required)) && (flag = 1) : ($('#uuid_err').text(''));
            (name == '') ? ($('#name_err').text(required)) && (flag = 1) : ($('#name_err').text(''));
            (email == '') ? ($('#email_err').text(required)) && (flag = 1) : ($('#email_err').text(''));
            (phone_number == '') ? ($('#phone_number_err').text(required)) && (flag = 1) : ($('#phone_number_err').text(''));
            (password == '') ? ($('#password_err').text(required)) && (flag = 1) : ($('#password_err').text(''));
            (cf_password == '') ? ($('#cf_password_err').text(required)) && (flag = 1) : ($('#cf_password_err').text(''));
            (birth_day == '') ? ($('#birth_day_err').text(required)) && (flag = 1) : ($('#birth_day_err').text(''));
            (id_number == '') ? ($('#id_number_err').text(required)) && (flag = 1) : ($('#id_number_err').text(''));
            (home_number == '') ? ($('#home_number_err').text(required)) && (flag = 1) : ($('#home_number_err').text(''));
            
            (checkEmail(email) == 1) ? ($('#email_err').text(email_err)) && (flag = 1) : ($('#email_err').text());
            // if(checkPhoneNumber(phone_number) == 1){
            //     console.log(123);
            //     $('#phone_number_err').text(phone_err);
            //      flag = 1;
            // }
            if(flag == 1){
                return false;
            }
           
            

            $.ajax({
                type: "POST",
                url: "{!! url('/front-ent/login') !!}",
                data: {
                    phone: phone,
                    password_code: password_code,
                    _token: $('[name=_token]').val()
                }
            }).done(function (res) {
                if (res == false) {
                    $('#login_err_password_code').text('Mật khẩu không đúng');
                    return false;
                }
                location.reload();
                // $('#form-login').submit();
            });
        }
        function checkEmail(email) { 
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/; 
            if (!filter.test(email.value)) 
                    return 1; 
            else
                return 0; 
        } 
        // function checkPhoneNumber(phone)
        // {
        //     var phoneno = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
        //     if(!phone.value.match(phoneno))
        //         return 1; 
        //     else
        //         return 0; 
        // }
    </script>
@endpush

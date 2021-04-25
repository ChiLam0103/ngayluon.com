<div class="modal fade" id="modalBooking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg action" id="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title"></span>
                </h4>
            </div>
            {{ Form::open(['url' => '', 'method' => '', 'enctype' => 'multipart/form-data']) }}
            <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}">
            <input type="hidden" id="id" name="id">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="control-label"></label>
                            <input type="file" name="avatar" value="{!! @$user->avatar !!}" id="exampleInputFile"
                                accept=".jpg, .jpeg, .png">
                            <input type="hidden" name="old_avatar" value="public/{!! @$user->avatar !!}"
                                id="oldInputFile">
                            <img class="imgUser" style="margin-top: 5px" id="blah" src="#" alt="your image"
                                width="100px" />
                            @if ($errors->has('avatar'))
                                @foreach ($errors->get('avatar') as $error)
                                    <span style="color: red" class="help-block">{!! $error !!}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="portlet-body form">
                            <legend>Thông tin khách hàng</legend>
                            <div class="row">
                                <div class="col-lg-6">
                                    <legend>Người gửi</legend>
                                </div>
                                <div class="col-lg-6">
                                    <legend>Người nhận</legend>
                                </div>
                            </div>
        
                            <div class="row" style="margin-bottom: 15px">
                                <div class="{{--has-error--}} form-group">
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Họ tên</label>
                                        {{ Form::select('name_id_fr', \App\Models\User::getCustomerOption() , old('name_id_fr'),
                                        ['class' => 'form-control', 'style' => 'width:100%', 'id'=>'name_id_fr', 'onchange'=>'loadCustomerFr()']) }}
                                       @if (isset($errors) && $errors->has('name_id_fr'))
                                           @foreach ($errors->get('name_id_to') as $error)
                                               <span style="color: red" class="help-block">{!! $error !!}</span>
                                           @endforeach
                                       @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Họ tên</label>
                                        <input class="form-control spinner" value="{{ old( 'name_to') }}"
                                               name="name_to" type="text" placeholder="Nhập tên">
                                        <span style="color: red;" id="name_to_err" class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: 15px">
                                <div class="{{--has-error--}} form-group">
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Số điện thoại</label>
                                        <input name="phone_number_fr" id="phone_number_fr"
                                               value="{{ old( 'phone_number_fr') }}"
                                               class="form-control spinner" type="text"
                                               placeholder="Nhập số điện thoại" disabled>
                                        @if ($errors->has('phone_number_fr'))
                                            @foreach ($errors->get('phone_number_fr') as $error)
                                                <span style="color: red" class="help-block">{!! $error !!}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="control-label" for="inputError">Số điện thoại</label>
                                        <input name="phone_number_to"
                                               value="{{ old( 'phone_number_to') }}"
                                               class="form-control spinner" type="text"
                                               placeholder="Nhập số điện thoại">
                                        <span style="color: red;" id="phone_number_to_err" class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>Tỉnh/Thành phố</label>
                                    {{ Form::select('province_id_fr', \App\Models\Province::getProvinceOption() , old('province_id_fr'), ['class' => 'form-control', 'style' => 'width:100%', 'id'=>'province_fr', 'onchange'=>'loadDistrictFrom()', 'disabled'=>'disabled']) }}
                                    @if (isset($errors) && $errors->has('province_id_fr'))
                                        @foreach ($errors->get('province_id_fr') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <label>Quận/Huyện</label>
                                    <select id="district_fr" onchange="loadWardFrom(this.value)"
                                            name="district_id_fr"
                                            class="form-control" disabled>
                                    </select>
                                    @if (isset($errors) && $errors->has('district_id_fr'))
                                        @foreach ($errors->get('district_id_fr') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <label>Tỉnh/Thành phố</label>
                                    {{ Form::select('province_id_to', \App\Models\Province::getProvinceOption() , old('province_id_to'),
                                     ['class' => 'form-control', 'style' => 'width:100%', 'id'=>'province_to', 'onchange'=>'loadDistrictTo()']) }}
                                    @if (isset($errors) && $errors->has('province_id_to'))
                                        @foreach ($errors->get('province_id_to') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <label>Quận/Huyện</label>
                                    <select id="district_to" onchange="loadWardTo(this.value)"
                                            name="district_id_to"
                                            class="form-control">
                                    </select>
                                    @if (isset($errors) && $errors->has('district_id_to'))
                                        @foreach ($errors->get('district_id_to') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px">
                                <div class="col-lg-3">
                                    <label>Xã/Phường</label>
                                    <select id="ward_fr" name="ward_id_fr" class="form-control" disabled>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>Số nhà</label>
                                    <input name="home_number_fr" class="form-control spinner" type="text" id="home_number_fr"
                                           value="{{ old('home_number_fr') }}" placeholder="Nhập số nhà" disabled>
                                    @if ($errors->has('home_number_fr'))
                                        @foreach ($errors->get('home_number_fr') as $error)
                                            <span style="color: red"
                                                  class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-lg-3">
                                    <label>Xã/Phường</label>
                                    <select id="ward_to" name="ward_id_to" class="form-control">
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>Số nhà</label>
                                    <input name="home_number_to" class="form-control spinner" type="text" value="{{ old('home_number_to') }}"
                                           placeholder="Nhập số nhà">
                                    <span style="color: red;" id="home_number_to_err" class="help-block"></span>
                                </div>
                            </div>
                            <legend style="margin-top: 20px">Thông tin cơ bản</legend>
                            <div class="row" style="margin-bottom: 15px">
                                <div class="form-group">
                                    <div class="col-lg-3">
                                        <label class="control-label" for="inputError">Tên đơn hàng</label>
                                        <input name="name" value="{{ old('name') }}"
                                               class="form-control spinner" type="text"
                                               placeholder="Nhập tên đơn hàng">
                                        <span style="color: red;" id="name_err" class="help-block"></span>
                                    </div>
                                  
                                    <div class="col-lg-3">
                                        <label>Ghi chú bắt buộc</label>
                                        <select name="payment_type" id="payment_type" class="form-control">
                                            <option value="1">Người gửi trả cước</option>
                                            <option value="2">Người nhận trả cước</option>
                                        </select>
                                    </div>
                                   
                                    <div class="col-lg-3">
                                        <label class="control-label" for="inputError">Tiền thu hộ</label>
                                        <input id="cod" name="cod" value="{{ old('cod') }}"
                                               class="form-control spinner" type="text"
                                               placeholder="Nhập số tiền thu hộ">
                                        @if ($errors->has('cod'))
                                            @foreach ($errors->get('cod') as $error)
                                                <span style="color: red" class="help-block">{!! $error !!}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label" for="inputError">Giá đơn hàng</label>
                                        <input class="form-control spinner"
                                               value="{{ old( 'price') }}"
                                               name="price" type="text" >
                                        @if ($errors->has('price'))
                                            @foreach ($errors->get('price') as $error)
                                                <span style="color: red" class="help-block">{!! $error !!}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
        
                            </div>
                            <div class="row" style="margin-bottom: 15px">
                                <div class="form-group">
                                    
                                    <div class="col-lg-3">
                                        <label class="control-label" for="inputError">Khối lượng (gram)</label>
                                        <input name="weight" value="{{ old('weight') }}"
                                               class="form-control spinner" type="text"
                                               placeholder="Nhập khối lượng">
                                        @if ($errors->has('weight'))
                                            @foreach ($errors->get('weight') as $error)
                                                <span style="color: red" class="help-block">{!! $error !!}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="control-label" for="inputError">Ghi chú khác</label>
                                        <input class="form-control spinner"
                                               value="{{ old( 'other_note') }}"
                                               name="other_note" type="text">
                                    </div>
                                </div>
        
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="btnSave"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                    Lưu</button>
                <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times-circle"
                        aria-hidden="true"></i> Bỏ qua</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@push('script')
    <script>
        //load info
        loadDistrictFrom();
        loadDistrictTo();
        loadCustomerFr();
        function loadDistrictFrom() {
            var province_fr = $('#province_fr').val();
            $("#district_fr option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/get_district')}}/' + province_fr
            }).done(function (msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{@old('district_id_fr')}}') {
                        $('select[name="district_id_fr"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="district_id_fr"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
                    }
                }
                if ('{{@old('district_id_fr')}}') {
                    loadWardFrom('{{@old('district_id_fr')}}');
                } else {
                    loadWardFrom(msg[0]['id']);
                }
            });
        }

        function loadWardFrom(id) {
            $("#ward_fr option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/get_ward/')}}/' + id
            }).done(function (msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{@old('ward_id_fr')}}') {
                        $('select[name="ward_id_fr"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="ward_id_fr"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
                    }
                }
            });
        }

        function loadDistrictTo() {
            var province_to = $('#province_to').val();
            $("#district_to option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/get_district')}}/' + province_to
            }).done(function (msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{@old('district_id_to')}}') {
                        $('select[name="district_id_to"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="district_id_to"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
                    }
                }
                if ('{{@old('district_id_to')}}') {
                    loadWardTo('{{@old('district_id_to')}}');
                } else {
                    loadWardTo(msg[0]['id']);
                }
            });
        }

        function loadWardTo(id) {
            $("#ward_to option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/get_ward/')}}/' + id
            }).done(function (msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{@old('ward_id_to')}}') {
                        $('select[name="ward_id_to"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="ward_id_to"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
                    }
                }
            });
        }
        function loadCustomerFr() {
            var name_id_fr = $('#name_id_fr').val();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/detail_user')}}/' + name_id_fr
            }).done(function (msg) {
                $("#phone_number_fr").val(msg.user.phone_number);
                $("#home_number_fr").val(msg.user.home_number);
                $('select[name="district_id_fr"] option[value="'+msg.user.district_id+'"]').prop('selected', true);
                $('select[name="ward_id_fr"] option[value="'+msg.user.ward_id+'"]').prop('selected', true);
            });
        }
    </script>
@endpush


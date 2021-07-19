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
                    <div class="col-lg-3  col-md-3 col-sm-3">
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
                    <div class="col-lg-9  col-md-9 col-sm-9">
                        <div class="portlet-body form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <legend>Người gửi</legend>
                                    <label class="control-label" for="inputError">Họ tên</label>
                                    {{ Form::select('name_id_fr', \App\Models\User::getUserOption('customer'), old('name_id_fr'), ['class' => 'form-control', 'style' => 'width:100%', 'id' => 'name_id_fr', 'onchange' => 'loadCustomerFr()']) }}
                                    @if (isset($errors) && $errors->has('name_id_fr'))
                                        @foreach ($errors->get('name_id_to') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                    <label class="control-label" for="inputError">Số điện thoại</label>
                                    <input name="phone_number_fr" id="phone_number_fr"
                                        value="{{ old('phone_number_fr') }}" class="form-control spinner" type="text"
                                        placeholder="Nhập số điện thoại" disabled>
                                    @if ($errors->has('phone_number_fr'))
                                        @foreach ($errors->get('phone_number_fr') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                    <label>Quận/Huyện</label>
                                    <select id="district_fr" onchange="loadWardFrom(this.value)" name="district_id_fr"
                                        class="form-control" disabled>
                                    </select>
                                    @if (isset($errors) && $errors->has('district_id_fr'))
                                        @foreach ($errors->get('district_id_fr') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                    <label>Số nhà</label>
                                    <input name="home_number_fr" class="form-control spinner" type="text"
                                        id="home_number_fr" value="{{ old('home_number_fr') }}"
                                        placeholder="Nhập số nhà" disabled>
                                    @if ($errors->has('home_number_fr'))
                                        @foreach ($errors->get('home_number_fr') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <legend>Người nhận</legend>
                                    <label class="control-label" for="inputError">Họ tên</label>
                                    <input class="form-control spinner" value="{{ old('name_to') }}" name="name_to"
                                        type="text" placeholder="Nhập tên">
                                    <span style="color: red;" id="name_to_err" class="help-block"></span>

                                    <label class="control-label" for="inputError">Số điện thoại</label>
                                    <input name="phone_number_to" value="{{ old('phone_number_to') }}"
                                        class="form-control spinner" type="text" placeholder="Nhập số điện thoại">
                                    <span style="color: red;" id="phone_number_to_err" class="help-block"></span>
                                    <label>Quận/Huyện</label>
                                    <select id="district_to" onchange="loadWardTo(this.value)" name="district_id_to"
                                        class="form-control">
                                    </select>
                                    @if (isset($errors) && $errors->has('district_id_to'))
                                        @foreach ($errors->get('district_id_to') as $error)
                                            <span style="color: red" class="help-block">{!! $error !!}</span>
                                        @endforeach
                                    @endif
                                    <label>Số nhà</label>
                                    <input name="home_number_to" class="form-control spinner" type="text"
                                        value="{{ old('home_number_to') }}" placeholder="Nhập số nhà">
                                    <span style="color: red;" id="home_number_to_err" class="help-block"></span>
                                </div>
                            </div>


                            <legend style="margin-top: 20px">Thông tin cơ bản</legend>
                            <div class="row" style="margin-bottom: 15px">
                                <div class="form-group">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <label class="control-label" for="inputError">Tên đơn hàng</label>
                                        <input name="name" value="{{ old('name') }}" class="form-control spinner"
                                            type="text" placeholder="Nhập tên đơn hàng">
                                        <span style="color: red;" id="name_err" class="help-block"></span>

                                        <label class="control-label" for="inputError">Tiền thu</label>
                                        <input id="receivable_price" name="receivable_price" value="{{ old('receivable_price') }}"
                                            class="form-control spinner number" type="text">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <label class="control-label" for="inputError">Tiền hàng</label>
                                        <input class="form-control spinner number" value="{{ old('product_price') }}"
                                            name="product_price" type="text">

                                        <label class="control-label" for="inputError">Tiền ship</label>
                                        <input class="form-control spinner number" value="{{ old('ship_price') }}"
                                            name="ship_price" type="text">

                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">

                                        <label class="control-label" for="inputError">Ghi chú</label>
                                        <input class="form-control spinner" value="{{ old('note') }}" name="note"
                                            type="text">
                                        <label class="control-label">
                                            <input type="checkbox" class="form-control option-input radio"
                                                name="is_prioritize" value="1"> Ưu tiên </label>
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
            // var province_fr = $('#province_fr').val();
            var province_fr = 50;
            $("#district_fr option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{ url('/ajax/get_district') }}/' + province_fr
            }).done(function(msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{ @old('district_id_fr') }}') {
                        $('select[name="district_id_fr"]').append('<option value="' + msg[i]['id'] + '" selected>' +
                            msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="district_id_fr"]').append('<option value="' + msg[i]['id'] + '">' + msg[i][
                            'name'
                        ] + '</option>')
                    }
                }
                // if ('{{ @old('district_id_fr') }}') {
                //     loadWardFrom('{{ @old('district_id_fr') }}');
                // } else {
                //     loadWardFrom(msg[0]['id']);
                // }
            });
        }
        // function loadWardFrom(id) {
        //     $("#ward_fr option[value!='-1']").remove();
        //     $.ajax({
        //         type: "GET",
        //         url: '{{ url('/ajax/get_ward/') }}/' + id
        //     }).done(function (msg) {
        //         var i;
        //         for (i = 0; i < msg.length; i++) {
        //             if (msg[i]['id'] == '{{ @old('ward_id_fr') }}') {
        //                 $('select[name="ward_id_fr"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
        //             } else {
        //                 $('select[name="ward_id_fr"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
        //             }
        //         }
        //     });
        // }
        function loadDistrictTo() {
            // var province_to = $('#province_to').val();
            var province_to = 50;
            $("#district_to option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{ url('/ajax/get_district') }}/' + province_to
            }).done(function(msg) {
                var i;
                for (i = 0; i < msg.length; i++) {
                    if (msg[i]['id'] == '{{ @old('district_id_to') }}') {
                        $('select[name="district_id_to"]').append('<option value="' + msg[i]['id'] + '" selected>' +
                            msg[i]['name'] + '</option>')
                    } else {
                        $('select[name="district_id_to"]').append('<option value="' + msg[i]['id'] + '">' + msg[i][
                            'name'
                        ] + '</option>')
                    }
                }
                // if ('{{ @old('district_id_to') }}') {
                //     loadWardTo('{{ @old('district_id_to') }}');
                // } else {
                //     loadWardTo(msg[0]['id']);
                // }
            });
        }

        // function loadWardTo(id) {
        //     $("#ward_to option[value!='-1']").remove();
        //     $.ajax({
        //         type: "GET",
        //         url: '{{ url('/ajax/get_ward/') }}/' + id
        //     }).done(function (msg) {
        //         var i;
        //         for (i = 0; i < msg.length; i++) {
        //             if (msg[i]['id'] == '{{ @old('ward_id_to') }}') {
        //                 $('select[name="ward_id_to"]').append('<option value="' + msg[i]['id'] + '" selected>' + msg[i]['name'] + '</option>')
        //             } else {
        //                 $('select[name="ward_id_to"]').append('<option value="' + msg[i]['id'] + '">' + msg[i]['name'] + '</option>')
        //             }
        //         }
        //     });
        // }
        function loadCustomerFr() {
            var name_id_fr = $('#name_id_fr').val();
            $.ajax({
                type: "GET",
                url: '{{ url('/ajax/detail_user') }}/' + name_id_fr
            }).done(function(msg) {
                $("#phone_number_fr").val(msg.user.phone_number);
                $("#home_number_fr").val(msg.user.home_number);
                $('select[name="district_id_fr"] option[value="' + msg.user.district_id + '"]').prop('selected',
                    true);
                $('select[name="ward_id_fr"] option[value="' + msg.user.ward_id + '"]').prop('selected', true);
                (msg.user.is_advance_money == 1) ? $('#frm_COD_edit').show(): $('#frm_COD_edit').hide();
            });
        }
    </script>
@endpush

<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                        <div class=" form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label" for="inputError">ID</label>
                                    <input class="form-control spinner" value="{{ old('uuid', @$user->uuid) }}"
                                        name="uuid" type="text" placeholder="" disabled>
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
                                    <label class="control-label">Email *</label>
                                    <div class="input-group">
                                        <input type="email" value="{{ old('email', @$user->email) }}"
                                            class="form-control" placeholder="Địa chỉ email" name="email">
                                        <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                    </div>
                                    <span style="color: red;" id="email_err" class="help-block"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="control-label" for="inputError">Số điện thoại *</label>
                                    <input name="phone_number" value="{{ old('phone_number', @$user->phone_number) }}"
                                        class="form-control spinner" type="text" placeholder="Nhập số điện thoại">
                                    <span style="color: red;" id="phone_number_err" class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label" for="inputError">Mật khẩu *</label>
                                    <input class="form-control spinner" value="{{ old('password') }}" name="password"
                                        type="password" placeholder="Nhập mật khẩu">
                                    <span style="color: red;" id="password_err" class="help-block"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label class="control-label" for="inputError">Nhập lại mật khẩu *</label>
                                    <input class="form-control spinner" value="" id="cf_password" type="password"
                                        placeholder="Nhập mật khẩu">
                                    <span style="color: red;" id="cf_password_err" class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="control-label">Ngày sinh *</label>
                                    <input name="birth_day" value="{{ old('birth_day', @$user->birth_day) }}"
                                        class="form-control" id="mask_date" type="text" />
                                    <span class="help-block">Năm/Tháng/Ngày</span>
                                    <span style="color: red;" id="birth_day_err" class="help-block"></span>
                                </div>

                                <div class="col-lg-6">
                                    <label class="control-label" for="inputError">Số CMND *</label>
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
                                    <label>Số nhà / tên đường *</label>
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
                                    <input name="bank_account"
                                        value="{{ old('bank_account', @$user->bank_account) }}"
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
                        @if ($active == 'customer')
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6" id="img_is_advance_money" >
                                        <label class="control-label" for="inputError" id="is_advance_money">Tạm ứng tiền</label>
                                        <br><input type="radio" class=""  name="is_advance_money" value="0"> Không
                                        <br><input type="radio" class=""  name="is_advance_money" value="1" > Có
                                    </div>
                                </div>
                            </div>
                        @endif
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

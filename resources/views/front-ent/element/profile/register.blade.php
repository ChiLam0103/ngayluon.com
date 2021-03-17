@extends('front-ent.app')
@section('content')
    <!-- BANNER -->
    <section class="banner-sub">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <h1>Đăng ký thông tin</h1>
                    <span><a href="{!! url('/') !!}">Trang chủ</a> / <b>Thông tin cá nhân</b> </span>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </section>
    <!-- SUB CREATE ORDER -->
    <section class="sub-content" style="padding: 5px 0 50px 0 !important;">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <!-- profile -->
                <div class="row sub-title">
                    <div class="col-md-12 col-sm-12">
                        <h3>Thông tin chung</h3>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="row order-form">
                    <div class="col-md-12 col-sm-12">
                        {{ Form::open(['url' => 'front-ent/register', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        <ul>
                            <li>
                                <label>Tên:</label>
                                <input name="name" type="text" 
                                       placeholder="Tên"/>
                                @if ($errors->has('name'))
                                    @foreach ($errors->get('name') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Email:</label>
                                <input name="email" type="text" 
                                       placeholder="Email"/>
                                @if ($errors->has('email'))
                                    @foreach ($errors->get('email') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Số điện thoại:</label>
                                <input name="phone_number" type="text"
                                       placeholder="Số điện thoại"/>
                                @if ($errors->has('phone_number'))
                                    @foreach ($errors->get('phone_number') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Mật khẩu:</label>
                                <input name="password" type="password" 
                                       placeholder="Mật khẩu"/>
                                @if ($errors->has('password'))
                                    @foreach ($errors->get('password') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Xác nhận mật khẩu:</label>
                                <input name="cf-password" type="password" 
                                       placeholder="Xác nhận mật khẩu"/>
                                @if ($errors->has('cf-password'))
                                    @foreach ($errors->get('cf-password') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Tên tài khoản:</label>
                                <input name="bank_account" type="text"
                                       placeholder="Tên tài khoản ngân hàng"/>
                                @if ($errors->has('bank_account'))
                                    @foreach ($errors->get('bank_account') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Số tài khoản:</label>
                                <input name="bank_account_number" type="text"
                                       placeholder="Số tài khoản ngân hàng"/>
                                @if (!empty(Auth::user()->bank_account_number))
                                <div style="width: 70%" class="pull-right">
                                    <span class="text-success">*Liên hệ admin để thay đổi số tài khoản</span>
                                </div>
                                @endif
                                @if ($errors->has('bank_account_number'))
                                    @foreach ($errors->get('bank_account_number') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Tên ngân hàng:</label>
                                <input name="bank_name" type="text" 
                                       placeholder="Tên ngân hàng"/>
                                @if ($errors->has('bank_name'))
                                    @foreach ($errors->get('bank_name') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label>Chi nhánh:</label>
                                <input name="bank_branch" type="text" 
                                       placeholder="Chi nhánh ngân hàng"/>
                                @if ($errors->has('bank_branch'))
                                    @foreach ($errors->get('bank_branch') as $error)
                                        <div style="width: 70%" class="pull-right">
                                            <span style="color: red;" class="help-block">{!! $error !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </li>
                            <li>
                                <label></label>
                                <button type="submit" name="register" value="register" id="">Đăng ký</button>
                            </li>
                        </ul>
                        {!! Form::close() !!}
                    </div>
                </div>

                <!-- end profile -->
            </div>
        </div>
    </section>
    <!-- COPYRIGHT -->
@endsection


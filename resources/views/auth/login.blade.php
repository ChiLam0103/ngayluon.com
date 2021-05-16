@extends('auth.app')
@section('main')
    <form class="login-form" id="login" action="{!!  url('loginPage') !!}" method="post">
        {{ csrf_field() }}
        <h3 class="form-title font-green">Đăng nhập</h3>
        @if (\Session::has('err'))
            <div class="alert alert-danger">
                <p>{!! \Session::get('err') !!}</p>
            </div>
        @endif
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span> Enter any username and password. </span>
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Email / Số điện thoại</label>
            <input class="form-control form-control-solid placeholder-no-fix" value="{!!  old('email') !!}" type="text"
                placeholder="Nhập email hoặc số điện thoại" name="email" />
            @if ($errors->has('email'))
                <div class="error" style="color: red">{{ $errors->first('email') }}</div>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Mật khẩu</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" placeholder="Nhập mật khẩu"
                name="password" />
            @if ($errors->has('password'))
                <div class="error" style="color: red">{{ $errors->first('password') }}</div>
            @endif
        </div>
        @if (\Session::has('success'))
            <div class="alert alert-warning">
                <p>{!! \Session::get('success') !!}</p>
            </div>
        @endif
        <div class="form-actions">
            <button type="submit" id="submit" class="btn green uppercase">Đăng nhập</button>
            <label class="rememberme check mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="remember" value="1" />Ghi nhớ
                <span></span>
            </label>
            {{-- <a href="#" id="forget-password" class="forget-password">Forgot Password?</a> --}}
        </div>
        {{-- <div class="login-options">
            <h4>Or login with</h4>
            <ul style="padding-left: 150px">
                <li style="list-style: none">
                    <a data-original-title="facebook" href="javascript:;"><img width="30"
                            src="{{ asset('/img/facebook.png') }}"></a>
                </li>
            </ul>
        </div>
        <div class="create-account" style="min-height: 50px;">
            <p>
            </p>
        </div> --}}
    </form>
@endsection
<script>
  document.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
            jQuery(this).blur();
            jQuery('#submit').focus().click();
        }
    }
</script>
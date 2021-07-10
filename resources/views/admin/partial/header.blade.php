<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}"><img
                    src="{{ asset('public/front_ent/img/apple-touch-icon.png') }}" alt="logo" /></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class=" @if (isset($active) && $active=='report' ) active @endif"> <a href="{{ url('/admin/report') }}">
                        <i class="fa fa-line-chart" aria-hidden="true"></i> Tổng quan
                        @if (isset($active) && $active == 'report')<span
                                class="selected"></span>
                        @endif
                    </a></li>
                <li class=" @if (isset($active) && $active=='booking' ) active @endif"> <a href="{{ url('/admin/booking') }}">
                        <i class="fa fa-file-text-o" aria-hidden="true"></i> Đơn hàng
                        @if (isset($active) && $active == 'booking')<span
                                class="selected"></span>
                        @endif
                    </a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-users"></i> Đối tác <span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li> <a href="{{ url('/admin/admin') }}" class="@if (isset($active) &&
                                $active=='admin' ) active @endif"><i class="fa fa-cogs"
                                    aria-hidden="true"></i> Admin</a></li>
                        <li><a href="{{ url('/admin/customer') }}" class="@if (isset($active) &&
                                $active=='customer' ) active @endif"><i class="fa fa-user"
                                    aria-hidden="true"></i> Khách hàng</a></li>
                        <li> <a href="{{ url('/admin/warehouse') }}" class="@if (isset($active)
                                && $active=='warehouse' ) active @endif"><i class="fa fa-home"
                                    aria-hidden="true"></i> Quản lý Kho</a></li>
                        <li> <a href="{{ url('/admin/shippers') }}" class="@if (isset($active)
                                && $active=='shipper' ) active @endif"><i class="fa fa-truck"
                                    aria-hidden="true"></i> Shipper</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user-plus"
                            aria-hidden="true"></i> Đăng ký <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li  class="@if (isset($active) && $active=='signin' ) active @endif"><a href="{{ url('/admin/feedback/signin') }}"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i> Làm tài xế</a></li>
                        <li class="@if (isset($active) && $active=='contact' ) active @endif"> <a href="{{ url('/admin/feedback/contact') }}" > <i
                                    class="fa fa-envelope" aria-hidden="true"></i> Liên hệ</a></li>
                        <li class="@if (isset($active) && $active=='newspaper' ) active @endif"> <a href="{{ url('/admin/feedback/newspaper') }}"><i
                                    class="fa fa-newspaper-o" aria-hidden="true"></i> Nhận bảng tin</a></li>
                    </ul>
                </li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="page_user_profile_1.html">
                        <i class="icon-user"></i> Hồ sơ </a></li>
                <li> <a href="{{ url('/logout') }}">
                        <i class="icon-key"></i> Đăng xuất </a></li>
            </ul>
        </div>
    </div>
</nav>

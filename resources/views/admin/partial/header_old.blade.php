<style>
    .page-header.navbar .page-logo .logo-default {
        margin: 0 !important;
        max-width: 120px;
        height: 3em
    }

</style>
<div class="page-header-inner ">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('public/front_ent/img/apple-touch-icon.png') }}" alt="logo" class="logo-default" /> </a>
        <div class="menu-toggler sidebar-toggler">
            <span></span>
        </div>
    </div>
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
        data-target=".navbar-collapse">
        <span></span>
    </a>
    <nav class="topnav" id="myTopnav">
        <a href="{{ url('/admin/report') }}" class=" @if (isset($active) && $active=='report'
            ) active @endif">
            <i class="fa fa-line-chart" aria-hidden="true"></i> Tổng quan
            @if (isset($active) && $active == 'report')<span class="selected"></span>
            @endif
        </a>
        <a href="{{ url('/admin/booking') }}" class=" @if (isset($active) && $active=='booking'
            ) active @endif">
            <i class="fa fa-file-text-o" aria-hidden="true"></i> Đơn hàng</a>
        @if (Auth::user()->role == 'admin')
            <div class="dropdown  @if ((isset($active) && $active=='admin' ) ||
                $active=='customer' || $active=='collaborators' || $active=='warehouse' || $active=='shipper' ) active open @endif">
                <button class="dropbtn"><i class="fa fa-users"></i> Đối tác
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ url('/admin/admin') }}" class="@if (isset($active) &&
                        $active=='admin' ) active @endif"><i class="fa fa-cogs"
                            aria-hidden="true"></i> Admin</a>
                    <a href="{{ url('/admin/customer') }}" class="@if (isset($active) &&
                        $active=='customer' ) active @endif"><i class="fa fa-user"
                            aria-hidden="true"></i> Khách hàng</a>
                    {{-- <a href="{{ url('/admin/collaborators')}}" class="@if (isset($active) && $active == 'collaborators') active @endif"><i class="fa fa-user-plus" aria-hidden="true"></i> Nhân viên của KH</a> --}}
                    <a href="{{ url('/admin/warehouse') }}" class="@if (isset($active) &&
                        $active=='warehouse' ) active @endif"><i class="fa fa-home"
                            aria-hidden="true"></i> Quản lý Kho</a>
                    <a href="{{ url('/admin/shippers') }}" class="@if (isset($active) &&
                        $active=='shipper' ) active @endif"><i class="fa fa-truck"
                            aria-hidden="true"></i> Shipper</a>
                </div>
            </div>

            <div class="dropdown @if ((isset($active) && $active=='district_type' ) ||
                $active=='price' || $active=='notification-handle' || $active=='promotions' || $active=='feedback' ||
                $active=='version' ) active open @endif">
                <button class="dropbtn"><i class="fa fa-cog" aria-hidden="true"></i> Quản lý
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ url('/admin/district_type') }}" class="@if (isset($active) &&
                        $active=='district_type' ) active @endif"><i class="fa fa-map"
                            aria-hidden="true"></i> Quận/Huyện</a>
                    {{-- <a href="{{ url('/admin/price')}}" class="@if (isset($active) && $active == 'price') active @endif"><i class="fa fa-money" aria-hidden="true"></i> Giá cước</a> --}}
                    <a href="{{ url('/admin/notification-handles') }}" class="@if (isset($active)
                        && $active=='notification-handle' ) active @endif"> <i
                            class="icon-bell" aria-hidden="true"></i> Thông báo</a>
                    <a href="{{ url('/admin/promotions') }}" class="@if (isset($active) &&
                        $active=='promotions' ) active @endif"> <i class="fa fa-ticket"
                            aria-hidden="true"></i> Chương trình khuyến mãi</a>
                    {{-- <a href="{{ url('/admin/feedback')}}" class="@if (isset($active) && $active == 'feedback') active @endif">  <i class="fa fa-comment-o" aria-hidden="true"></i> Phản hồi</a> --}}
                    <a href="{{ url('/admin/versions') }}" class="@if (isset($active) &&
                        $active=='version' ) active @endif"> <i class="fa fa-level-up"
                            aria-hidden="true"></i> Version</a>
                </div>
            </div>
            <div class="dropdown @if ((isset($active) && $active=='contact' ) || $active=='signin'
               || $active=='newspaper' || $active=='feedback'
                ) active open @endif">
                <button class="dropbtn"><i class="fa fa-user-plus" aria-hidden="true"></i> Đăng ký
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="{{ url('/admin/feedback/signin') }}" class="@if (isset($active) &&
                        $active=='signin' ) active @endif"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i> Làm tài xế</a>
                    <a href="{{ url('/admin/feedback/contact') }}" class="@if (isset($active) &&
                        $active=='contact' ) active @endif"> <i
                            class="fa fa-envelope" aria-hidden="true"></i> Liên hệ</a>
                    <a href="{{ url('/admin/feedback/newspaper') }}" class="@if (isset($active) &&
                        $active=='newspaper' ) active @endif"><i class="fa fa-newspaper-o"
                            aria-hidden="true"></i> Nhận bảng tin</a>
                </div>
            </div>
        @endif
        <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </nav>
    <div class="top-menu">

        <ul class="nav navbar-nav pull-right">
            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                    data-close-others="true">
                    <i class="icon-bell"></i>
                    <span class="badge badge-default" id="count-notification"></span>
                </a>
                <ul class="dropdown-menu">
                    <!-- <li class="external">
                        <h3>
                            <span class="bold">12 pending</span> notifications</h3>
                        <a href="page_user_profile_1.html">view all</a>
                    </li> -->
                    <li>
                        <ul class="dropdown-menu-list scroller" id="list-notification" style="height: 250px;"
                            data-handle-color="#637283">
                            <li>
                                <a href="">
                                    <span class="time">just now</span>
                                    <span class="details">
                                        <span class="label label-sm label-icon label-success">
                                            <i class="fa fa-plus"></i>
                                        </span>123
                                    </span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="javascript:;">
                                    <span class="time">3 mins</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Server #12 overloaded. </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <span class="time">10 mins</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Server #2 not responding. </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <span class="time">14 hrs</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> Application error. </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <span class="time">2 days</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Database overloaded 68%. </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <span class="time">3 days</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> A user IP blocked. </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <span class="time">4 days</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Storage Server #4 not responding dfdfdfd. </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <span class="time">5 days</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> System Error. </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <span class="time">9 days</span>
                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Storage server failed. </span>
                                </a>
                            </li> -->
                        </ul>
                    </li>
                </ul>
            </li>
            <!-- <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                   data-close-others="true">
                    <i class="icon-envelope-open"></i>
                    <span class="badge badge-default"> 4 </span>
                </a>
                <ul class="dropdown-menu">
                    <li class="external">
                        <h3>You have
                            <span class="bold">7 New</span> Messages</h3>
                        <a href="app_inbox.html">view all</a>
                    </li>
                    <li>
                        <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                            <li>
                                <a href="#">
                                                    <span class="photo">
                                                        <img src="../assets/layouts/layout3/img/avatar2.jpg"
                                                             class="img-circle" alt=""> </span>
                                    <span class="subject">
                                                        <span class="from"> Lisa Wong </span>
                                                        <span class="time">Just Now </span>
                                                    </span>
                                    <span class="message"> Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                                    <span class="photo">
                                                        <img src="../assets/layouts/layout3/img/avatar3.jpg"
                                                             class="img-circle" alt=""> </span>
                                    <span class="subject">
                                                        <span class="from"> Richard Doe </span>
                                                        <span class="time">16 mins </span>
                                                    </span>
                                    <span class="message"> Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                                    <span class="photo">
                                                        <img src="../assets/layouts/layout3/img/avatar1.jpg"
                                                             class="img-circle" alt=""> </span>
                                    <span class="subject">
                                                        <span class="from"> Bob Nilson </span>
                                                        <span class="time">2 hrs </span>
                                                    </span>
                                    <span class="message"> Vivamus sed nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                                    <span class="photo">
                                                        <img src="../assets/layouts/layout3/img/avatar2.jpg"
                                                             class="img-circle" alt=""> </span>
                                    <span class="subject">
                                                        <span class="from"> Lisa Wong </span>
                                                        <span class="time">40 mins </span>
                                                    </span>
                                    <span class="message"> Vivamus sed auctor 40% nibh congue nibh... </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                                    <span class="photo">
                                                        <img src="../assets/layouts/layout3/img/avatar3.jpg"
                                                             class="img-circle" alt=""> </span>
                                    <span class="subject">
                                                        <span class="from"> Richard Doe </span>
                                                        <span class="time">46 mins </span>
                                                    </span>
                                    <span class="message"> Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                   data-close-others="true">
                    <i class="icon-calendar"></i>
                    <span class="badge badge-default"> 3 </span>
                </a>
                <ul class="dropdown-menu extended tasks">
                    <li class="external">
                        <h3>You have
                            <span class="bold">12 pending</span> tasks</h3>
                        <a href="app_todo.html">view all</a>
                    </li>
                    <li>
                        <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                            <li>
                                <a href="javascript:;">
                                                    <span class="task">
                                                        <span class="desc">New release v1.2 </span>
                                                        <span class="percent">30%</span>
                                                    </span>
                                    <span class="progress">
                                                        <span style="width: 40%;"
                                                              class="progress-bar progress-bar-success"
                                                              aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">40% Complete</span>
                                                        </span>
                                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                                    <span class="task">
                                                        <span class="desc">Application deployment</span>
                                                        <span class="percent">65%</span>
                                                    </span>
                                    <span class="progress">
                                                        <span style="width: 65%;"
                                                              class="progress-bar progress-bar-danger"
                                                              aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">65% Complete</span>
                                                        </span>
                                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                                    <span class="task">
                                                        <span class="desc">Mobile app release</span>
                                                        <span class="percent">98%</span>
                                                    </span>
                                    <span class="progress">
                                                        <span style="width: 98%;"
                                                              class="progress-bar progress-bar-success"
                                                              aria-valuenow="98" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">98% Complete</span>
                                                        </span>
                                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                                    <span class="task">
                                                        <span class="desc">Database migration</span>
                                                        <span class="percent">10%</span>
                                                    </span>
                                    <span class="progress">
                                                        <span style="width: 10%;"
                                                              class="progress-bar progress-bar-warning"
                                                              aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">10% Complete</span>
                                                        </span>
                                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                                    <span class="task">
                                                        <span class="desc">Web server upgrade</span>
                                                        <span class="percent">58%</span>
                                                    </span>
                                    <span class="progress">
                                                        <span style="width: 58%;" class="progress-bar progress-bar-info"
                                                              aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">58% Complete</span>
                                                        </span>
                                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                                    <span class="task">
                                                        <span class="desc">Mobile development</span>
                                                        <span class="percent">85%</span>
                                                    </span>
                                    <span class="progress">
                                                        <span style="width: 85%;"
                                                              class="progress-bar progress-bar-success"
                                                              aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">85% Complete</span>
                                                        </span>
                                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                                    <span class="task">
                                                        <span class="desc">New UI release</span>
                                                        <span class="percent">38%</span>
                                                    </span>
                                    <span class="progress progress-striped">
                                                        <span style="width: 38%;"
                                                              class="progress-bar progress-bar-important"
                                                              aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">38% Complete</span>
                                                        </span>
                                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li> -->
            <li class="dropdown dropdown-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                    data-close-others="true">
                    <img alt="" class="img-circle" src="
                    @if (Auth::user()->avatar != null) {{ url(Auth::user()->avatar) }}
                @else
                    {{ url('public/img/default-avatar.jpg') }} @endif"/>
                    <span class="username username-hide-on-mobile">{!! @Auth::user()->name !!}</span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-default">
                    <li>
                        <a href="page_user_profile_1.html">
                            <i class="icon-user"></i> Hồ sơ cá nhân </a>
                    </li>
                    <li>
                        <a href="{{ url('/logout') }}">
                            <i class="icon-key"></i> Log Out </a>
                    </li>
                </ul>
            </li>
            {{-- <li class="dropdown dropdown-quick-sidebar-toggler">
                <a href="javascript:;" class="dropdown-toggle">
                    <i class="icon-logout"></i>
                </a>
            </li> --}}
        </ul>
    </div>
</div>

<?php

namespace App\Http\Controllers\Ajax;

use App\Models\BookDelivery;
use App\Models\Booking;
use App\Models\Collaborator;
use App\Models\PaidHistory;
use App\Models\ReportImage;
use App\Models\User;
use App\Models\Agency;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\ManagementScope;
use App\Models\ShipperLocation;
use App\Models\DeliveryAddress;
use App\Http\Controllers\Controller;
use function asset;
use function dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Form, DB;
use function implode;
use function number_format;
use function round;
use function url;

class UserController extends Controller
{
    public function getDetailUser($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json(['user' => $user]);
    }
    public function getUser()
    {
        $user = User::where('role', 'collaborators')->where('delete_status', 0)->get();
        return datatables()->of($user)
            ->addColumn('action', function ($user) {
                $action = [];
                $action[] = '<a style="float:left" href="' . url('admin/collaborators/' . $user->id . '/edit') . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $action[] = '<div style="float: left">' . Form::open(['method' => 'DELETE', 'url' => ['admin/collaborators/' . $user->id]]) .
                    '<button class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash-o"></i> Xóa</button>' .
                    Form::close() . '</div>';
                return implode(' ', $action);
            })
            ->addColumn('agency_name', function ($user) {
                $check = Collaborator::where('user_id', $user->id)->get();
                $data = [];
                if (isset($check)) {
                    foreach ($check as $c) {
                        $data[] = $c->agency_name;
                    }
                }
                return implode(', ', $data);
            })
            ->editColumn('avatar', function ($user) {
                $user->avatar = $user->avatar != null ? url('/' . $user->avatar) : asset('public/img/default-avatar.jpg');
                $data = '<img src="' . $user->avatar . '" width="70px"></img>';
                return $data;
            })
            ->rawColumns(['avatar', 'action'])
            ->make(true);
    }
    public function getCustomer()
    {
        $user = User::where('role', 'customer')->where('delete_status', 0)->orderBy('id', 'desc');
        return datatables()->of($user)
            ->addColumn('action', function ($user) {
                $action = [];
                $action[] = '<a style="float:left" href="' . url('admin/customers/show_address/' . $user->id) . '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Điểm giao/nhận hàng</a>';
                if (Auth::user()->role == 'admin') {
                    $action[] = '<a style="float:left" href="#" onclick="exportBooking(' . $user->id . ')" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-edit"></i> Xuất đơn hàng</a>';
                    $action[] = '<button type="button" class="btn btn-info btn-xs" onclick="showModal(' . $user->id . ')"><i class="icon-bell" aria-hidden="true"></i> Thông báo nhanh</button>';
                    $action[] = '<a style="float:left" href="' . url('admin/customers/' . $user->id . '/edit') . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                    $action[] = '<div style="float: left">' . Form::open(['method' => 'DELETE', 'url' => ['admin/customers/' . $user->id]]) . '<button class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash-o"></i> Xóa</button>' . Form::close() . '</div>';
                    $action[] = '<a href="' . url('admin/customers/withdrawal/' . $user->id) . '" class="btn btn-xs btn-default" onclick="return confirm(\'Bạn có chắc chắn muốn rút tiền?\');"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span> Rút tiền</a>';
                }
                return implode(' ', $action);
            })
            ->editColumn('avatar', function ($user) {
                return ($user->avatar != null ? '<a href="javascript:void(0);" class="img_modal"> <img width="30" alt="' . $user->name . '" src="' . asset('public/' .  $user->avatar) . '"></a>' : "<img src=" . asset('public/img/default-avatar.jpg') . " width='30'/>");
            })
            ->editColumn('name', function ($user) {
                $name = $user->name;
                if ($user->is_vip == 1) {
                    $name .= ' <img src="' . asset('public/img/vip.png') . '" alt="VIP" title="Khách hàng VIP" width="40px" />';
                } elseif ($user->is_vip == 2) {
                    $name .= ' <img src="' . asset('public/img/pro.png') . '" alt="Pro" title="Khách hàng Pro" width="40px" />';
                }
                return $name;
            })
            ->editColumn('is_advance_money', function ($user) {
                $name = '';
                if ($user->is_advance_money == 0) {
                    $name .= '<input type="checkbox" value="0" readonly>';
                } elseif ($user->is_advance_money == 1) {
                    $name .= '<input type="checkbox"  checked value="1" readonly >';
                }
                return $name;
            })
            ->addColumn('owe', function ($user) {
                $booking = Booking::where('sender_id', $user->id)->where('owe', 0)->where(function ($query) {
                    $query->where('status', 'completed');
                });
                if (Auth::user()->role == 'collaborators') {
                    $user_id = Auth::user()->id;
                    $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
                    $booking = $booking->whereIn('last_agency', $scope);
                }
                $booking = $booking->select('bookings.*');
                $data = round(($booking->sum('price') + $booking->sum('incurred')) - $booking->sum('paid'));
                return number_format($data) . '<br/><a style="margin-top: 5px" href="' . url('admin/customers/owe/' . $user->id) . '" class="btn btn-xs btn-success"> Chi tiết</a>';
            })
            ->editColumn('total_COD', function ($user) {
                $cod = Booking::where('sender_id', $user->id)->where('status', 'completed')->where('COD', '>', 0)->where('COD_status', 'pending');
                if (Auth::user()->role == 'collaborators') {
                    $scope = Collaborator::where('user_id', Auth::user()->id)->pluck('agency_id');
                    $cod = $cod->whereIn('last_agency', $scope);
                }
                $cod = $cod->sum('COD');
                return number_format($cod) . '<br/><a style="margin-top: 5px" href="' . url('admin/COD_details/' . $user->id) . '" class="btn btn-xs btn-success">Chi tiết</a>';
            })
            ->addColumn('wallet', function ($user) {
                $wallet = 0;
                $cod = Booking::where('sender_id', $user->id)->where('status', 'completed')->where('COD', '>', 0)->where('COD_status', 'pending');
                if (Auth::user()->role == 'collaborators') {
                    $scope = Collaborator::where('user_id', Auth::user()->id)->pluck('agency_id');
                    $cod = $cod->whereIn('last_agency', $scope);
                }
                $cod = $cod->sum('COD');
                $booking = Booking::where('sender_id', $user->id)->where('owe', 0)->where(function ($query) {
                    $query->where('status', 'completed');
                });
                if (Auth::user()->role == 'collaborators') {
                    $user_id = Auth::user()->id;
                    $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
                    $booking = $booking->whereIn('last_agency', $scope);
                }
                $booking = $booking->select('bookings.*');
                $data = round(($booking->sum('price') + $booking->sum('incurred')) - $booking->sum('paid'));
                $wallet = round($cod - $data);
                return number_format($wallet);
            })
            ->rawColumns(['avatar', 'name', 'owe', 'total_COD', 'wallet', 'is_advance_money', 'action'])
            ->make(true);
    }
    public function getOweDetails($id)
    {
        $booking = Booking::where('sender_id', $id)->where('owe', 0)->where(function ($query) {
            $query->where('status', 'return')->where('sub_status', 'none')->whereHas('deliveries', function ($d) {
                $d->where('category', 'return')->where('status', 'completed');
            })
                ->orWhere('status', 'completed')->whereHas('deliveries', function ($d1) {
                    $d1->where('category', 'send');
                });
        });
        if (Auth::user()->role == 'collaborators') {
            $user_id = Auth::user()->id;
            $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
            $booking = $booking->whereIn('last_agency', $scope);
        }
        $data = $booking->select('bookings.*')->get();
        return datatables()->of($data)
            ->editColumn('action', function ($b) {
                return '<img onclick="changeOweStatus(' . $b->id . ')" src="' . asset('public/img/incorect.png') . '" width="30px"></img>';
            })
            ->editColumn('payment_date', function ($b) {
                return $b->payment_date != null ? $b->payment_date : '';
            })
            ->editColumn('COD', function ($b) {
                return number_format($b->COD);
            })
            ->editColumn('COD_status', function ($b) {
                return $b->COD_status == 'pending' ? 'Chưa thanh toán' : 'Đã thanh toán';
            })
            ->editColumn('payment_type', function ($b) {
                return $b->payment_type == 1 ? 'Người gửi trả cước' : 'Người nhận trả cước';
            })
            ->addColumn('receiveShipper', function ($b) {
                if ($b->status != null) {
                    if ($b->status != 'new') {
                        return @BookDelivery::where('book_id', $b->id)->where('category', 'receive')->where('status', 'completed')->first()->shipper_name;
                    }
                }
                return '';
            })
            ->addColumn('sendShipper', function ($b) {
                if ($b->status != null) {
                    if ($b->status != 'new') {
                        return @BookDelivery::where('book_id', $b->id)->where('category', 'send')->where('status', 'completed')->first()->shipper_name;
                    }
                }
                return '';
            })
            ->addColumn('returnShipper', function ($b) {
                if ($b->status != null) {
                    if ($b->status != 'new') {
                        return @BookDelivery::where('book_id', $b->id)->where('category', 'return')->where('status', 'completed')->first()->shipper_name;
                    }
                }
                return '';
            })
            ->addColumn('report_image', function ($b) {
                $delivery = BookDelivery::where('book_id', $b->id)->where(function ($query) {
                    $query->where('category', 'return')->where('status', 'completed')
                        ->orWhere('category', 'send')->where('status', 'completed');
                })
                    ->select('id')->get();
                $image = '';
                if (isset($delivery)) {
                    foreach ($delivery as $d) {
                        $url = ReportImage::where('task_id', $d->id)->get();
                        if (isset($url)) {
                            foreach ($url as $k => $u) {
                                if ($k == 0) {
                                    $image .= '<a href="' . asset('/' . $u->image) . '" data-lightbox="' . $b->id . '"><button class="btn btn-xs btn-info">Hiển thị</button></a>';
                                }
                                if ($k > 0) {
                                    $image .= '<a href="' . asset('/' . $u->image) . '" data-lightbox="' . $b->id . '"></a>';
                                }
                            }
                        }
                    }
                }
                return $image;
            })
            ->rawColumns(['action', 'report_image'])
            ->make(true);
    }

    public function changeOweStatus($id)
    {
        DB::beginTransaction();
        try {
            $data = Booking::find($id);
            $data->owe = 1;
            $data->paid = $data->price + $data->incurred;
            $data->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return $data;
    }

    public function getPartner()
    {
        $partner = DB::table('users')->join('partner_a_p_is', 'users.id', '=', 'partner_a_p_is.partner_id')->select('users.*', 'partner_a_p_is.api_content')->get();
        return datatables()->of($partner)
            ->addColumn('action', function ($user) {
                $action = [];
                $action[] = '<a style="float:left" href="' . url('admin/partners/' . $user->id . '/edit') . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $action[] = '<div style="float: left">' . Form::open(['method' => 'DELETE', 'url' => ['admin/partners/' . $user->id]]) .
                    '<button class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash-o"></i> Xóa</button>' .
                    Form::close() . '</div>';
                return implode(' ', $action);
            })
            ->addColumn('full_address', function ($user) {
                $province_name = Province::find($user->province_id)->name;
                $district_name = District::find($user->district_id)->name;
                $ward_name = Ward::find($user->ward_id)->name;;
                return $user->home_number . ', ' . $ward_name . ', ' . $district_name . '. ' . $province_name;
            })
            ->editColumn('avatar', function ($user) {
                $user->avatar = $user->avatar != null ? url('/' . $user->avatar) : asset('public/img/default-avatar.jpg');
                $data = '<img src="' . $user->avatar . '" width="70px"></img>';
                return $data;
            })
            ->rawColumns(['avatar', 'action'])
            ->make(true);
    }
    public function getUserRole($role)
    {
        $user = User::with('revenues', 'shipper')->where('role', $role)->where('delete_status', 0)->where('status', 'active');
        return datatables()->of($user)
            ->addColumn('action', function ($user) {
                $action = [];
                //role shipper
                if ($user->role == "shipper") {
                    $action[] = '<a style="float:left" href="#" onclick="exportBooking(' . $user->id . ')" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-edit"></i> Xuất đơn hàng</a>';
                    // $action[] = '<a style="float:left" href="' . url('admin/shippers/refresh-book/' . $user->id) . '" class="btn btn-xs btn-default" onclick="if(!confirm(\'Bạn chắc chắn muốn làm mới phân công của shipper này không ?\')) return false;"><i class="fa fa-refresh"></i> Làm mới ĐH</a>';
                    $action[] = '<a style="float:left" href="' . url('admin/shippers/manage-scope/' . $user->id) . '" class="btn btn-xs btn-default"><i class="fa fa-refresh"></i> Phân khu vực</a>';
                }
                //role customer
                if ($user->role == "customer") {
                    $action[] = '<a style="float:left" href="#" onclick="exportBooking(' . $user->id . ')" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-edit"></i> Xuất đơn hàng</a>';
                    $action[] = '<button type="button" class="btn btn-info btn-xs" onclick="showModal(' . $user->id . ')"><i class="icon-bell" aria-hidden="true"></i> Thông báo nhanh</button>';
                    $action[] = '<a href="' . url('admin/customers/withdrawal/' . $user->id) . '" class="btn btn-xs btn-default" onclick="return confirm(\'Bạn có chắc chắn muốn rút tiền?\');"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span> Rút tiền</a>';
                }
                $action[] = '<a class="btn btn-xs btn-primary edit_user" href="javascript:void(0);" name="' . $user->id . '" class="uuid"> <i class="glyphicon glyphicon-edit"></i> Sửa </a>';
                $action[] = '<div style="float: left">' . Form::open(['method' => 'DELETE', 'url' => ['admin/'. $user->role. '/' . $user->id]]) .
                    '<button class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash-o"></i> Xóa</button>' .
                    Form::close() . '</div>';

                return implode(' ', $action);
            })
            ->addColumn('full_address', function ($user) {
                $full_address = '';
                if ($user->district_id != null && $user->ward_id != null) {
                    $district_name = District::find($user->district_id)->name;
                    $ward_name = Ward::find($user->ward_id)->name;
                    $full_address = $user->home_number . ', ' . $ward_name . ', ' . $district_name;
                }
                return $full_address;
            })
            ->addColumn('revenue_price', function ($user) {
                if ($user->role == "shipper") {
                    $data = 0;
                    if ($user->revenues != null) {
                        $data = round($user->revenues->total_price - $user->revenues->price_paid);
                    }
                    return number_format($data) . '<br/><a style="margin-top: 5px" href="#" onclick="shipperPaid([' . $user->id . ', \'price_paid\', ' . $data . '])" class="btn btn-xs btn-success"> Thanh toán</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('avatar', function ($user) {
                return ($user->avatar != null ? '<a href="javascript:void(0);" class="img_modal"> <img width="30" alt="' . $user->name . '" src="' . asset($user->avatar) . '"></a>' : "<img src=" . asset('public/img/default-avatar.jpg') . " width='30'/>");
            })
            //role shipper
            ->addColumn('revenue_cod', function ($user) {
                if ($user->role == "shipper") {
                    $data = 0;
                    if ($user->revenues != null) {
                        $data = round($user->revenues->total_COD - $user->revenues->COD_paid);
                    }
                    return number_format($data) . '<br/><a style="margin-top: 5px" href="#" onclick="shipperPaid([' . $user->id . ', \'COD_paid\', ' . $data . '])" class="btn btn-xs btn-success"> Thanh toán</a>';
                } else {
                    return '';
                }
            })
           //role customer
            ->editColumn('is_advance_money', function ($user) {
                if ($user->role == "customer") {
                    $name = '';
                    if ($user->is_advance_money == 0) {
                        $name .= '<img src="' . asset('public/img/incorect.png') . '" width="30px"></img>' ;
                    } elseif ($user->is_advance_money == 1) {
                        $name .= '<img src="' . asset('public/img/corect.png') . '" width="30px"></img>' ;
                    }
                    return $name;
                } else {
                    return '';
                }
            })
            ->addColumn('owe', function ($user) {
                if ($user->role == "customer") {
                    $booking = Booking::where('sender_id', $user->id)->where('owe', 0)->where(function ($query) {
                        $query->where('status', 'completed');
                    });
                    if (Auth::user()->role == 'collaborators') {
                        $user_id = Auth::user()->id;
                        $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
                        $booking = $booking->whereIn('last_agency', $scope);
                    }
                    $booking = $booking->select('bookings.*');
                    $data = round(($booking->sum('price') + $booking->sum('incurred')) - $booking->sum('paid'));
                    return number_format($data) . '<br/><a style="margin-top: 5px" href="' . url('admin/customers/owe/' . $user->id) . '" class="btn btn-xs btn-success"> Chi tiết</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('total_COD', function ($user) {
                if ($user->role == "customer") {
                    $cod = Booking::where('sender_id', $user->id)->where('status', 'completed')->where('COD', '>', 0)->where('COD_status', 'pending');
                    if (Auth::user()->role == 'collaborators') {
                        $scope = Collaborator::where('user_id', Auth::user()->id)->pluck('agency_id');
                        $cod = $cod->whereIn('last_agency', $scope);
                    }
                    $cod = $cod->sum('COD');
                    return number_format($cod) . '<br/><a style="margin-top: 5px" href="' . url('admin/COD_details/' . $user->id) . '" class="btn btn-xs btn-success">Chi tiết</a>';
                } else {
                    return '';
                }
            })
            ->addColumn('wallet', function ($user) {
                if ($user->role == "customer") {
                $wallet = 0;
                    $cod = Booking::where('sender_id', $user->id)->where('status', 'completed')->where('COD', '>', 0)->where('COD_status', 'pending');
                    if (Auth::user()->role == 'collaborators') {
                        $scope = Collaborator::where('user_id', Auth::user()->id)->pluck('agency_id');
                        $cod = $cod->whereIn('last_agency', $scope);
                    }
                    $cod = $cod->sum('COD');
                    $booking = Booking::where('sender_id', $user->id)->where('owe', 0)->where(function ($query) {
                        $query->where('status', 'completed');
                    });
                    if (Auth::user()->role == 'collaborators') {
                        $user_id = Auth::user()->id;
                        $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
                        $booking = $booking->whereIn('last_agency', $scope);
                    }
                    $booking = $booking->select('bookings.*');
                    $data = round(($booking->sum('price') + $booking->sum('incurred')) - $booking->sum('paid'));
                    $wallet = round($cod - $data);
                    return number_format($wallet);
                } else {
                    return '';
                }
            })

            ->rawColumns(['avatar', 'action', 'revenue_price', 'revenue_cod', 'owe', 'total_COD', 'wallet', 'is_advance_money'])
            ->make(true);
    }
    public static function generateNo($type)
    {
        $name = '';
        switch ($type) {
            case 'admin':
                $name = "AD";
                break;
            case 'warehouse':
                $name = "WH";
                break;
            case 'shipper':
                $name = "SP";
                break;
            case 'customer':
                $name = "CT";
                break;
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $count = User::where('role', $type)->count();
        $count = (int) $count + 1;

        do {
            $no = sprintf($name . "%05d", $count);
            $count++;
        } while (User::where('uuid', $no)->first());
        return $no;
    }
    public function checkExistUser(Request $request)
    {
        $check = array();
        $email = User::where('email', $request->email)->first();
        $phone_number = User::where('phone_number', $request->phone_number)->first();
        $uuid = User::where('uuid', $request->uuid)->first();
        if ($email) {
            array_push($check, "email_err");
        }
        if ($phone_number) {
            array_push($check, "phone_number_err");
        }
        return json_encode($check);
    }
    public function actionUser(Request $request)
    {
        DB::beginTransaction();
        try {
            ($request->action == "store") ? ($data = new User()) : ($data = User::where('id', $request->id)->first());

            if ($request->action == "store") {
                $uuid = UserController::generateNo($request->role);
                $data->uuid = $uuid;
                $data->email = $request->email;
                $data->phone_number = $request->phone_number;
                $data->password = Hash::make($request->password);
            } else {
                if ($request->password != null) {
                    $data->password = Hash::make($request->password);
                }
            }
            $data->name = $request->name;
            $data->home_number = $request->home_number;
            $data->province_id = $request->province_id;
            $data->district_id = $request->district_id;
            $data->ward_id = $request->ward_id;
            $data->birth_day = $request->birth_day;
            $data->id_number = $request->id_number;
            $data->bank_account = $request->bank_account;
            $data->bank_account_number = $request->bank_account_number;
            $data->bank_name = $request->bank_name;
            $data->bank_branch = $request->bank_branch;
            $data->role = $request->role;
            if($data->role == "customer") 
            {
                $data->is_advance_money = $request->is_advance_money;
            }
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $name = $file->getClientOriginalName();
                $exection = $file->getClientOriginalExtension();
                $file->move(public_path() . '/img/avatar/', $name);
                $data->avatar = '/public/img/avatar/' . $name;
            }

            $data->save();
            DB::commit();
            return json_encode(true);
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function getShipper()
    {
        $user_id = Auth::user()->id;
        $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
        $user = User::with('revenues', 'shipper')->where('role', 'shipper')->where('delete_status', 0)->where('status', 'active');
        if (Auth::user()->role == 'collaborators') {
            $user = $user->whereHas('shipper', function ($query) use ($scope) {
                $query->whereIn('agency_id', $scope);
            });
        }
        return datatables()->of($user)
            ->addColumn('action', function ($user) {
                $action = [];
                $action[] = '<a style="float:left" href="#" onclick="exportBooking(' . $user->id . ')" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-edit"></i> Xuất đơn hàng2</a>';
                $action[] = '<a style="float:left" href="' . url('admin/shippers/' . $user->id . '/edit') . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $action[] = '<div style="float: left">' . Form::open(['method' => 'DELETE', 'url' => ['admin/shippers/' . $user->id]]) .
                    '<button class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash-o"></i> Xóa</button>' .
                    Form::close() . '</div>';
                if (Auth::user()->role == 'admin') {
                    $action[] = '<div style="float: left">' . Form::open(['method' => 'GET', 'url' => ['admin/shippers/' . $user->id]]) .
                        '<button class="btn btn-xs btn-info" type="submit"><i class="fa fa-eye"></i> Chi tiết</button>' .
                        Form::close() . '</div>';
                }
                $action[] = '<a style="float:left" href="' . url('admin/shippers/refresh-book/' . $user->id) . '" class="btn btn-xs btn-default" onclick="if(!confirm(\'Bạn chắc chắn muốn làm mới phân công của shipper này không ?\')) return false;"><i class="fa fa-refresh"></i> Làm mới ĐH</a>';
                $action[] = '<a style="float:left" href="' . url('admin/shippers/manage-scope/' . $user->id) . '" class="btn btn-xs btn-default"><i class="fa fa-refresh"></i> Phân khu vực</a>';
                return implode(' ', $action);
            })
            ->addColumn('agency', function ($user) {
                $data = '';
                if (!empty($user->shipper)) {
                    $data = Agency::find($user->shipper->agency_id)->name;
                }
                return $data;
            })
            ->addColumn('revenue_price', function ($user) {
                $data = 0;
                if ($user->revenues != null) {
                    $data = round($user->revenues->total_price - $user->revenues->price_paid);
                }
                return number_format($data) . '<br/><a style="margin-top: 5px" href="#" onclick="shipperPaid([' . $user->id . ', \'price_paid\', ' . $data . '])" class="btn btn-xs btn-success"> Thanh toán</a>'; //<a href="' . url('admin/shippers/detail_total_cod/' . $user->id . '?type=ship&name=' . $user->name) . '" class="btn btn-xs btn-info">Chi tiết</a>
            })
            ->addColumn('revenue_cod', function ($user) {
                $data = 0;
                if ($user->revenues != null) {
                    $data = round($user->revenues->total_COD - $user->revenues->COD_paid);
                }
                return number_format($data) . '<br/><a style="margin-top: 5px" href="#" onclick="shipperPaid([' . $user->id . ', \'COD_paid\', ' . $data . '])" class="btn btn-xs btn-success"> Thanh toán</a>'; //<a href="' . url('admin/shippers/detail_total_cod/' . $user->id . '?type=cod&name=' . $user->name) . '" class="btn btn-xs btn-info">Chi tiết</a>
            })
            ->editColumn('avatar', function ($user) {
                $user->avatar = $user->avatar != null ? url('/' . $user->avatar) : asset('public/img/default-avatar.jpg');
                $data = '<img src="' . $user->avatar . '" width="80px"></img>';
                return $data;
            })
            ->editColumn('uuid', function ($user) {
                return $user->uuid != null ? $user->uuid : '';
            })
            ->editColumn('name', function ($user) {
                $shipperLocation = ShipperLocation::where('user_id', $user->id)->first();
                $name = $user->name != null ? '<span onclick="statistical(' . $user->id . ')" style="cursor: pointer">' . $user->name : '<span onclick="statistical(' . $user->id . ')" style="cursor: pointer">';
                if (!empty($shipperLocation) && $shipperLocation->online == 1) {
                    $name .= ' <span class="badge badge-success"> Online </span>';
                } else {
                    $name .= ' <span class="badge badge-danger"> Offline </span>';
                }
                $name .= '</span>';
                return $name;
            })
            ->rawColumns(['avatar', 'action', 'revenue_price', 'revenue_cod', 'name'])
            ->make(true);
    }
    public function getWareHouse()
    {
        $user_id = Auth::user()->id;
        $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
        $user = User::with('revenues', 'shipper')->where('role', 'warehouse')->where('delete_status', 0)->where('status', 'active');
        if (Auth::user()->role == 'collaborators') {
            $user = $user->whereHas('shipper', function ($query) use ($scope) {
                $query->whereIn('agency_id', $scope);
            });
        }
        return datatables()->of($user)
            ->addColumn('action', function ($user) {
                $action = [];
                $action[] = '<a style="float:left" href="#" onclick="exportBooking(' . $user->id . ')" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-edit"></i> Xuất đơn hàng</a>';
                $action[] = '<a style="float:left" href="' . url('admin/warehouse/' . $user->id . '/edit') . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $action[] = '<div style="float: left">' . Form::open(['method' => 'DELETE', 'url' => ['admin/warehouse/' . $user->id]]) .
                    '<button class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash-o"></i> Xóa</button><br/>' .
                    Form::close() . '</div>';
                if (Auth::user()->role == 'admin') {
                    $action[] = '<div style="float: left">' . Form::open(['method' => 'GET', 'url' => ['admin/warehouse/' . $user->id]]) .
                        '<button class="btn btn-xs btn-info" type="submit"><i class="fa fa-eye"></i> Chi tiết</button>' .
                        Form::close() . '</div>';
                }
                $action[] = '<a style="float:left" href="' . url('admin/warehouse/refresh-book/' . $user->id) . '" class="btn btn-xs btn-default" onclick="if(!confirm(\'Bạn chắc chắn muốn làm mới phân công của shipper này không ?\')) return false;"><i class="fa fa-refresh"></i> Làm mới ĐH</a>';
                $action[] = '<a style="float:left" href="' . url('admin/warehouse/manage-scope/' . $user->id) . '" class="btn btn-xs btn-default"><i class="fa fa-refresh"></i> Phân khu vực</a>';
                return implode(' ', $action);
            })
            // ->addColumn('agency', function ($user) {
            //     $data = '';
            //     if (!empty($user->shipper)) {
            //         $data = Agency::find($user->shipper->agency_id)->name;
            //     }
            //     return $data;
            // })
            ->addColumn('revenue_price', function ($user) {
                $data = 0;
                if ($user->revenues != null) {
                    $data = round($user->revenues->total_price - $user->revenues->price_paid);
                }
                return number_format($data) . '<br/><a style="margin-top: 5px" href="#" onclick="shipperPaid([' . $user->id . ', \'price_paid\', ' . $data . '])" class="btn btn-xs btn-success"> Thanh toán</a>'; //<a href="' . url('admin/shippers/detail_total_cod/' . $user->id . '?type=ship&name=' . $user->name) . '" class="btn btn-xs btn-info">Chi tiết</a>
            })
            ->addColumn('revenue_cod', function ($user) {
                $data = 0;
                if ($user->revenues != null) {
                    $data = round($user->revenues->total_COD - $user->revenues->COD_paid);
                }
                return number_format($data) . '<br/><a style="margin-top: 5px" href="#" onclick="shipperPaid([' . $user->id . ', \'COD_paid\', ' . $data . '])" class="btn btn-xs btn-success"> Thanh toán</a>'; //<a href="' . url('admin/shippers/detail_total_cod/' . $user->id . '?type=cod&name=' . $user->name) . '" class="btn btn-xs btn-info">Chi tiết</a>
            })
            ->editColumn('avatar', function ($user) {
                $user->avatar = $user->avatar != null ? url('/' . $user->avatar) : asset('public/img/default-avatar.jpg');
                $data = '<img src="' . $user->avatar . '" width="80px"></img>';
                return $data;
            })
            ->editColumn('uuid', function ($user) {
                return $user->uuid != null ? $user->uuid : '';
            })
            ->editColumn('name', function ($user) {
                $shipperLocation = ShipperLocation::where('user_id', $user->id)->first();
                $name = $user->name != null ? '<span onclick="statistical(' . $user->id . ')" style="cursor: pointer">' . $user->name : '<span onclick="statistical(' . $user->id . ')" style="cursor: pointer">';
                if (!empty($shipperLocation) && $shipperLocation->online == 1) {
                    $name .= ' <span class="badge badge-success"> Online </span>';
                } else {
                    $name .= ' <span class="badge badge-danger"> Offline </span>';
                }
                $name .= '</span>';
                return $name;
            })
            // ->rawColumns(['avatar',  'name'])
            ->rawColumns(['avatar', 'action', 'revenue_price', 'revenue_cod', 'name'])
            ->make(true);
    }

    public function getAgency()
    {
        $agency = Agency::with('collaborators')->where([]);
        if (Auth::user()->role == 'collaborators') {
            $agency = $agency->whereHas('collaborators', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $agency->get();
        return datatables()->of($agency)
            ->addColumn('action', function ($agency) {
                $action = [];
                $check = PaidHistory::where('agency_id', $agency->id)->where('type', 0)->where('status', 0)->count();
                $action[] = '<a style="float:left" href="' . url('admin/agencies/liabilities/' . $agency->id) . '" class="btn btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i> Công nợ (<span style="color: #e3ff16; font-weight: bold">' . $check . '</span>)</a>';
                $action[] = '<a style="float:left" href="' . url('admin/agencies/' . $agency->id . '/edit') . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Sửa</a>';
                $action[] = '<div style="float: left">' . Form::open(['method' => 'DELETE', 'url' => ['admin/agencies/' . $agency->id]]) .
                    '<button class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash-o"></i> Xóa</button>' .
                    Form::close() . '</div>';
                return implode(' ', $action);
            })
            ->addColumn('scope', function ($agency) {
                $district_id = ManagementScope::where('agency_id', $agency->id)->pluck('district_id');
                $scope = District::whereIn('id', $district_id)->pluck('name')->toArray();
                return implode(', ', $scope);
            })
            ->editColumn('phone', function ($a) {
                return $a->phone != null ? $a->phone : '';
            })
            ->rawColumns(['scope', 'action', 'phone'])
            ->make(true);
    }

    public function getLiability($id)
    {
        $liability = PaidHistory::where('agency_id', $id)->where('type', 0)->where('status', 0)->get();
        return datatables()->of($liability)
            ->addColumn('action', function ($a) {
                return '<img onclick="changeStatus(' . $a->id . ')" src="' . asset('public/img/incorect.png') . '" width="30px"></img>';
            })
            ->editColumn('value', function ($a) {
                return number_format($a->value);
            })
            ->make(true);
    }

    public function statisticalBookShipper()
    {
        $arr = array(
            'day_receive_book' => 0,
            'day_send_book' => 0,
            'month_receive_book' => 0,
            'month_send_book' => 0
        );
        if (!isset(request()->id) || empty(request()->id)) {
            return json_encode($arr);
        }

        $arr['day_receive_book'] = BookDelivery::where('user_id', request()->id)
            ->where('category', 'receive')
            ->whereDate('created_at', date('Y-m-d'))
            ->count();
        $arr['day_send_book'] = BookDelivery::where('user_id', request()->id)
            ->where('category', 'send')
            ->whereDate('created_at', date('Y-m-d'))
            ->count();
        $arr['month_receive_book'] = BookDelivery::where('user_id', request()->id)
            ->where('category', 'receive')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        $arr['month_send_book'] = BookDelivery::where('user_id', request()->id)
            ->where('category', 'send')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        return json_encode($arr);
    }
}

<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Agency;
use App\Models\ReportImage;
use function count;
use function foo\func;
use Illuminate\Http\Request;
use App\Models\BookDelivery;
use App\Models\Booking;
use App\Models\Collaborator;
use App\Models\ManagementScope;
use App\Models\ManagementWardScope;
use App\Models\User;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Jobs\NotificationJob;
use Carbon\Carbon;
use function dd;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use function in_array;
use function microtime;
use function url;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['only' => [
            'removeBookingByTime'
        ]]);
    }

    protected function getBookingScope()
    {
        $user_id = Auth::user()->id;
        $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
        $ward = ManagementWardScope::whereIn('agency_id', $scope)->pluck('ward_id');
        return $ward;
    }
    public function detailBooking($id)
    {
        $booking = DB::table('bookings as b')
        ->leftJoin('districts as d1','d1.id','b.send_district_id')
        ->leftJoin('districts as d2','d2.id','b.receive_district_id')
        ->where('b.id', $id)->select('b.*','d1.name as send_district_name','d2.name as receive_district_name')->first();
        $log = DB::table('notifications')->where('booking_id', $id)->get();
        $shipper =  BookDelivery::where('book_id', $id)->where('category', 'receive')->first();
        return response()->json(['booking' => $booking, 'log' => $log, 'shipper' => $shipper]);
    }
    public function allBooking()
    {
        $booking = new Booking;
        $booking = $booking->orderBy('id', 'DESC');
        return datatables()->of($booking)
            ->editColumn('uuid', function ($b) {
                return '<a href="javascript:void(0);" name="' . $b->id . '" class="uuid">' . $b->uuid . '</a>';
            })
            ->editColumn('image_order', function ($b) {
                return ($b->image_order != null ? '<a href="javascript:void(0);" class="img_modal"> <img width="30" alt="' . $b->uuid . '" src="' . asset('public/' . $b->image_order) . '"></a>' : "<img src='../public/img/not-found.png' width='30'/>");
            })
            ->addColumn('send_district_name', function ($b) {
                $district_name = District::find($b->send_district_id)->name;
                return   $district_name;
            })
            ->addColumn('receive_district_name', function ($b) {
                $district_name = District::find($b->receive_district_id)->name;
                return  $district_name;
            })
            ->rawColumns(['uuid', 'image_order', 'send_district_name', 'receive_district_name'])
            ->make(true);
    }
    public function getBookingStatus(Request $request)
    {
        $booking = new Booking;
        if ($request->status == "all") {
            $booking = $booking->orderBy('id', 'DESC');
        } else {
            $booking = $booking->orderBy('id', 'DESC')->where('status', $request->status);
        }
        return datatables()->of($booking)
            ->addColumn('action', function ($b) {
                $action = [];
                $check = BookDelivery::where('book_id', $b->id)->where('category', 'receive')->where('status', 'processing')->first();
                if (empty($check)) {
                    // $action[] = '<div style="display: inline-flex"><a href="#"  name="' . $b->id . '"  class="btn btn-xs btn-primary btnAssign"><i class="fa fa-motorcycle"></i> Phân công</a>';
                } else {
                    // $action[] = '<div style="display: inline-flex"><a href="#" name="' . $b->id . '" class="btn btn-xs btn-success btnReassign"><i class="fa fa-motorcycle"></i> Phân công lại</a>';
                    if ($b->payment_type == 1) {
                        $action[] = '<a data-toggle="popover" data-placement="top" data-html="true" title="<p><b>Đã thanh toán</b></p>" 
                        data-content="<div style=\'display: inline-flex\'><input id=\'owe\' style=\'transform: scale(1.5);\' onclick=\'changeUrl()\' type=\'checkbox\'> 
                        <a id=\'owe_submit\' href=' . url('admin/booking/completed/receive/' . $b->id) . ' class=\'btn btn-xs btn-success\' style=\'background: green; margin-left: 10px\'>
                        <i class=\'fa fa-check\'></i> Thực hiện</a></div>" class="btn btn-xs btn-success" style="background: green">Đã lấy</a>';
                    } else {
                        $action[] = '<a href="' . url('admin/booking/completed/receive/' . $b->id) . '" class="btn btn-xs btn-success" style="background: green" ><i class="fa fa-check"></i> Đã lấy</a>';
                    }
                    $action[] = '<a style="background: pink" href="' . url('admin/booking/delay/receive/' . $b->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-clock-o" aria-hidden="true"></i> Delay</a>';
                }
                $action[] = '<a style="background: rgba(131,1,7,0.98)" href="' . url('admin/booking/cancel/new/' . $b->id) . '" onclick="if(!confirm(\'Bạn chắc chắn muốn hủy đơn hàng này không ?\')) return false;" class="btn btn-xs btn-primary"><i class="fa fa-remove"></i> Hủy</a></div>';

                $action[] = '<div style="margin-top: 5px; display: inline-flex"><a href="' . url('admin/booking/print/new/' . $b->id) . '" class="btn btn-xs btn-info"><i class="fa fa-print" aria-hidden="true"></i> in hóa đơn</a>';
                $action[] = '<a style="background: rgba(159,158,25,0.81)" href="#"  name="' . $b->id . '" class="btn btn-xs btn-primary btnEdit"><i class="fa fa-edit"></i> Sửa</a>';
                $action[] = '<a style="background: rgba(73,4,70,0.87)" href="' . url('admin/booking/delete/new/' . $b->id) . '" onclick="if(!confirm(\'Bạn chắc chắn muốn xóa đơn hàng này không ?\')) return false;" class="btn btn-xs btn-primary"><i class="fa fa-trash"></i> Xóa</a></div>';
                return implode(' ', $action);
            })
            ->editColumn('uuid', function ($b) {
                return '<a href="javascript:void(0);" name="' . $b->id . '" class="uuid">' . $b->uuid . '</a>';
            })
            ->editColumn('image_order', function ($b) {
                return ($b->image_order != null ? '<a href="javascript:void(0);" class="img_modal"> <img width="30" alt="' . $b->uuid . '" src="' . asset('public/' . $b->image_order) . '"></a>' : "<img src='../public/img/not-found.png' width='30'/>");
            })
            ->editColumn('price', function ($b) {
                return number_format($b->price);
            })
            ->editColumn('status', function ($b) {
                $title = '';
                switch ($b->status) {
                    case 'new':
                        $title = "Chờ xử lý";
                        break;
                    case 'taking':
                        $title = "Lấy hàng";
                        break;
                    case 'return':
                        $title = "Chuyển hoàn";
                        break;
                }
                return "<span style='font-size:10px'>" . $title . "</span>";
            })
            ->editColumn('is_prioritize', function ($user) {
                $name = '';
                if ($user->is_prioritize == 0) {
                    $name .= '<img src="' . asset('public/img/incorect.png') . '" width="30px"></img>';
                } elseif ($user->is_prioritize == 1) {
                    $name .= '<img src="' . asset('public/img/corect.png') . '" width="30px"></img>';
                }
                return $name;
            })
            ->addColumn('send_district_name', function ($b) {
                $district_name = District::find($b->send_district_id)->name;
                return   $district_name;
            })
            ->addColumn('receive_district_name', function ($b) {
                $district_name = District::find($b->receive_district_id)->name;
                return  $district_name;
            })
            ->rawColumns(['uuid', 'image_order', 'price', 'status', 'action', 'is_prioritize', 'send_district_name', 'receive_district_name'])
            ->make(true);
    }
    public static function generateBookID()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $countRecordToday = Booking::whereDate('created_at', date("Y-m-d"))->count();
        $countRecordToday = (int) $countRecordToday + 1;
        do {
            $id = sprintf("DH%s%'.03d", date('ymd') . '-', $countRecordToday);
            $countRecordToday++;
        } while (Booking::where('uuid', $id)->first());
        return $id;
    }
    protected function getAddress($province, $district, $ward, $home_number)
    {
        $province_name = Province::find($province)->name;
        $district_name = District::find($district)->name;
        $ward_name = Ward::find($ward)->name;;
        return $home_number . ', ' . $ward_name . ', ' . $district_name . '. ' . $province_name;
    }
    public function getCustomerDistrict($id)
    {
        $customer = User::where('district_id', $id)->where('role', 'customer')->select('id', 'name', 'phone_number')->get();
        return response()->json(['customer' => $customer]);
    }
    public function getListBookingAssign(Request $request)
    {
        $query = DB::table('bookings as b')
            ->leftJoin('book_deliveries as bd', 'b.id', 'bd.book_id')
            ->leftJoin('users as u', 'u.id', 'bd.user_id')
            ->where('b.sender_id', $request->sender_id)
            ->whereBetween('b.created_at', [$request->date_from, $request->date_to])
            ->select('b.id', 'b.uuid', 'b.name', 'b.send_name', 'b.send_phone', 'b.send_full_address', 'u.name as shipper_name');
        if ($request->status == 'all') {
            $query->whereIn('bd.category', ['receive', 'send', 'return', 'move']);
        } else {
            switch ($request->status) {
                case 'receive':
                    $query->where('b.status', 'new');
                    break;
                case 'send':
                    $query->where('bd.category', 'receive')
                        ->where('bd.status', 'completed');
                    break;
                case 'return':
                    $query->where('b.status', 'return')
                        ->where('b.sub_status', 'none');
                    break;
                case 'move':
                    $query->where('b.status', 'move')
                        ->where('b.sub_status', 'none');
                    break;
            }
        }
        $booking = $query->get();
        return response()->json(['booking' => $booking]);
    }

    public function actionBooking(Request $request)
    {
        DB::beginTransaction();
        try {
            ($request->action == "store") ? ($data = new Booking()) : ($data = Booking::where('id', $request->id)->first());
            $title = '';
            if ($request->action == "store") {
                $uuid = $this->generateBookID();
                $sender = User::where('id', $request->name_id_fr)->where('role', 'customer')->first();

                $data->uuid = $uuid;
                $data->sender_id = $sender->id;
                $data->send_district_id = $sender->district_id;
                $data->send_homenumber = $sender->home_number;
                $data->send_name = $sender->name;
                $data->send_phone = $sender->phone_number;
                $data->status = 'new';
                $title = ' vừa được tạo';
            } else {
                $title = ' vừa được chỉnh sửa';
            }
            $data->receive_name = $request->name_to;
            $data->receive_phone = $request->phone_number_to;
            $data->receive_district_id = $request->district_id_to;
            $data->receive_homenumber = $request->home_number_to;

            $data->name = $request->name;
            $data->receivable_price = $request->receivable_price;
            $data->product_price = $request->product_price;
            $data->ship_price = $request->ship_price;
            $data->note = $request->note;
            $data->is_prioritize = $request->is_prioritize;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $name =  $uuid . '_booking.png';
                $exection = $file->getClientOriginalExtension();
                $file->move(public_path() . '/img/order/', $name);
                $data->image_order = '/img/order/' . $name;
            }

            $data->save();
            DB::commit();
            //    Thông báo tới admin có đơn hàng mới
            $bookingTmp = $data->toArray();
            NotificationJob::logBooking($bookingTmp, $title);

            return json_encode(true);
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function countBooking()
    {

        $data['all'] = 0;
        $data['new'] = 0;
        $data['taking'] = 0;
        $data['warehouse'] = 0;
        $data['sending'] = 0;
        $data['completed'] = 0;
        $data['return'] = 0;
        $data['cancel'] = 0;
        $data['move'] = 0;
        // $data['received'] = 0;
        // $data['sented'] = 0;
        // $data['return'] = 0;
        // $data['cancel'] = 0;
        // $data['re-send'] = 0;
        $query = Booking::select('id', 'sender_id', 'status');

        if (Auth::user()->role == "customer") {
            $query->where('sender_id', Auth::user()->id);
        }
        $booking = '';


        $booking = $query->get();

        $data['all'] = count($booking);
        if (!empty($booking) && count($booking) > 0) {
            foreach ($booking as $item) {
                if ($item->status == 'new') {
                    $data['new']++;
                } elseif ($item->status == 'taking') {
                    $data['taking']++;
                } elseif ($item->warehouse == 1) {
                    $data['warehouse']++;
                } elseif ($item->status == 'sending') {
                    $data['sending']++;
                } elseif ($item->status == 'completed') {
                    $data['completed']++;
                } elseif ($item->status == 'return') {
                    $data['return']++;
                } elseif ($item->status == 'cancel') {
                    $data['cancel']++;
                } elseif ($item->status == 'move') {
                    $data['move']++;
                }
                //  elseif ($item->status == 'return') {

                //     if (!empty($item->returnDeliveries)) {
                //         $data['return']++;
                //     }
                //     if (!empty($item->requestDeliveries)) {
                //         $data['re-send']++;
                //     }
                // }
            }
        }
        return $data;
    }
    
    public function getQuickAssignNew()
    {
        if (Auth::user()->role == 'collaborators') {
            // $booking = Booking::where('status', 'new')->where('sub_status', 'none')->whereIn('send_ward_id', $this->getBookingScope())
            //     ->orwhere('status', 'taking')->where('sub_status', 'none')->whereIn('send_ward_id', $this->getBookingScope());
            $booking = Booking::where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('status', 'new')->where('sub_status', 'none')->whereIn('send_ward_id', $this->getBookingScope());
                });
                $q->orWhere(function ($q2) {
                    $q2->where('status', 'taking')->where('sub_status', 'none')->whereIn('send_ward_id', $this->getBookingScope());
                });
            });
        } else {
            $booking = Booking::whereIn('status', ['new', 'taking'])->where('sub_status', 'none');
        }

        // đơn hàng chưa phân công
        if (request()->type_assign == 'no_assign') {
            $booking = $booking->where('status', 'new');
        } else {
            $booking = $booking->where('status', 'taking');
        }

        if (request()->province_id != -1) {
            $booking = $booking->where('send_province_id', request()->province_id);
        }
        if (request()->district_id != -1) {
            $booking = $booking->where('send_district_id', request()->district_id);
        }
        if (request()->ward_id != -1) {
            $booking = $booking->where('send_ward_id', request()->ward_id);
        }
        if (!empty(request()->phone)) {
            $booking = $booking->where('send_phone', 'LIKE', '%' . request()->phone . '%');
        }

        $booking = $booking->with(['firstAgencies', 'currentAgencies', 'deliveries', 'shipperRecivcier'])
            ->orderBy('created_at', 'DESC')
            ->get();
        $shippers = User::where('role', 'shipper')
            ->where('status', 'active')
            ->where('delete_status', 0)
            ->get();
        $data = [
            'province_id' => request()->province_id,
            'district_id' => request()->district_id,
            'ward_id' => request()->ward_id,
            'phone' => request()->phone,
            'books' => $booking,
            'shippers' => $shippers
        ];

        return $data;
    }

    public function postQuickAssignNew()
    {
        $data['shipper_id'] = '';
        $data['book_ids'] = [];
        foreach (request()->inputs as $key => $value) {
            if ($value['name'] == 'shipper') {
                $data['shipper_id'] = $value['value'];
            } elseif ($value['name'] == 'books') {
                $data['book_ids'][] = $value['value'];
            }
        }

        if (empty($data['shipper_id'])) {
            return json_encode(['status' => 'Hãy chọn 1 shipper!']);
        }

        if (count($data['book_ids']) > 0) {
            foreach ($data['book_ids'] as $id) {
                $booking = Booking::find($id);
                $check = BookDelivery::where('book_id', $id)->where('category', request()->type_assign)->first();
                if (empty($check)) {
                    DB::beginTransaction();
                    $status = '';
                    $category = '';
                    try {
                        switch (request()->choose_status) {
                            case 'receive':
                                $status = 'taking';
                                $category = 'receive';
                                break;
                            case 'send':
                                $status = 'sending';
                                $category = 'send';
                                break;
                            case 'return':
                                $status = 'return';
                                $category = 'return';
                                break;
                            case 'move':
                                $status = 'move';
                                $category = 'move';
                                break;
                        }
                        $booking->update(['status' => $status]);
                        BookDelivery::insert([
                            'user_id' => $data['shipper_id'],
                            'send_address' => $booking->send_full_address,
                            'receive_address' => $booking->receive_full_address,
                            'book_id' => $id,
                            'category' => $category,
                            'sending_active' => 1,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return $e;
                    }
                } else {
                    DB::beginTransaction();
                    try {
                        BookDelivery::whereIn('book_id', $data['book_ids'])
                            ->where('sending_active', 1)
                            ->update(['user_id' => $data['shipper_id']]);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return $e;
                    }
                }
            }
            // if (request()->type_assign == 'no_assign') {
            //     foreach ($data['book_ids'] as $id) {
            //         $booking = Booking::find($id);
            //         $check = BookDelivery::where('book_id', $id)->where('category', 'receive')->first();
            //         if (empty($check)) {
            //             DB::beginTransaction();
            //             try {
            //                 $booking->update(['status' => 'taking']);
            //                 BookDelivery::insert([
            //                     'user_id' => $data['shipper_id'],
            //                     'send_address' => $booking->send_full_address,
            //                     'receive_address' => $booking->receive_full_address,
            //                     'book_id' => $id,
            //                     'category' => 'receive',
            //                     'sending_active' => 1,
            //                     'created_at' => Carbon::now(),
            //                     'updated_at' => Carbon::now(),
            //                 ]);
            //                 DB::commit();
            //             } catch (\Exception $e) {
            //                 DB::rollBack();
            //                 return $e;
            //             }
            //         }
            //     }
            // } else {
            //     DB::beginTransaction();
            //     try {
            //         BookDelivery::whereIn('book_id', $data['book_ids'])
            //             ->where('sending_active', 1)
            //             ->update(['user_id' => $data['shipper_id']]);
            //         DB::commit();
            //     } catch (\Exception $e) {
            //         DB::rollBack();
            //         return $e;
            //     }
            // }
        } else {
            return json_encode(['status' => 'Chọn ít nhất 1 đơn hàng!']);
        }

        return json_encode(['status' => 'success']);
    }
}

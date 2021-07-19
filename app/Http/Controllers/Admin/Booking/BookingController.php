<?php

namespace App\Http\Controllers\Admin\Booking;

use App\Http\Requests\AssignRequest;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\BookDelivery;
use App\Models\Booking;
use App\Models\Agency;
use App\Models\ManagementScope;
use App\Models\Setting;
use App\Models\Shipper;
use App\Models\District;
use App\Models\Province;
use App\Models\ShipperRevenue;
use App\Models\User;
use App\Models\Ward;
use App\Models\Notification;
use App\Models\NotificationUser;
use function back;
use Book;
use Carbon\Carbon;
use function dd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Collaborator;
use App\Models\ManagementWardScope;
// use Auth, Excel;
use Auth;
use Illuminate\Support\Facades\DB;
use function in_array;
use function is;
use function redirect;
use function url;
use function view;
use App\Helpers\NotificationHelper;
use App\Jobs\NotificationJob;
use App\Models\QRCode;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $breadcrumb = ['Quản lý đơn hàng'];
    //create booking
    public function allBooking()
    {
        // $this->updateNotificationReaded($request);

        $this->breadcrumb[] = 'tất cả đơn hàng';
        if (Auth::user()->role == 'collaborators') {
            $time = Booking::whereIn('send_ward_id', $this->getBookingScope())->where('sub_status', 'none')->whereIn('status', ['new', 'taking'])->min('created_at');
        } else {
            $time = Booking::Where('sub_status', 'none')->where(function ($query) {
                $query->where('status', 'new')->orWhere('status', 'taking');
            })->min('created_at');
        }
        $time_from = $time != null ? date("Y-m-d", strtotime($time)) : Carbon::today()->toDateString();
        return view('admin.elements.booking.index', ['active' => 'booking', 'breadcrumb' => $this->breadcrumb, 'time_from' => $time_from]);
    }
    public function assignBooking()
    {
        // $this->updateNotificationReaded($request);

        $this->breadcrumb[] = 'phân công đơn hàng';
        return view('admin.elements.booking.assign.index', ['active' => 'assign', 'breadcrumb' => $this->breadcrumb]);
    }
    protected function getBookingScope()
    {
        $user_id = Auth::user()->id;
        $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
        $ward = ManagementWardScope::whereIn('agency_id', $scope)->pluck('ward_id');
        return $ward;
    }

    protected function getAddress($province, $district, $ward, $home_number)
    {
        $province_name = Province::find($province)->name;
        $district_name = District::find($district)->name;
        $ward_name = Ward::find($ward)->name;;
        return $home_number . ', ' . $ward_name . ', ' . $district_name . '. ' . $province_name;
    }

    protected function getProperties()
    {
        $user_id = Auth::user()->id;
        $scope = Collaborator::where('user_id', $user_id)->pluck('agency_id');
        $shipper = User::Where('role', 'shipper')->where('delete_status', 0)->where('status', 'active');
        if (Auth::user()->role == 'collaborators') {
            $shipper = $shipper->with('shipper')->whereHas('shipper', function ($query) use ($scope) {
                $query->whereIn('agency_id', $scope);
            });
        }
        return $shipper->get();
    }

    public function printBooking($type, $id)
    {
        $booking = Booking::find($id);
        $agency = null;
        switch ($booking->status) {
            case 'taking':
                $shipper_id = BookDelivery::where('book_id', $booking->id)->where('category', 'receive')->where('status', 'processing')->first()->user_id;
                $agency_id = Shipper::where('user_id', $shipper_id)->first();
                if (!empty($agency_id)) {
                    $agency = Agency::find($agency_id->agency_id);
                }
                break;
            case 'sending':
                $check = BookDelivery::where('book_id', $booking->id)->where('category', 'send')->where('status', 'processing')->first();
                if (!empty(($check))) {
                    $shipper_id = $check->user_id;
                } else {
                    $shipper_id = BookDelivery::where('book_id', $booking->id)->where('category', 'receive')->where('status', 'completed')->first()->user_id;
                }
                $agency_id = Shipper::where('user_id', $shipper_id)->first();
                if (!empty($agency_id)) {
                    $agency = Agency::find($agency_id->agency_id);
                }
                break;
            case 'completed':
                $shipper_id = BookDelivery::where('book_id', $booking->id)->where('category', 'send')->where('status', 'completed')->first()->user_id;
                $agency_id = Shipper::where('user_id', $shipper_id)->first();
                if (!empty($agency_id)) {
                    $agency = Agency::find($agency_id->agency_id);
                }
                break;
            default:
                $agency = Agency::where('ward_id', $booking->send_ward_id)->first();
        }
        $user = null;
        if ($agency != null) {
            $collaborator = Collaborator::where('agency_id', $agency->id)->with('users')->first();
            if (!empty($collaborator)) {
                $user = $collaborator->users;
            }
        }
        return view('admin.elements.booking.print', ['booking' => $booking, 'agency' => $agency, 'collaborator' => $user, 'type' => $type]);
    }

    //create booking
    public function createBooking()
    {
        $this->breadcrumb[] = 'tạo đơn hàng thủ công';
        return view('admin.elements.booking.create.add', ['active' => 'create', 'breadcrumb' => $this->breadcrumb]);
    }

    public function postCreateBooking(CreateBookingRequest $req)
    {
        DB::beginTransaction();
        try {
            $sender_id = null;
            // $receiver_id = null;
            $sender_check = User::where('phone_number', $req->phone_number_fr)->where('role', 'customer')->where('delete_status', 0)->first();
            // $receiver_check = User::where('phone_number', $req->phone_number_to)->where('role', 'customer')->where('delete_status', 0)->first();
            if (!empty($sender_check)) {
                $sender_id = $sender_check->id;
            } else {
                $check_sender_delete = User::where('phone_number', $req->phone_number_fr)->where('role', 'customer')->where('delete_status', 1)->first();
                if (!empty($check_sender_delete)) {
                    $check_sender_delete->delete_status = 0;
                    $check_sender_delete->save();
                    $sender_id = $check_sender_delete->id;
                } else {
                    $user = new User();
                    $user->phone_number = $req->phone_number_fr;
                    $user->save();
                    $sender_id = $user->id;
                }
            }
            // if (!empty($receiver_check)) {
            //     $receiver_id = $receiver_check->id;
            // } else {
            //     $user = new User();
            //     $user->phone_number = $req->phone_number_to;
            //     $user->save();
            //     $receiver_id = $user->id;
            // }
            $booking = new Booking();
            $booking->sender_id = $sender_id;
            // $booking->receiver_id = $receiver_id;
            $booking->name = $req->name;
            $booking->send_name = $req->name_fr;
            $booking->send_phone = $req->phone_number_fr;
            $booking->send_province_id = $req->province_id_fr;
            $booking->send_district_id = $req->district_id_fr;
            $booking->send_ward_id = $req->ward_id_fr;
            $booking->send_homenumber = $req->home_number_fr;
            $booking->send_full_address = $this->getAddress($req->province_id_fr, $req->district_id_fr, $req->ward_id_fr, $req->home_number_fr);
            $booking->receive_name = $req->name_to;
            $booking->receive_phone = $req->phone_number_to;
            $booking->receive_province_id = $req->province_id_to;
            $booking->receive_district_id = $req->district_id_to;
            $booking->receive_ward_id = $req->ward_id_to;
            $booking->receive_homenumber = $req->home_number_to;
            $booking->receive_full_address = $this->getAddress($req->province_id_to, $req->district_id_to, $req->ward_id_to, $req->home_number_to);
            $booking->receive_type = $req->receive_type;
            $booking->price = $req->price;
            $booking->weight = $req->weight;
            $booking->transport_type = $req->transport_type;
            $booking->payment_type = $req->payment_type;
            $booking->COD = $req->cod;
            $booking->other_note = $req->other_note;
            $booking->status = 'new';

            // kiểm tra khách lần đầu tiên sử dụng hệ thống (khách mới)
            $check = Booking::where('sender_id', $req->user()->id)->count();
            if ($check == 0) {
                $booking->is_customer_new = 1;
            }
            //tạo uuid
            $id = $this->generateBookID();
            $booking->uuid = $id;
            //tạo image
            if ($req->hasFile('image_order')) {
                $file = $req->image_order;
                // $filename = date('Ymd-His-') . $file->getFilename() . '.' . $file->extension();
                $filename =  $id . '_booking.png';
                $filePath = 'img/order/';
                $movePath = public_path($filePath);
                $file->move($movePath, $filename);
                $booking->image_order = $filePath . $filename;
            }
            $booking->save();
            // $qrcode = new QRCode();
            // $qrcode->name = str_random(5) . $booking->id;
            // $qrcode->created_at = date('Y-m-d h:i:s');
            // $qrcode->used_at = date('Y-m-d h:i:s');
            // $qrcode->is_used = 1;
            // $qrcode->save();
            // $booking->uuid = $qrcode->name;

            DB::commit();

            // Thông báo tới admin có đơn hàng mới
            $bookingTmp = $booking->toArray();
            $bookingTmp['uuid'] = $booking->uuid;
            // $notificationHelper = new NotificationHelper();
            // $notificationHelper->notificationBooking($bookingTmp, 'admin', ' vừa được tạo', 'push_order');
            dispatch(new NotificationJob($bookingTmp, 'admin', ' vừa được tạo', 'push_order'));
            NotificationJob::logBooking($bookingTmp, ' vừa được tạo');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return redirect(url('admin/booking/new'))->with('success', 'tạo mới đơn hàng thành công');
    }


    //delete booking
    public function deleteBooking($active, $id)
    {
        if ($active == 'return') {
            $check = BookDelivery::where('id', $id)->first();
            if ($check != null) {
                if ($check->book_id != null) {
                    $id = $check->book_id;
                } else {
                    return redirect()->back()->with('danger', 'Đơn hàng không tồn tại');
                }
            }
        }
        DB::beginTransaction();
        try {
            BookDelivery::where('book_id', $id)->delete();
            Booking::find($id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return redirect()->back()->with('success', 'Xóa đơn hàng thành công');
    }

    //cancel booking
    public function cancelBooking($active, $id)
    {
        if ($active == 'return') {
            $check = BookDelivery::where('id', $id)->first();
            if ($check != null) {
                if ($check->book_id != null) {
                    $id = $check->book_id;
                } else {
                    return redirect()->back()->with('danger', 'Đơn hàng không tồn tại');
                }
            }
        }
        DB::beginTransaction();
        try {
            BookDelivery::where('book_id', $id)->update(['status' => 'cancel']);
            Booking::find($id)->update(['status' => 'cancel', 'sub_status' => 'none']);
            DB::commit();

            // thông báo tới admin, customer, shipper khi hủy đơn hàng
            $notificationHelper = new NotificationHelper();
            $bookingTmp = Booking::find($id);
            $bookingTmp = $bookingTmp->toArray();
            $bookDeliveryTmp = BookDelivery::where('book_id', $id)->first();
            if ($bookDeliveryTmp && !empty($bookDeliveryTmp)) {
                $bookingTmp['shipper_id'] = $bookDeliveryTmp->user_id;
                $bookingTmp['book_delivery_id'] = $bookDeliveryTmp->id;
                // $notificationHelper->notificationBooking($bookingTmp, 'shipper', ' vừa được hủy', 'push_order_change');
                dispatch(new NotificationJob($bookingTmp, 'shipper', ' vừa được hủy', 'push_order_change'));
            }
            // $notificationHelper->notificationBooking($bookingTmp, 'admin', ' vừa được hủy', 'push_order_change');
            // $notificationHelper->notificationBooking($bookingTmp, 'customer', ' vừa được hủy', 'push_order_change');
            dispatch(new NotificationJob($bookingTmp, 'admin', ' vừa được hủy', 'push_order_change'));
            dispatch(new NotificationJob($bookingTmp, 'customer', ' vừa được hủy', 'push_order_change'));
            NotificationJob::logBooking($bookingTmp, ' vừa được hủy');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return redirect()->back()->with('success', 'Hủy đơn hàng thành công');
    }



    //tạo mã uuid
    public static function generateBookID()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $countRecordToday = DB::table('bookings')->whereDate('created_at', date("Y-m-d"))->count();
        $countRecordToday = (int) $countRecordToday + 1;
        do {
            $id = sprintf("DH%s%'.03d", date('ymd') . '-', $countRecordToday);
            $countRecordToday++;
        } while (DB::table('bookings')->where('uuid', $id)->first());
        return $id;
    }
    //start import file excel add booking
    public static function importBooking(){
        Excel::load(Input::file('file'), function ($reader) {
            foreach ($reader->toArray() as $array) {
                BookingController::actionImportBooking($array);
            }
        });
        return redirect()->back()->with('success', 'Đã upload đơn hàng thành công');
    }
    public static function actionImportBooking($array){
        $shop=DB::table('users')->where('uuid',$array['shop'])->where('role','customer')->where('status','active')->first();
        $shipper=DB::table('users')->where('uuid',$array['shipper'])->where('role','shipper')->where('status','active')->first();
        $district= DB::table('districts')->where('name', 'like', '%' . $array['quan'] . '%')->select('id')->first();
        //tạo uuid
        $uuid = BookingController::generateBookID();
        $booking = new Booking();
        $booking->name=$array['ten_don_hang'];
        $booking->uuid=$uuid;
        $booking->sender_id = $shop->id;
        $booking->send_name = $shop->name;
        $booking->send_phone = $shop->phone_number;
        $booking->send_district_id=$shop->district_id;
        $booking->send_homenumber=$shop->home_number;
        $booking->receive_name=$array['ten_kh'];
        $booking->receive_phone=$array['sdt'];
        $booking->receive_district_id=$district->id;
        $booking->receive_homenumber=$array['dia_chi'];
        $booking->receivable_price=$array['tien_thu'];
        $booking->ship_price=$array['tien_ship'];
        $booking->product_price=$array['tien_hang'];
        $booking->note=$array['ghi_chu'];
        $booking->status='new';
        
        $booking->save();
        DB::commit();
       
        // Thông báo tới admin có đơn hàng mới
        $bookingTmp = $booking->toArray();
        // dispatch(new NotificationJob($bookingTmp, 'admin', ' vừa được tạo', 'push_order'));
        NotificationJob::logBooking($bookingTmp, ' vừa được tạo');
    }
   // end import file excel add booking
}

<?php

namespace App\Http\Controllers\API\Customer;

use App\Models\Agency;
use App\Models\BookDelivery;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\ReportImage;
use App\Models\SendAndReceiveAddress;
use App\Models\Shipper;
use App\Models\ShipperRevenue;
use App\Models\Booking;
use App\Models\DeliveryAddress;
use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\ManagementWardScope;
use App\Models\ManagementScope;
use App\Models\ShipperLocation;
use Carbon\Carbon;
use function dd;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use function in_array;
use function is;
use Uuid, Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Jobs\NotificationJob;

class OrderController extends ApiController
{

    public function getListBook(Request $req)
    {
        $limit = $req->get('limit', 10);
        $query = Booking::query();
        $query->where('sender_id', $req->user()->id);
        if (isset($req->status)) {
            if ($req->status == 'new') {
                $query->where('status', 'new');
            }
            if ($req->status == 'taking') {
                $query->where('status', 'taking');
            }
            if ($req->status == 'sending') {
                $query->where('status', 'sending');
            }
            if ($req->status == 'move') {
                $query->where('status', 'move');
            }
            if ($req->status == 'completed') {
                $query->where('status', 'completed');
            }
            // giao lai  
            if ($req->status == 're-send') {
                $query->where('bookings.status', 're-send');
                $query->where('bookings.sub_status', '!=', 'request-return');
                $query->join('book_deliveries', 'bookings.id', '=', 'book_deliveries.book_id');
                $query->where(function ($query) {
                    $query->where('book_deliveries.category', '=', 're-send');
                    $query->whereIn('book_deliveries.status', ['deny']);
                });
                $rows = $query->select('bookings.*', 'book_deliveries.category as deliveries_category', 'book_deliveries.id as book_deliverie_id', 'book_deliveries.status as delivery_status');
            }
            if ($req->status == 'return') {

                $query->join('book_deliveries', 'bookings.id', '=', 'book_deliveries.book_id');

                $query->where(function ($query) {
                    $query->where('bookings.status', 'return');
                    $query->where('book_deliveries.category', '=', 'return');
                    $query->where(function ($q) {
                        $q->whereIn('book_deliveries.status', ['delay', 'processing', 'completed']);
                    });
                });
                $query->orWhere(function ($q) {
                    $q->where('bookings.status', 're-send');
                    $q->where('bookings.sub_status', 'request-return');
                    $q->where('book_deliveries.status', '=', 'deny');
                    $q->where('book_deliveries.category', '=', 're-send');
                });
                $rows = $query->select('bookings.*', 'book_deliveries.category as deliveries_category', 'book_deliveries.id as book_deliverie_id', 'book_deliveries.status as delivery_status');
            }
            if ($req->status == 'cancel') {
                $query->where('status', 'cancel');
            }
        }
        if (empty($req->status) || $req->status == 'all') {
            $query->with('returnBookingInfo');
        }
        $query = $query->orderBy('bookings.created_at', 'desc');
        if ($req->status == 'return') {
            $query->orderBy('book_deliveries.status', 'desc');
        }

        $rows = $query->paginate($limit);
        foreach ($rows->items() as $query) {

            if (!empty($query->reportImages)) {
                $reportImage = $query->reportImages;
                foreach ($reportImage as $image) {
                    $image->image = url($image->image);
                }
            }

            $query->sender_info = [
                'name' => $query->send_name,
                'phone' => $query->send_phone,
                'address' => [
                    'province_id' => $query->send_province_id,
                    'district_id' => $query->send_district_id,
                    'ward_id' => $query->send_ward_id,
                    'home_number' => $query->send_homenumber,
                    'full_address' => $query->send_full_address
                ]
            ];
            $query->receiver_info = [
                'name' => $query->receive_name,
                'phone' => $query->receive_phone,
                'address' => [
                    'province_id' => $query->receive_province_id,
                    'district_id' => $query->receive_district_id,
                    'ward_id' => $query->receive_ward_id,
                    'home_number' => $query->receive_homenumber,
                    'full_address' => $query->receive_full_address
                ]
            ];
            unset($query->send_province_id, $query->send_district_id, $query->send_ward_id, $query->send_homenumber, $query->send_full_address, $query->send_name, $query->send_phone, $query->receive_name, $query->receive_phone, $query->receive_province_id, $query->receive_district_id, $query->receive_ward_id, $query->receive_homenumber, $query->receive_full_address);
        }
        return $this->apiOk($rows);
    }
    // listbook lấy danh sách trong ngày -chilam
    public function getListBookType($status, $type, Request $req)
    {
        date_default_timezone_set('Australia/Melbourne');
        $date = date('Y-m-d 00:00:00', time());

        //"updated_at": "2020-12-30 13:31:04"
        $limit = $req->get('limit', 10);
        $query = Booking::query();
        $query->where('sender_id', $req->user()->id);
        if (isset($type)) {
            if ($type == 'today') {
                $query->where('updated_at', '>=', $date);
            }
        }

        if (isset($status)) {
            if ($status == 'new') {
                $query->where('status', 'new');
            }
            if ($status == 'taking') {
                $query->where('status', 'taking');
            }
            if ($status == 'warehouse') {
                $query->where('warehouse', 1);
            }
            if ($status == 'sending') {
                $query->where('status', 'sending');
            }
            if ($status == 'move') {
                $query->where('status', 'move');
            }
            if ($status == 'completed') {
                $query->where('status', 'completed');
            }
            // giao lai  
            if ($status == 're-send') {
                $query->where('bookings.status', 're-send');
                $query->where('bookings.sub_status', '!=', 'request-return');
                $query->join('book_deliveries', 'bookings.id', '=', 'book_deliveries.book_id');
                $query->where(function ($query) {
                    $query->where('book_deliveries.category', '=', 're-send');
                    $query->whereIn('book_deliveries.status', ['deny']);
                });
                $rows = $query->select('bookings.*', 'book_deliveries.category as deliveries_category', 'book_deliveries.id as book_deliverie_id', 'book_deliveries.status as delivery_status');
            }
            if ($status == 'return') {

                $query->join('book_deliveries', 'bookings.id', '=', 'book_deliveries.book_id');

                $query->where(function ($query) {
                    $query->where('bookings.status', 'return');
                    $query->where('book_deliveries.category', '=', 'return');
                    $query->where(function ($q) {
                        $q->whereIn('book_deliveries.status', ['delay', 'processing', 'completed']);
                    });
                });
                $query->orWhere(function ($q) {
                    $q->where('bookings.status', 're-send');
                    $q->where('bookings.sub_status', 'request-return');
                    $q->where('book_deliveries.status', '=', 'deny');
                    $q->where('book_deliveries.category', '=', 're-send');
                });
                $rows = $query->select('bookings.*', 'book_deliveries.category as deliveries_category', 'book_deliveries.id as book_deliverie_id', 'book_deliveries.status as delivery_status');
            }
            if ($status == 'cancel') {
                $query->where('status', 'cancel');
            }
        }
        if (empty($status) || $status == 'all') {
            $query->with('returnBookingInfo');
        }
        $query = $query->orderBy('bookings.created_at', 'desc');
        if ($status == 'return') {
            $query->orderBy('book_deliveries.status', 'desc');
        }

        $rows = $query->paginate($limit);
        foreach ($rows->items() as $query) {

            if (!empty($query->reportImages)) {
                $reportImage = $query->reportImages;
                foreach ($reportImage as $image) {
                    $image->image = url($image->image);
                }
            }

            $query->sender_info = [
                'name' => $query->send_name,
                'phone' => $query->send_phone,
                'address' => [
                    'province_id' => $query->send_province_id,
                    'district_id' => $query->send_district_id,
                    'ward_id' => $query->send_ward_id,
                    'home_number' => $query->send_homenumber,
                    'full_address' => $query->send_full_address
                ]
            ];
            $query->receiver_info = [
                'name' => $query->receive_name,
                'phone' => $query->receive_phone,
                'address' => [
                    'province_id' => $query->receive_province_id,
                    'district_id' => $query->receive_district_id,
                    'ward_id' => $query->receive_ward_id,
                    'home_number' => $query->receive_homenumber,
                    'full_address' => $query->receive_full_address
                ]
            ];
            unset($query->send_province_id, $query->send_district_id, $query->send_ward_id, $query->send_homenumber, $query->send_full_address, $query->send_name, $query->send_phone, $query->receive_name, $query->receive_phone, $query->receive_province_id, $query->receive_district_id, $query->receive_ward_id, $query->receive_homenumber, $query->receive_full_address);
        }
        return $this->apiOk($rows);
    }

    public function lastedBookSender(Request $req)
    {

        try {
            $query = Booking::where('sender_id', $req->user()->id)->orderBy('id', 'DESC');
            if ($req->receive_phone) {
                $query->where('receive_phone', 'LIKE', '%' . $req->receive_phone . '%');
            }
            $result = $query->first();
            if (empty($result)) {
                return [];
            }

            $result->sender_info = [
                'name' => $result->send_name,
                'phone' => $result->send_phone,
                'address' => [
                    'province_id' => $result->send_province_id,
                    'district_id' => $result->send_district_id,
                    'ward_id' => $result->send_ward_id,
                    'home_number' => $result->send_homenumber,
                    'full_address' => $result->send_full_address
                ],
                'location' => [
                    'lat' => $result->send_lat,
                    'lng' => $result->send_lng
                ]
            ];
            $result->receiver_info = [
                'name' => $result->receive_name,
                'phone' => $result->receive_phone,
                'address' => [
                    'province_id' => $result->receive_province_id,
                    'district_id' => $result->receive_district_id,
                    'ward_id' => $result->receive_ward_id,
                    'home_number' => $result->receive_homenumber,
                    'full_address' => $result->receive_full_address
                ],
                'location' => [
                    'lat' => $result->receive_lat,
                    'lng' => $result->receive_lng
                ]
            ];
            $result->total_price = $result->payment_type == 1 ? @$result->price + @$result->incurred :
                @$result->price + @$result->incurred + @$result->COD;
            unset($result->send_province_id, $result->send_district_id, $result->send_ward_id, $result->send_homenumber, $result->send_full_address, $result->send_name, $result->send_phone, $result->receive_name, $result->receive_phone, $result->receive_province_id, $result->receive_district_id, $result->receive_ward_id, $result->receive_homenumber, $result->receive_full_address, $result->send_lat, $result->send_lng, $result->receive_lat, $result->receive_lng, $result->send_address, $result->receive_address, $result->receive_homenumber, $result->receive_full_address);
            return $this->apiOk($result);
        } catch (\Exception $e) {
            return $this->apiError($e->getMessage());
        }
    }
    public function updateNote($id, Request $req)
    {
        $bookDelivery = BookDelivery::where(['book_deliveries.book_id' => $id])
            ->join('bookings', 'bookings.id', '=', 'book_deliveries.book_id')
            ->where('bookings.sender_id', $req->user()->id)
            ->whereIn('category', ['receive', 'receive-and-send'])->first();

        if (empty($bookDelivery)) {
            return $this->apiErrorWithCode('Không tìm thấy đơn hàng này', 404);
        }
        $validator = Validator::make($req->all(), [
            'other_note' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->apiError($validator->errors()->first());
        }
        $book = $bookDelivery->booking;
        $now = date('d/m/Y H:i');
        if (empty($book->other_note)) {
            $book->other_note = '\n' . $now . ' ' . $req->other_note;
        } else {
            $book->other_note .= '\n' . $now . ' ' . $req->other_note;
        }
        DB::beginTransaction();
        try {
            if ($book->save()) {
                DB::commit();
                return $this->apiOk('success');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiError($e->getMessage());
        }
        return $this->apiOk('success');
    }
    //chi don hang status = return và sub status = deny tức đơn hàng giao lại mới  yêu cầu trả lại
    public function RequestReturn($id, Request $req)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::where(['id' => $id, 'sender_id' => $req->user()->id])->first();
            if (empty($booking)) {
                return $this->apiErrorWithCode('Không tìm thấy đơn hàng này', 404);
            }
            $delivery = BookDelivery::where('book_id', $id);
            $bookingTmp = $booking->toArray();

            if ($booking->status == 're-send') {
                $delivery = $delivery->where('category', 're-send')->first();
                if (!empty($delivery)) {
                    $booking->sub_status = 'request-return';
                    //$delivery->status = 'deny';
                    $delivery->save();
                }
                $now = date('d/m/Y H:i');
                if (empty($booking->other_note)) {
                    $booking->other_note = '\n ' . $now . ' Khách hàng yêu cầu trả hàng';
                } else {
                    $booking->other_note .= '\n ' . $now . ' Khách hàng yêu cầu trả hàng';
                }
            }

            $bookingTmp['book_delivery_id'] = $delivery->id;
            // thông báo tới khách hàng là đơn hàng đã được giao
            dispatch(new NotificationJob($bookingTmp, 'customer', ' đã được giao lại/trả lại', 'push_order_change'));

            $booking->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return $this->apiOk($booking);
    }
    //bộ lọc
    public function getFilter(Request $req)
    {
        try {
            // thời gian từ/đến - trạng thái đơn hàng - mã đơn hàng - tên khách hàng - sdt khách hàng
            date_default_timezone_set('Australia/Melbourne');
            $date = date('Y-m-d 00:00:00', time());

            //"updated_at": "2020-12-30 13:31:04"
            $limit = $req->get('limit', 10);
            $query = Booking::query();
            $query->where('sender_id', $req->user()->id);
            $check_date = 0;
            $check_uuid = 0;
            $check_receive_name = 0;
            $check_receive_phone = 0;
            if (($req->fromdate && $req->todate) == null || ($req->fromdate && $req->todate) == 'null' || ($req->fromdate && $req->todate) == 'undefined') {
                $check_date = 1;
            }
            if ($req->check_uuid == null ||  $req->check_uuid == 'null' ||  $req->check_uuid == 'undefined') {
                $check_uuid = 1;
            }
            if ($req->receive_name == null ||  $req->receive_name == 'null' ||  $req->receive_name == 'undefined') {
                $check_receive_name = 1;
            }
            if ($req->check_receive_phone == null ||  $req->check_receive_phone == 'null' ||  $req->check_receive_phone == 'undefined') {
                $check_receive_phone = 1;
            }
            if (isset($req->status)) {
                if ($req->status == 'new') {
                    $query->where('status', 'new');
                }
                if ($req->status == 'taking') {
                    $query->where('status', 'taking');
                }
                if ($req->status == 'warehouse') {
                    $query->where('warehouse', 1);
                }
                if ($req->status == 'sending') {
                    $query->where('status', 'sending');
                }
                if ($req->status == 'move') {
                    $query->where('status', 'move');
                }
                if ($req->status == 'completed') {
                    $query->where('status', 'completed');
                }
                // giao lai  
                if ($req->status == 're-send') {
                    $query->where('bookings.status', 're-send');
                    $query->where('bookings.sub_status', '!=', 'request-return');
                    $query->join('book_deliveries', 'bookings.id', '=', 'book_deliveries.book_id');
                    $query->where(function ($query) {
                        $query->where('book_deliveries.category', '=', 're-send');
                        $query->whereIn('book_deliveries.status', ['deny']);
                    });
                    $rows = $query->select('bookings.*', 'book_deliveries.category as deliveries_category', 'book_deliveries.id as book_deliverie_id', 'book_deliveries.status as delivery_status');
                }
                if ($req->status == 'return') {

                    $query->join('book_deliveries', 'bookings.id', '=', 'book_deliveries.book_id');

                    $query->where(function ($query) {
                        $query->where('bookings.status', 'return');
                        $query->where('book_deliveries.category', '=', 'return');
                        $query->where(function ($q) {
                            $q->whereIn('book_deliveries.status', ['delay', 'processing', 'completed']);
                        });
                    });
                    $query->orWhere(function ($q) {
                        $q->where('bookings.status', 're-send');
                        $q->where('bookings.sub_status', 'request-return');
                        $q->where('book_deliveries.status', '=', 'deny');
                        $q->where('book_deliveries.category', '=', 're-send');
                    });
                    $rows = $query->select('bookings.*', 'book_deliveries.category as deliveries_category', 'book_deliveries.id as book_deliverie_id', 'book_deliveries.status as delivery_status');
                }
                if ($req->status == 'cancel') {
                    $query->where('status', 'cancel');
                }
            }
            if (empty($req->status) || $req->status == 'all') {
                $query->with('returnBookingInfo');
            }
            $query = $query->orderBy('bookings.created_at', 'desc');
            if ($req->status == 'return') {
                $query->orderBy('book_deliveries.status', 'desc');
            }
            if (isset($req->fromdate) && $check_date == 0) {
                $query->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($req->fromdate)), date('Y-m-d 00:00:00', strtotime($req->todate))]);
            }
            if (isset($req->uuid) && $check_uuid == 0) {
                $query->where('uuid', 'like', '%' . $req->uuid . '%');
            }
            if (isset($req->receive_name) && $check_receive_name == 0) {
                $query->where('receive_name', 'like', '%' . $req->receive_name . '%');
            }
            if (isset($req->receive_phone) && $check_receive_phone == 0) {
                $query->where('receive_phone', 'like', '%' . $req->receive_phone . '%');
            }
            $rows = $query->paginate($limit);
            foreach ($rows->items() as $query) {

                if (!empty($query->reportImages)) {
                    $reportImage = $query->reportImages;
                    foreach ($reportImage as $image) {
                        $image->image = url($image->image);
                    }
                }

                $query->sender_info = [
                    'name' => $query->send_name,
                    'phone' => $query->send_phone,
                    'address' => [
                        'province_id' => $query->send_province_id,
                        'district_id' => $query->send_district_id,
                        'ward_id' => $query->send_ward_id,
                        'home_number' => $query->send_homenumber,
                        'full_address' => $query->send_full_address
                    ]
                ];
                $query->receiver_info = [
                    'name' => $query->receive_name,
                    'phone' => $query->receive_phone,
                    'address' => [
                        'province_id' => $query->receive_province_id,
                        'district_id' => $query->receive_district_id,
                        'ward_id' => $query->receive_ward_id,
                        'home_number' => $query->receive_homenumber,
                        'full_address' => $query->receive_full_address
                    ]
                ];
                unset($query->send_province_id, $query->send_district_id, $query->send_ward_id, $query->send_homenumber, $query->send_full_address, $query->send_name, $query->send_phone, $query->receive_name, $query->receive_phone, $query->receive_province_id, $query->receive_district_id, $query->receive_ward_id, $query->receive_homenumber, $query->receive_full_address);
            }
            return $this->apiOk($rows);
        } catch (\Exception $e) {
            return $e;
        }
    }
}

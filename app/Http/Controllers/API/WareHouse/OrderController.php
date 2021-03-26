<?php

namespace App\Http\Controllers\API\WareHouse;

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
use Uuid,
    Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Jobs\NotificationJob;

class OrderController extends ApiController
{
    public function getListBook(Request $req)
    {
        // $shipperOnline = ShipperLocation::where('user_id', request()->user()->id)->where('online', 1)->first();
        // if (empty($shipperOnline)) {
        //     return $this->apiErrorWithStatus(403, 'Kích hoạt chế độ Đang hoạt động để thấy đơn hàng1!');
        // }

        $limit = $req->get('limit', 10);
        $query = Booking::query()
            ->join('book_deliveries', 'bookings.id', '=', 'book_deliveries.book_id');
        // $query->where('bookings.status', '!=', 'cancel')->where('book_deliveries.user_id', $req->user()->id);
        if (isset($req->category)) {
            // $query->where(function ($query) {
            //     $query->where('book_deliveries.category', '=', 'warehouse')->where('book_deliveries.status', 'none');
            // });
            // $query->where('bookings.sub_status', '!=', 'deny')->where('bookings.status', '!=', 'completed'); $booking->warehouse = 1;
            $query->where('bookings.warehouse', 1);
            if (isset($req->keyword) && !empty($req->keyword)) {
                $query->where(function ($q) use ($req) {
                    $q->where('send_name', 'LIKE', '%' . $req->keyword . '%');
                    $q->orWhere('send_phone', 'LIKE', '%' . $req->keyword . '%');
                    $q->orWhere('send_full_address', 'LIKE', '%' . $req->keyword . '%');
                    $q->orWhere('name', 'LIKE', '%' . $req->keyword . '%');
                    $q->orWhere('uuid', 'LIKE', '%' . $req->keyword . '%');
                });
            }
            if (isset($req->district_id) && !empty($req->district_id)) {
                $query->where('bookings.send_district_id', 'LIKE', '%' . $req->district_id . '%');
            }
            if (isset($req->ward_id) && !empty($req->ward_id)) {
                $query->where('bookings.send_ward_id', 'LIKE', '%' . $req->ward_id . '%');
            }
        }
        $rows = $query->select('bookings.*', 'bookings.transport_type', 'book_deliveries.id', 'book_deliveries.user_id', 'book_deliveries.book_id', 'book_deliveries.category', 'book_deliveries.status', 'book_deliveries.last_move', 'book_deliveries.delay_total', 'book_deliveries.sending_active', 'book_deliveries.created_at as assign_time', 'book_deliveries.completed_at as completed_time')
            ->with('transactionTypeService.service')
            ->orderBy('is_prioritize', 'desc')
            ->orderBy('assign_time', 'desc')
            ->paginate($limit);

        foreach ($rows->items() as $item) {
            $item->reportDeliverImage;
            $item->sender_info = [
                'name' => $item->send_name,
                'phone' => $item->send_phone,
                'address' => [
                    'province_id' => $item->send_province_id,
                    'district_id' => $item->send_district_id,
                    'ward_id' => $item->send_ward_id,
                    'home_number' => $item->send_homenumber,
                    'full_address' => $item->send_full_address
                ],
                'location' => [
                    'lat' => $item->send_lat,
                    'lng' => $item->send_lng
                ]
            ];
            $item->receiver_info = [
                'name' => $item->receive_name,
                'phone' => $item->receive_phone,
                'address' => [
                    'province_id' => $item->receive_province_id,
                    'district_id' => $item->receive_district_id,
                    'ward_id' => $item->receive_ward_id,
                    'home_number' => $item->receive_homenumber,
                    'full_address' => $item->receive_full_address
                ],
                'location' => [
                    'lat' => $item->receive_lat,
                    'lng' => $item->receive_lng
                ]
            ];
            unset($item->send_province_id, $item->send_district_id, $item->send_ward_id, $item->send_homenumber, $item->send_full_address, $item->send_name, $item->send_phone, $item->receive_name, $item->receive_phone, $item->receive_province_id, $item->receive_district_id, $item->receive_ward_id, $item->receive_homenumber, $item->receive_full_address, $item->send_lat, $item->send_lng, $item->receive_lat, $item->receive_lng);
          
        }
        return $this->apiOk($rows);
    }
    public function updateBookWareHouse($id, Request $req)
    {
        $validator = Validator::make($req->all(), [
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiError($validator->errors()->first());
        }
        if (!in_array($req->status, ['input'])) {
            return $this->apiError('status invalid');
        }
        $delivery = BookDelivery::where('id', $id)->first();
        if (empty($delivery)) {
            return $this->apiError('task can not found');
        }

        $booking = Booking::find($delivery->book_id);
        // dd($booking);
        if (empty($booking)) {
            return $this->apiError('booking can not found');
        }
        $bookingTmp = $booking->toArray();
        $bookingTmp['book_delivery_id'] = $delivery->id;

        // không cho cập nhật bất kì cái gì khi đơn hàng đã hoàn tất
        if ($booking->status == 'completed') {
            return $this->apiError('Đơn hàng đã hoàn thành không thể cập nhật thêm!');
        }
        DB::beginTransaction();
        try {
            switch ($req->status) {
                case 'input':
                    DB::table('booking_warehouse')->insert([
                        'user_id' => $req->user()->id,
                        'book_id' => $id,
                        'shipper_id' => $delivery->user_id,
                        'status' => $booking->status,
                        'created_at'=>new \DateTime(),
                        'updated_at'=>new \DateTime(),
                    ]);
                    // $booking->sub_status = $booking->status;
                    $booking->warehouse = 1;
                    break;
                default:
            }

            $booking->save();
            DB::commit();
            $bookingTmp = $booking->toArray();
            NotificationJob::logBooking($bookingTmp, ' đã được nhập kho');
            return $this->apiOk('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiError($e->getMessage());
        }
    }
}

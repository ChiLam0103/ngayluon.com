<!-- Modal Phân công hàng loạt-->
{!! csrf_field() !!}
<div class="modal fade" id="shipperChangeStatusModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Shipper-Thay đổi đơn hàng</h4>
            </div>
            <div class="modal-body">
                <legend style="font-size:20px; color:red">Bộ lọc danh sách</legend>
                <div class="row">
                    <div class="col-lg-3">
                        <label>Shipper:</label>
                        {{ Form::select('shipper', \App\Models\User::getUserOption('shipper'), old('name_id_fr'), ['class' => 'form-control', 'style' => 'width:100%', 'id' => 'shipper', 'onchange' => 'loadCustomerFr()']) }}
                        <label>Trạng thái:</label>
                        <select class="form-control" id="assign" disabled>
                        </select>
                    </div>
                 
                    <div class="col-lg-4">
                        <label>Trạng thái đơn hàng:</label>
                        <select class="form-control" id="choose_status">
                            <option value="all">Tất cả</option>
                            <option value="receive">Chờ lấy hàng</option>
                            <option value="send">Chờ giao hàng</option>
                            <option value="return">Chờ trả lại  shop</option>
                            <option value="move">Chờ giao lại K/H</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <label>Ngày tạo đơn: </label>
                        <input type="date" id="date_from" name="date_from" class="form-control"
                            aria-describedby="sizing-addon2" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                    </div>
                    <div class="col-lg-4">
                        <label>Đến ngày:</label>
                        <input type="date" id="date_to" name="date_to" class="form-control"
                            aria-describedby="sizing-addon2" value="{{ \Carbon\Carbon::today()->toDateString() }}">

                    </div>
                    <div class="col-lg-4"><label>Click chọn để xem danh sách: </label><button type="button" id="btnViewAssign"
                            class="btn btn-primary form-control"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Xem</button></div>
                </div>
                <form action="" method="POST" id="form-quick-assign">
                    <legend style="font-size:20px; margin-top:3em;color:red">Phân công đơn hàng</legend>
                    <div class="row">
                        <div class="col-lg-3">
                            <label>Phân công cho Shipper:</label>
                            {{ Form::select('shipper', \App\Models\User::getUserOption('shipper'), old('name_id_fr'), ['class' => 'form-control', 'style' => 'width:100%', 'id' => 'shipper', 'onchange' => 'loadCustomerFr()']) }}
                            <label>Trạng thái:</label>
                            <select class="form-control" id="assign" disabled>
                            </select>
                        </div>
                        <div class="col-lg-9">
                            <table id="ul-book" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="check-all"></th>
                                        <th>Mã ĐH</th>
                                        <th>Tên ĐH</th>
                                        <th>Người gửi</th>
                                        <th>Số ĐT người gửi</th>
                                        <th>Địa chỉ người gửi</th>
                                        <th>Shipper đã phân công</th>
                                    </tr>
                                </thead>
                                <tbody style="max-height: 400px; overflow-y: scroll;"></tbody>
                            </table>
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <span id="msg-error" style="color: red"></span>
                <button type="button" class="btn btn-primary" id="save-quick-assign"><i class="fa fa-check" aria-hidden="true"></i> Phân công</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Thoát</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>


</script>

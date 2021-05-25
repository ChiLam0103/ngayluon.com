<!-- Modal xem phân công đơn hàng-->
<div class="modal fade" id="viewQuickAssignModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Xem danh sách đã phân công</h4>
            </div>
            <div class="modal-body">
                <legend style="font-size:20px;color:red">Phân công đơn hàng</legend>
                <div class="row">
                    <div class="col-lg-4">
                        <label>Danh sách Shipper:</label>
                        {{ Form::select('shipper', \App\Models\User::getUserOption('shipper'), old('name_id_fr'), ['class' => 'form-control', 'style' => 'width:100%', 'id' => 'shipper_id', 'onchange' => 'loadCustomerFr()']) }}
                    </div>
                    <div class="col-lg-4">
                        <label>Trạng thái:</label>
                        <select class="form-control" id="category">
                            <option value="receive">Lấy hàng</option>
                            <option value="send">Giao hàng</option>
                            <option value="return">Trả lại hàng</option>
                            <option value="receive-and-send">Vừa lấy vừa giao</option>
                            <option value="move">Giao lại</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <label></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"> </span> Từ
                                ngày</span>
                            <input type="date" id="date_from" name="date_from" class="form-control"
                                aria-describedby="sizing-addon2" value="{!! $time_from !!}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"> </span> Đến
                                ngày</span>
                            <input type="date" id="date_to" name="date_to" class="form-control"
                                aria-describedby="sizing-addon2"
                                value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                    </div>
                    <div class="col-lg-4"> <label></label> <button type="button" id="btnView"
                            class="btn btn-primary form-control">
                            <i class="fa fa-eye" aria-hidden="true"></i> Xem
                        </button></div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="table_booking" style="margin-top: 2em">
                        <table id="list_table_assign_booking" class=" boder portlet box green" width="100%">
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="msg-error" style="color: red"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Thoát</button>
            </div>
        </div>
    </div>
</div>
<script>

</script>

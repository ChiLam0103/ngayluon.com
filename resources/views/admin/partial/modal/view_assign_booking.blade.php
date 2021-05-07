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
                    <div class="col-lg-3">
                        <label>Phân công cho Shipper:</label>
                        {{ Form::select('shipper', \App\Models\User::getUserOption('shipper'), old('name_id_fr'), ['class' => 'form-control', 'style' => 'width:100%', 'id' => 'shipper', 'onchange' => 'loadCustomerFr()']) }}
                    </div>
                    <div class="col-lg-3">
                        <label>Hành động:</label>
                        <select class="form-control" id="choose_status">
                            <option value="taking">Đã phân</option>
                            <option value="sending">Hoãn </option>
                            <option value="remove">Đã hoàn thành</option>
                            <option value="remove">Từ chối</option>
                            <option value="remove">Hủy</option>
                          
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Trạng thái:</label>
                        <select class="form-control" id="choose_status">
                            <option value="taking">Lấy hàng</option>
                            <option value="sending">Giao hàng</option>
                            <option value="remove">Trả lại hàng</option>
                            <option value="remove">Vừa lấy vừa giao</option>
                            <option value="remove">Giao lại</option>
                        </select>
                    </div>
                    <div class="col-lg-3"> <label></label> <button type="button" id="view-quick-assign" class="btn btn-primary form-control">
                            <i class="fa fa-eye" aria-hidden="true"></i> Xem 
                        </button></div>
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

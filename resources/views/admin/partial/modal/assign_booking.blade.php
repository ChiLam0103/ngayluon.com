<!-- Modal Phân công hàng loạt-->
{!! csrf_field() !!}
<div class="modal fade" id="quickAssignModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Phân công hàng loạt</h4>
            </div>
            <div class="modal-body">
                <legend style="font-size:20px; color:red">Bộ lọc danh sách</legend>


                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Chờ lấy đơn</a></li>
                    <li><a data-toggle="tab" href="#menu1">Chờ giao đơn</a></li>
                    <li><a data-toggle="tab" href="#menu2">Chờ trả lại shop</a></li>
                    <li><a data-toggle="tab" href="#menu3">Chờ giao lại khách</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Danh sách Quận/Huyện:</label>
                                <select id="district_fr" onchange="loadCustomer(this.value)" name="district_id_to"
                                    class="form-control">
                                    <option>---Chọn Quận/Huyện---</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Danh sách K/H trong Quận/Huyện:</label>
                                <select class="form-control" name="customer" id="customer">
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <label>Ngày tạo đơn: </label>
                                <input type="date" id="date_from" name="date_from" class="form-control"
                                    aria-describedby="sizing-addon2"
                                    value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            </div>
                            <div class="col-lg-4">
                                <label>Đến ngày:</label>
                                <input type="date" id="date_to" name="date_to" class="form-control"
                                    aria-describedby="sizing-addon2"
                                    value="{{ \Carbon\Carbon::today()->toDateString() }}">

                            </div>
                            <div class="col-lg-4"><label>Click chọn để xem danh sách: </label><button type="button"
                                    id="btnViewAssign" class="btn btn-primary form-control"><i
                                        class="fa fa-hand-o-right" aria-hidden="true"></i> Xem</button></div>
                        </div>
                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <h3>Menu 1</h3>
                        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                            commodo consequat.</p>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <h3>Menu 2</h3>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                            laudantium, totam rem aperiam.</p>
                    </div>
                    <div id="menu3" class="tab-pane fade">
                        <h3>Menu 3</h3>
                        <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt
                            explicabo.</p>
                    </div>
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
                <button type="button" class="btn btn-primary" id="save-quick-assign"><i class="fa fa-check"
                        aria-hidden="true"></i> Phân công</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Thoát</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    function loadCustomer(id) {
        $("#customer option[value!='-1']").remove();
        $.ajax({
            type: "GET",
            url: '{{ url('/ajax/get_customer_district/') }}/' + id
        }).done(function(msg) {
            var i;
            for (i = 0; i < msg.customer.length; i++) {
                if (msg.customer[i]['id'] == '{{ @old('ward_id_fr') }}') {
                    $('select[name="customer"]').append('<option value="' + msg.customer[i]['id'] +
                        '" selected>' + msg.customer[i]['name'] + '</option>')
                } else {
                    $('select[name="customer"]').append('<option value="' + msg.customer[i]['id'] + '">' + msg
                        .customer[i]['name'] + ' - ' + msg.customer[i]['phone_number'] + '</option>')
                }
            }
        });
    }

</script>

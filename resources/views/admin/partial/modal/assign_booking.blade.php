<!-- Modal Phân công hàng loạt-->
{{-- <form action="" method="POST" id="form-quick-assign"> --}}
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
                    <legend style="font-size:15px">Bộ lọc danh sách</legend>
                    <div class="row">
                        <div class="col-lg-3">
                            <select id="district_fr" onchange="loadCustomer(this.value)" name="district_id_to"
                                class="form-control">
                                <option>Chọn Quận/Huyện---</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control" name="customer" id="customer"> </select>
                        </div>
                        <div class="col-lg-3">
                            <select class="form-control" id="choose_status">
                                <option>Chọn trạng thái---</option>
                                <option value="new">Chờ xử lý</option>
                                <option value="take">Chờ lấy hàng</option>
                                <option value="send">Chờ giao hàng</option>
                                <option value="remove">Chờ chuyển hoàn</option>
                            </select>
                        </div>
                        <div class="col-lg-3" ><button class="btn btn-primary" onclick="viewList()"><i class="fa fa-eye"
                                    aria-hidden="true"></i> Xem danh sách</button></div>
                    </div>
                    <legend style="font-size:15px">Danh sách</legend>
                    <div class="row">
                        <div class="col-lg-3"><button class="btn btn-primary"><i class="fa fa-eye"
                                    aria-hidden="true"></i> Xem danh sách</button></div>
                        <div class="col-md-8">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Thoát</button>
                    <button type="button" class="btn btn-primary" id="save-quick-assign">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
{{-- </form> --}}
<script>
      function loadCustomer(id) {
            $("#customer option[value!='-1']").remove();
            $.ajax({
                type: "GET",
                url: '{{url('/ajax/get_customer_district/')}}/' + id
            }).done(function (msg) {
                var i;
                for (i = 0; i < msg.customer.length; i++) {
                    if (msg.customer[i]['id'] == '{{@old('ward_id_fr')}}') {
                        $('select[name="customer"]').append('<option value="' + msg.customer[i]['id'] + '" selected>' + msg.customer[i]['name'] + '</option>')
                    } else {
                        $('select[name="customer"]').append('<option value="' + msg.customer[i]['id'] + '">' + msg.customer[i]['name'] +' - '+ msg.customer[i]['phone_number'] + '</option>')
                    }
                }
            });
        }
        function viewList(){
            var district_id = $("#district_fr option:selected").val();
            var customer_id = $("#customer option:selected").val();
            var choose_status = $("#choose_status option:selected").val();
            console.log(district_id,customer_id,choose_status);
        }
</script>
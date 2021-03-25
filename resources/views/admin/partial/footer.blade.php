
<!-- Footer -->

<div class="page-footer-inner">
    Copyright &copy; UITShop 2017
</div>
<!-- Modal image booking -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Ảnh đơn hàng <span id="alt_uuid_booking"></span></span></h4>
        </div>
        <div class="modal-body">
        <img src="" id="imagepreview" width="100%" >
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        </div>
    </div>
    </div>
</div>
<!-- Modal detail booking -->
<div class="modal fade" id="detailBooking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Chi tiết đơn hàng <span id="uuid_booking"></span></span></h4>
            </div>
            <div class="modal-body">
                <h3 style="color: blue;"><b>Thông tin khách hàng</b></h3>
                <div class="info-send col-md-6">
                    <h4><b>Người gửi</b></h4>
                    <div class="content"></div>
                </div>
                <div class="info-receive col-md-6">
                    <h4><b>Người nhận</b></h4>
                    <div class="content"></div>
                </div>
                <h3 style="color: blue;"><b>Thông tin cơ bản</b></h3>
                <div class="info-booking col-md-6">
                    <h4><b>Đơn hàng</b></h4>
                    <div class="content"></div>
                </div>
                <div class="info-log col-md-6">
                    <h4><b>Log đơn hàng</b></h4>
                    <div class="content"></div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:none">
            </div>
        </div>
    </div>
</div>
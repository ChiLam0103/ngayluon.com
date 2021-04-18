$(document).ready(function () {
  $("#quick-assign").click(function () {
    $("#type-assign").val("no_assign");
    loadListBoook("no_assign");
  });

  $("#type-assign").change(function () {
    loadListBoook($(this).val());
  });

  $("#save-quick-assign").click(function (e) {
    $.ajax({
      type: "POST",
      url: "../ajax/quick-assign-new",
      data: {
        inputs: $("#form-quick-assign").serializeArray(),
        _token: $("input[name='_token']").val(),
        type_assign: $("#type-assign").val(),
      },
      dataType: "JSON",
    }).done(function (msg) {
      if (msg.status == "success") {
        $("#quickAssignModal").modal("hide");
        location.reload();
      } else {
        $("#msg-error").html(msg.status);
      }
    });
  });

  $("#check-all").change(function () {
    var checkboxes = $(this).closest("form").find(":checkbox");
    if ($(this).prop("checked")) {
      checkboxes.prop("checked", true);
    } else {
      checkboxes.prop("checked", false);
    }
  });

});
lightbox.option({
  resizeDuration: 200,
  wrapAround: true,
  showImageNumberLabel: true,
});
// modal show detail booking
$(document).on("click", ".uuid", function () {
  var id = $(this).attr("name");
  var empty = "";
  $("#detailBooking .modal-body .content p").remove();
  $.ajax({
    type: "GET",
    url: "../ajax/detail_booking/" + id,
  }).done(function (msg) {
    var payer = msg.booking.payment_type == 1 ? "người gửi" : "người nhận";
    var shipper =
      msg.shipper != null
        ? "<p>Shipper lấy đơn: " + msg.shipper.shipper_name + "</p>"
        : "";
    var content_send =
      "<p>Họ tên: " +
      msg.booking.send_name +
      "</p> <p>Số điện thoại: " +
      msg.booking.send_phone +
      "</p> <p>Địa chỉ: " +
      msg.booking.send_full_address +
      "</p>";
    var content_receive =
      "<p>Họ tên: " +
      msg.booking.receive_name +
      "</p> <p>Số điện thoại: " +
      msg.booking.receive_phone +
      "</p> <p>Địa chỉ: " +
      msg.booking.receive_full_address +
      "</p>";
    var content_booking =
      "<p>Tên đơn hàng: " +
      msg.booking.name +
      " -- Ngày tạo: " +
      msg.booking.created_at +
      "</p> <p>Tiền thu hộ: " +
      formatNumber(msg.booking.COD, ".", ",") +
      "</p> </p> <p>Giá đơn hàng: " +
      formatNumber(msg.booking.price, ".", ",") +
      "</p>  </p> <p>Chi phí phát sinh: " +
      formatNumber(msg.booking.incurred, ".", ",") +
      "</p> <p>Số tiền thanh toán: " +
      formatNumber(msg.booking.paid, ".", ",") +
      "</p> <p>Khối lượng (gram): " +
      formatNumber(msg.booking.weight, ".", ",") +
      "</p>  <p>Ghi chú khách hàng: " +
      msg.booking.other_note +
      "</p> <p>Ghi chú hệ thống: " +
      msg.booking.note +
      "</p> <p>Trả cước: " +
      payer +
      "</p><p>Trạng thái: " +
      msg.booking.status +
      "</p> " +
      shipper +
      "";
    var content_log = "";
    $(msg.log).each(function (index, value) {
      if (value.type_detail == "book_detail") {
        content_log += "<p>" + value.title + " - " + value.created_at + "</p> ";
      }
    });
    $("#uuid_booking").text(msg.booking.uuid);
    $("#detailBooking .modal-body .info-send .content").append(content_send);
    $("#detailBooking .modal-body .info-receive .content").append(
      content_receive
    );
    $("#detailBooking .modal-body .info-booking .content").append(
      content_booking
    );
    $("#detailBooking .modal-body .info-log .content").append(content_log);
  });
  $("#detailBooking").modal("show");
});
setTimeout(function () {
  $('[data-toggle="popover"]').popover();
}, 1000);

//actions table booking
var table = $("#example").DataTable({
  order: [[0, "desc"]],
  ajax: {
    url: "../ajax/get_booking_status?status=all",
    type: "GET",
  },
  serverSide: true,
  processing: true,
  columns: [
    { data: "image_order" ,title: "Ảnh đơn hàng"},
    { data: "uuid",title:"ID" },
    { data: "send_name",title:"Người gửi" },
    { data: "receive_name",title:"Người nhận" },
    { data: "price",title:"Giá" },
    { data: "status",title:"Trạng thái" },
    { data: "created_at",title:"Ngày tạo" },
  ],
});
$("#status_booking input[name=booking_status]").change(function () {
  table = $("#example").DataTable();
  table.destroy();
  $("#example").empty();

  $("#example").DataTable({
    order: [[0, "desc"]],
    ajax: {
      url: "../ajax/get_booking_status?status=" + this.value,
      type: "GET",
    },
    serverSide: true,
    processing: true,
    columns: [
      { data: "image_order", title: "Ảnh đơn hàng" },
      { data: "uuid", title: "ID" },
      { data: "send_name", title: "Người gửi" },
      { data: "receive_name", title: "Người nhận" },
      { data: "price", title: "Giá" },
      { data: "created_at", title: "Ngày tạo" },
    ],
  });
});

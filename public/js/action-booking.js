$(document).ready(function () {
  lightbox.option({
    resizeDuration: 200,
    wrapAround: true,
    showImageNumberLabel: true,
  });
  setTimeout(function () {
    $('[data-toggle="popover"]').popover();
  }, 1000);
  $("#quick-assign").click(function () {
    $('#quickAssignModal').modal('show');
  });

  // $("#type-assign").change(function () {
  //   loadListBoook($(this).val());
  // });

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
    var checkboxes = $(this).closest("table").find(":checkbox");

    if ($(this).prop("checked")) {
      checkboxes.prop("checked", true);
    } else {
      checkboxes.prop("checked", false);
    }
  });

  $('#choose_status').on('change', function() {
    var customer_id = $("#customer option:selected").val();
    var choose_status = $("#choose_status option:selected").val();
    $.ajax({
        type: "GET",
        url: '../ajax/get_list_booking_assign?' + 'sender_id=' + customer_id + '&status=' + choose_status,

    }).done(function(msg) {
        $('#quickAssignModal #ul-book tbody').html('');
        if (msg.booking.length > 0) {
            $(msg.booking).each(function(index, value) {
                var bookLi = '';
                bookLi += '<tr>';
                bookLi += '<td><input type="checkbox" value="' + value.id + '" name="books"></td>';
                bookLi += '<td>' + value.uuid + '</td>';
                bookLi += '<td>' + value.name + '</td>';
                bookLi += '<td>' + value.send_name + '</td>';
                bookLi += '<td>' + value.send_phone + '</td>';
                bookLi += '<td>' + value.send_full_address + '</td>';
                bookLi += '<td>' + value.shipper_name + '</td>';
                bookLi += '</tr>';
                $('#quickAssignModal #ul-book tbody').append(bookLi);
            });
        }
    });
});

  //action click btn click of modal Booking
  $("#modalBooking #btnSave").on("click", function (event) {
    var id = $("#modalBooking .action").attr('id');
    if (id == "addBooking") {
      actionBooking("store");
    } else {
      actionBooking("update");
    }
  });

  //change status menu left -> show list booking
  $("#status_booking input[name=booking_status]").change(function () {
    table = $("#list_booking").DataTable();
    table.destroy();
    $("#list_booking").empty();

    $("#list_booking").DataTable({
      order: [[0, "desc"]],
      ajax: {
        url: "../ajax/get_booking_status?status=" + this.value,
        type: "GET",
      },
      serverSide: true,
      processing: true,
      columns: [
        { data: "action", title: "Hành động" },
        { data: "image_order", title: "Ảnh đơn hàng" },
        { data: "uuid", title: "ID" },
        { data: "send_name", title: "Người gửi" },
        { data: "receive_name", title: "Người nhận" },
        { data: "price", title: "Giá" },
        { data: "created_at", title: "Ngày tạo" },
      ],
    });
  });
  //modal add new booking
  $(document).on('click', '#btnAddNewBooking', function () {
    $('.modal-title').text('Thêm thông tin đơn hàng');
    $('#modalBooking .action').attr('id', 'addBooking');
    $('#modalBooking').modal('show');
    $("#name_id_fr").prop("disabled", false);
    $("input[name=name_to]").val('');
    $("input[name=phone_number_to]").val('');
    $("input[name=home_number_to]").val('');
    $("input[name=name]").val('');
    $("input[name=cod]").val('');
    $("input[name=price]").val('');
    $("input[name=weight]").val('');
    $("input[name=other_note]").val('');
    $('.imgUser').attr('src', '');
    $(' #name_to_err').text('');
    $('#phone_number_to_err').text('');
    $('#home_number_to_err').text('');
    $('#name_err').text('');
  });
  //modal add edit booking
  $(document).on('click', '.btnEdit', function () {
    var id = $(this).attr("name");
    $('.modal-title').text('Chỉnh sửa thông tin đơn hàng');
    $('#modalBooking .action').attr('id', 'editBooking');
    $('#modalBooking').modal('show');
    $("#name_id_fr").prop("disabled", "disabled");

    $.ajax({
      type: "GET",
      url: "../ajax/detail_booking/" + id,
    }).done(function (data) {
      $("input[name=id]").val(data.booking.id);
      $('#name_id_fr option[value="' + data.booking.sender_id + '"]').prop('selected', true);
      $('#province_fr option[value="' + data.booking.send_province_id + '"]').prop('selected', true);
      $("input[name=phone_number_fr]").val(data.booking.send_phone);
      $('#province_fr option[value="' + data.booking.send_province_id + '"]').prop('selected', true);
      $('#district_fr option[value="' + data.booking.send_district_id + '"]').prop('selected', true);
      $('#ward_fr option[value="' + data.booking.send_ward_id + '"]').prop('selected', true);
      $("input[name=home_number_fr]").val(data.booking.send_homenumber);

      $("input[name=name_to]").val(data.booking.receive_name);
      $("input[name=phone_number_to]").val(data.booking.receive_phone);
      $('#province_to option[value="' + data.booking.receive_province_id + '"]').prop('selected', true);
      $('#district_to option[value="' + data.booking.receive_district_id + '"]').prop('selected', true);
      $('#ward_to option[value="' + data.booking.receive_ward_id + '"]').prop('selected', true);
      $("input[name=home_number_to]").val(data.booking.receive_homenumber);

      $("input[name=name]").val(data.booking.name);
      $('#payment_type option[value="' + data.booking.payment_type + '"]').prop('selected', true);
      $("input[name=cod]").val(data.booking.COD);
      $("input[name=price]").val(data.booking.price);
      $("input[name=weight]").val(data.booking.weight);
      $("input[name=other_note]").val(data.booking.other_note);
      $('.imgUser').attr('src', '../public/' + data.booking.image_order);
      $(' #name_to_err').text('');
      $('#phone_number_to_err').text('');
      $('#home_number_to_err').text('');
      $('#name_err').text('');
    });
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
      var status_name = '';
      switch (msg.booking.status) {
        case 'new':
          status_name = 'chờ xử lý';
          break;
        case 'taking':
          status_name = "Lấy hàng";
          break;
        case 'return':
          status_name = "Chuyển hoàn";
          break;

      }
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
        status_name +
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
});

//when load page will show list booking
var table = $("#list_booking").DataTable({
  order: [[0, "desc"]],
  ajax: {
    url: "../ajax/get_booking_status?status=all",
    type: "GET",
  },
  serverSide: true,
  processing: true,
  columns: [
    { data: "action", title: "Hành động" },
    { data: "image_order", title: "Ảnh đơn hàng" },
    { data: "uuid", title: "ID" },
    { data: "send_name", title: "Người gửi" },
    { data: "receive_name", title: "Người nhận" },
    { data: "price", title: "Giá" },
    { data: "status", title: "Trạng thái" },
    { data: "created_at", title: "Ngày tạo" },
  ],
});
//action store create & update
function actionBooking(action) {
  var id = $("input[name=id]").val();
  var avatar = $("#exampleInputFile")[0].files[0];
  var name_id_fr = $("#name_id_fr option:selected").val();
  var name_to = $("input[name=name_to]").val();
  var phone_number_to = $("input[name=phone_number_to]").val();
  var province_id_to = $("#province_to option:selected").val();
  var district_id_to = $("#district_to option:selected").val();
  var ward_id_to = $("#ward_fr option:selected").val();
  var home_number_to = $("input[name=home_number_to]").val();
  var name = $("input[name=name]").val();
  var payment_type = $("#payment_type option:selected").val();
  var cod = $("input[name=cod]").val();
  var price = $("input[name=price]").val();
  var weight = $("input[name=weight]").val();
  var other_note = $("input[name=other_note]").val();

  var flag = 0;
  var required = "Trường dữ liêu bắt buộc";
  var phone_err = "Trường dữ liêu không đúng định dạng sdt";
  (name_to == '') ? ($(' #name_to_err').text(required)) && (flag = 1) : ($(' #name_to_err').text(''));
  (phone_number_to == '') ? ($('#phone_number_to_err').text(required)) && (flag = 1) : ($('#phone_number_to_err').text(''));
  (home_number_to == '') ? ($('#home_number_to_err').text(required)) && (flag = 1) : ($('#home_number_to_err').text(''));
  (name == '') ? ($('#name_err').text(required)) && (flag = 1) : ($('#name_err').text(''));
  (checkPhoneNumber(phone_number_to) == false) ? ($(' #phone_number_to_err').text(phone_err)) && (flag = 1) : ($('#phone_number_to_err').text(''));

  if (flag == 1) {
    return false;
  } else {
    $("#modalBooking #btnSave").prop('disabled', true);
    var formData = new FormData()
    formData.append('_token', $(' [name=_token]').val());
    formData.append('id', id);
    formData.append('name_id_fr', name_id_fr);
    formData.append('name_to', name_to);
    formData.append('phone_number_to', phone_number_to);
    formData.append('province_id_to', province_id_to);
    formData.append('district_id_to', district_id_to);
    formData.append('ward_id_to', ward_id_to);
    formData.append('home_number_to', home_number_to);
    formData.append('name', name);
    formData.append('payment_type', payment_type);
    formData.append('cod', cod);
    formData.append('price', price);
    formData.append('weight', weight);
    formData.append('other_note', other_note);
    formData.append('avatar', avatar);
    formData.append('action', action);

    $.ajax({
      type: "post",
      url: "../ajax/action_booking",
      processData: false,
      contentType: false,
      data: formData
    }).done(function (res) {
      $("#modalBooking #btnSave").prop('disabled', true);
      location.reload();
    });
  }
}



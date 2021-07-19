$(document).ready(function() {
    countListStatus();
    lightbox.option({
        resizeDuration: 200,
        wrapAround: true,
        showImageNumberLabel: true,
    });
    setTimeout(function() {
        $('[data-toggle="popover"]').popover();
    }, 1000);
    $("#quick-assign").click(function() {
        $('#quickAssignModal').modal('show');
    });;
    $("#shipper-change-status").click(function() {
        $('#shipperChangeStatusModal').modal('show');
    });;

    $("#save-quick-assign").click(function(e) {
        $.ajax({
            type: "POST",
            url: "../ajax/quick-assign-new",
            data: {
                inputs: $("#form-quick-assign").serializeArray(),
                _token: $("input[name='_token']").val(),
                type_assign: $("#assign").val(),
                choose_status: $("#choose_status").val(),
            },
            dataType: "JSON",
        }).done(function(msg) {
            if (msg.status == "success") {
                $("#quickAssignModal").modal("hide");
                location.reload();
            } else {
                $("#msg-error").html(msg.status);
            }
        });
    });

    $("#check-all").change(function() {
        var checkboxes = $(this).closest("table").find(":checkbox");

        if ($(this).prop("checked")) {
            checkboxes.prop("checked", true);
        } else {
            checkboxes.prop("checked", false);
        }
    });

    $("#quickAssignModal #btnViewAssign").on("click", function(event) {
        var customer_id = $("#quickAssignModal #customer option:selected").val();
        var choose_status = $("#quickAssignModal #choose_status option:selected").val();
        var date_from = $("#quickAssignModal #date_from").val();
        var date_to = $("#quickAssignModal #date_to").val();
        $('#quickAssignModal #assign').empty();
        var option = "";
        switch (choose_status) {
            case 'new':
                option = " <option value='receive'>Đi lấy hàng</option>";
                break;
            case 'taking':
                option = "  <option value='send'>Đi giao hàng</option>";
                break;
            case 'return':
                option = " <option value='return'>Đi trả lại</option>";
                break;
            case 'move':
                option = "  <option value='move'>Đi giao lại</option>";
                break;
        }
        $('#quickAssignModal #assign').append(option);
        $.ajax({
            type: "GET",
            url: '../ajax/get_list_booking_assign?' + 'sender_id=' + customer_id + '&status=' + choose_status + '&date_from=' + date_from + '&date_to=' + date_to,
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
    $("#modalBooking #btnSave").on("click", function(event) {
        var id = $("#modalBooking .action").attr('id');
        if (id == "addBooking") {
            actionBooking("store");
        } else {
            actionBooking("update");
        }
    });

    //change status menu left -> show list booking
    $("#status_booking input[name=booking_status]").change(function() {
        table = $("#list_booking").DataTable();
        table.destroy();
        $("#list_booking").empty();

        $("#list_booking").DataTable({
            order: [
                [0, "desc"]
            ],
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
                { data: "send_district_name", title: "Đ/C Gửi" },
                { data: "receive_name", title: "Người nhận" },
                { data: "receive_district_name", title: "Đ/C nhận" },
                { data: "price", title: "Giá" },
                { data: "is_prioritize", title: "Ưu tiên" },
                { data: "status", title: "Trạng thái" },
                { data: "created_at", title: "Ngày tạo" },
            ],
        });
    });
    //modal add new booking
    $(document).on('click', '#btnAddNewBooking', function() {
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
        $('input[name=is_prioritize]').prop('checked', false)
    });
    //modal add edit booking
    $(document).on('click', '.btnEdit', function() {
        var id = $(this).attr("name");
        $('.modal-title').text('Chỉnh sửa thông tin đơn hàng');
        $('#modalBooking .action').attr('id', 'editBooking');
        $("#name_id_fr").prop("disabled", "disabled");
        $("#editBooking .modal-body .input").val('');
        $.ajax({
            type: "GET",
            url: "../ajax/detail_booking/" + id,
        }).done(function(data) {
            $("input[name=id]").val(data.booking.id);
            $('#name_id_fr option[value="' + data.booking.sender_id + '"]').prop('selected', true);
            $("input[name=phone_number_fr]").val(data.booking.send_phone);
            $('#district_fr option[value="' + data.booking.send_district_id + '"]').prop('selected', true);
            $("input[name=home_number_fr]").val(data.booking.send_homenumber);

            $("input[name=name_to]").val(data.booking.receive_name);
            $("input[name=phone_number_to]").val(data.booking.receive_phone);
            $('#district_to option[value="' + data.booking.receive_district_id + '"]').prop('selected', true);
            $("input[name=home_number_to]").val(data.booking.receive_homenumber);

            $("input[name=name]").val(data.booking.name);
            $("input[name=receivable_price]").val(data.booking.receivable_price);
            $("input[name=product_price]").val(data.booking.product_price);
            $("input[name=ship_price]").val(data.booking.ship_price);
            $("input[name=note]").val(data.booking.note);
            $('.imgUser').attr('src', '../public/' + data.booking.image_order);
            (data.booking.is_prioritize == 1) ? $('input[name=is_prioritize]').prop('checked', true): $('input[name=is_prioritize]').prop('checked', false);

            $('#name_to_err').text('');
            $('#phone_number_to_err').text('');
            $('#home_number_to_err').text('');
            $('#name_err').text('');
        });
        $('#modalBooking').modal('show');
    });
    // modal show detail booking
    $(document).on("click", ".uuid", function() {
        var id = $(this).attr("name");
        var empty = "";
        $("#detailBooking .modal-body .content p").remove();
        $.ajax({
            type: "GET",
            url: "../ajax/detail_booking/" + id,
        }).done(function(msg) {
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
            var shipper =
                msg.shipper != null ?
                "<p>Shipper lấy đơn: " + msg.shipper.shipper_name + "</p>" :
                "";
            var content_send =
                "<p><b>Họ tên:</b> " +
                msg.booking.send_name +
                "</p> <p><b>Số điện thoại:</b> " +
                msg.booking.send_phone +
                "</p> <p><b>Địa chỉ:</b> " +
                msg.booking.send_homenumber + ", " + msg.booking.send_district_name +
                "</p>";
            var content_receive =
                "<p><b>Họ tên:</b> " +
                msg.booking.receive_name +
                "</p> <p><b>Số điện thoại:</b> " +
                msg.booking.receive_phone +
                "</p> <p><b>Địa chỉ:</b> " +
                msg.booking.receive_homenumber + ", " + msg.booking.receive_district_name +
                "</p>";
            var content_booking =
                "<p><b>Tên đơn hàng:</b> " +
                msg.booking.name +
                " </p>  <p><b>Ngày tạo:</b> " +
                msg.booking.created_at +
                "</p> <p><b>Tiền thu:</b> " +
                formatNumber(msg.booking.receivable_price, ".", ",") +
                "</p> <p><b>Tiền ship:</b> " +
                formatNumber(msg.booking.ship_price, ".", ",") +
                "</p> </p> <b>Tiền hàng:</b> " +
                formatNumber(msg.booking.product_price, ".", ",") +
                "</p> <p><b>Số tiền thanh toán:</b> " +
                formatNumber(msg.booking.paid, ".", ",") +
                "</p>  <p><b>Ghi chú:</b> " +
                msg.booking.note +
                "</p><p><b>Trạng thái:</b> " +
                status_name +
                "</p> " +
                shipper +
                "";
            var content_log = "";
            $(msg.log).each(function(index, value) {
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
    order: [
        [0, "desc"]
    ],
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
        { data: "send_district_name", title: "Đ/C Gửi" },
        { data: "receive_name", title: "Người nhận" },
        { data: "receive_district_name", title: "Đ/C nhận" },
        { data: "price", title: "Giá" },
        { data: "is_prioritize", title: "Ưu tiên" },
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
    var district_id_to = $("#district_to option:selected").val();
    var home_number_to = $("input[name=home_number_to]").val();
    var name = $("input[name=name]").val();
    var receivable_price = $("input[name=receivable_price]").val();
    var product_price = $("input[name=product_price]").val();
    var ship_price = $("input[name=ship_price]").val();
    var note = $("input[name=note]").val();
    var is_prioritize = $('input[name=is_prioritize]:checked').val();

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
        formData.append('district_id_to', district_id_to);
        formData.append('home_number_to', home_number_to);
        formData.append('name', name);
        formData.append('receivable_price', receivable_price);
        formData.append('product_price', product_price);
        formData.append('ship_price', ship_price);
        formData.append('note', note);
        formData.append('avatar', avatar);
        formData.append('is_prioritize', is_prioritize);
        formData.append('action', action);

        $.ajax({
            type: "post",
            url: "../ajax/action_booking",
            processData: false,
            contentType: false,
            data: formData
        }).done(function(res) {
            $("#modalBooking #btnSave").prop('disabled', true);
            location.reload();
            console.log(res);
        });
    }
}
//count list
function countListStatus() {
    $.ajax({
        type: "get",
        url: "../ajax/count_booking",
        dataType: "JSON",
    }).done(function(msg) {
        $('#status_booking #status_all').html(msg.all);
        $('#status_booking #status_new').html(msg.new);
        $('#status_booking #status_warehouse').html(msg.warehouse);
        $('#status_booking #status_taking').html(msg.taking);
        $('#status_booking #status_sending').html(msg.sending);
        $('#status_booking #status_completed').html(msg.completed);
        $('#status_booking #status_return').html(msg.return);
        $('#status_booking #status_move').html(msg.move);
        $('#status_booking #status_cancel').html(msg.cancel);
    });
}

//add & edit user
function actionUser(role, action) {
    var id = $("input[name=id]").val();
    var name = $("input[name=name]").val();
    var email = $("input[name=email]").val();
    var phone_number = $("input[name=phone_number]").val();
    var password = $("input[name=password]").val();
    var cf_password = $("#cf_password").val();
    var birth_day = $("input[name=birth_day]").val();
    var province_id = $("#province option:selected").val();
    var district_id = $("#district option:selected").val();
    var ward_id = $("#ward option:selected").val();
    var id_number = $("input[name=id_number]").val();
    var home_number = $("input[name=home_number]").val();
    var bank_account = $("input[name=bank_account]").val();
    var bank_account_number = $("input[name=bank_account_number]").val();
    var bank_name = $("input[name=bank_name]").val();
    var bank_branch = $("input[name=bank_branch]").val();
    var avatar = $("#exampleInputFile")[0].files[0];
    var is_advance_money = $('input[name=is_advance_money]:checked').val();

    var required = "Trường dữ liêu bắt buộc";
    var email_err = "Trường dữ liêu không đúng định dạng email";
    var phone_err = "Trường dữ liêu không đúng định dạng sdt";
    var unique = "Dữ liệu đã tồn tại";
    
    var cf_password_err = "Mật khẩu không trùng khớp"; var flag = 0; (name == '') ? ($(' #name_err').text(required)) && (flag = 1) : ($(' #name_err').text(''));
    (email == '') ? ($(' #email_err').text(required)) && (flag = 1) : ($(' #email_err').text(''));
    (phone_number == '') ? ($(' #phone_number_err').text(required)) && (flag = 1) : ($(' #phone_number_err').text(''));

    (birth_day == '') ? ($(' #birth_day_err').text(required)) && (flag = 1) : ($(' #birth_day_err').text(''));
    (id_number == '') ? ($(' #id_number_err').text(required)) && (flag = 1) : ($(' #id_number_err').text(''));
    (home_number == '') ? ($(' #home_number_err').text(required)) && (flag = 1) : ($(' #home_number_err').text(''));
    (password != cf_password) ? ($(' #cf_password_err').text(cf_password_err)) && (flag = 1) : ($(' #cf_password_err').text(''));
    (isEmail(email) == false) ? ($(' #email_err').text(email_err)) && (flag = 1) : ($(' #email_err').text(''));
    (checkPhoneNumber(phone_number) == false) ? ($(' #phone_number_err').text(phone_err)) && (flag = 1) : ($(' #phone_number_err').text(''));

    if (id == "#modalUser #addUser") {
        (password == '') ? ($(' #password_err').text(required)) && (flag = 1) : ($(' #password_err').text(''));
        (cf_password == '') ? ($(' #cf_password_err').text(required)) && (flag = 1) : ($(' #cf_password_err').text(''));
    }
    if (action == "store") {

        $.ajax({
            type: "GET",
            url: "../ajax/check_exist_user",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: {
                email: email,
                phone_number: phone_number,
                _token: $('[name=_token]').val()
            }
        }).done(function (data) {
            var dataItems = "";
            $.each(data, function (index, itemData) {
                switch (itemData) {
                    case "email_err":
                        $(' #email_err').text(unique);
                        flag = 1;
                        break;
                    case "phone_number_err":
                        $(' #phone_number_err').text(unique);
                        flag = 1;
                        break;
                }
            });
        });
    }

    if (flag == 1) {
        return false;
    } else {
        var formData = new FormData()
        formData.append('_token', $(' [name=_token]').val());
        formData.append('id', id);
        formData.append('name', name);
        formData.append('email', email);
        formData.append('phone_number', phone_number);
        formData.append('password', password);
        formData.append('birth_day', birth_day);
        formData.append('province_id', province_id);
        formData.append('district_id', district_id);
        formData.append('ward_id', ward_id);
        formData.append('id_number', id_number);
        formData.append('home_number', home_number);
        formData.append('bank_account', bank_account);
        formData.append('bank_account_number', bank_account_number);
        formData.append('bank_name', bank_name);
        formData.append('bank_branch', bank_branch);
        formData.append('avatar', avatar);
        formData.append('is_advance_money', is_advance_money);
        formData.append('role', role);
        formData.append('action', action);

        $.ajax({
            type: "post",
            url: "../ajax/action_user",
            processData: false,
            contentType: false,
            data: formData
        }).done(function (res) {
            location.reload();
        });
    }
}
// modal show  add new user  
$(document).on('click', '#btnAddNew', function () {
    $('.modal-title').text('Thêm thông tin người dùng');
    $('#modalUser .action').attr('id', 'addUser');
    $('#modalUser').modal('show');
    $("input[name=id]").val('');
    $("input[name=uuid]").val('');
    $("input[name=name]").val('');
    $("input[name=email]").val('');
    $("input[name=phone_number]").val('');
    $("input[name=birth_day]").val('');
    $("input[name=id_number]").val('');
    $("input[name=home_number]").val('');
    $("input[name=bank_account]").val('');
    $("input[name=bank_account_number]").val('');
    $("input[name=bank_name]").val('');
    $("input[name=bank_branch]").val('');
    $('.imgUser').attr('src', '');
    $("input[name=email]").removeAttr("disabled")
    $("input[name=phone_number]").removeAttr("disabled")
    $('#uuid_err').text('');
    $('#name_err').text('');
    $('#email_err').text('');
    $('#phone_number_err').text('');
    $('#birth_day_err').text('');
    $('#id_number_err').text('');
    $('#home_number_err').text('');
    $('#cf_password_err').text('');
    $('#email_err').text('');
    $('#password_err').text('');
    $('#cf_password_err').text('');
    $('input[name=is_advance_money]').prop('checked', false)
});
//   show edit booking
$(document).on('click', '.edit_user', function () {
    var id = $(this).attr("name");
    $('.modal-title').text('Chỉnh sửa thông tin người dùng');
    $('#modalUser .action').attr('id', 'editUser');
    $('#modalUser').modal('show');
    $.ajax({
        type: "GET",
        url: "../ajax/detail_user/" + id,
    }).done(function (data) {
        $("input[name=id]").val(data.user.id);
        $("input[name=uuid]").val(data.user.uuid);
        $("input[name=name]").val(data.user.name);
        $("input[name=email]").val(data.user.email);
        $("input[name=phone_number]").val(data.user.phone_number);
        $("input[name=birth_day]").val(data.user.birth_day);
        $('#province option[value="' + data.user.province_id + '"]').prop('selected', true);
        $('#district option[value="' + data.user.district_id + '"]').prop('selected', true);
        $('#ward option[value="' + data.user.ward_id + '"]').prop('selected', true);
        $("input[name=id_number]").val(data.user.id_number);
        $("input[name=home_number]").val(data.user.home_number);
        $("input[name=bank_account]").val(data.user.bank_account);
        $("input[name=bank_account_number]").val(data.user.bank_account_number);
        $("input[name=bank_name]").val(data.user.bank_name);
        $("input[name=bank_branch]").val(data.user.bank_branch);
        $('.imgUser').attr('src', '../' + data.user.avatar);
        $('#uuid_err').text('');
        $('#name_err').text('');
        $('#email_err').text('');
        $('#phone_number_err').text('');
        $('#birth_day_err').text('');
        $('#id_number_err').text('');
        $('#home_number_err').text('');
        $('#cf_password_err').text('');
        $('#email_err').text('');
        $('#password_err').text('');
        $('#cf_password_err').text('');
        $("input[name=email]").attr("disabled", "disabled");
        $("input[name=phone_number]").attr("disabled", "disabled");
        (data.user.is_advance_money == 1) ? $('input[name=is_advance_money]').prop('checked', true) : $('input[name=is_advance_money]').prop('checked', false);
    });
});


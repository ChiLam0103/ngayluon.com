$(document).ready(function () {
    $('#clickmewow').click(function () {
        $('#radio1003').attr('checked', 'checked');
    });

    // lấy thông báo
    connectNotification()
    // pushNotification();
    //format input date, formatCurrency,formatNumber    
    $("#mask_date").inputmask("y/m/d", {
        autoUnmask: true
    });
    //direct mask
    $('#blah').hide();
    loadDistrict();
    if ($('#oldInputFile').val()) {
        $('#blah').attr('src', '../' + $('#oldInputFile').val());
        $('#blah').show();
    }
    //show image
    $("#exampleInputFile").change(function () {
        readURL(this);
        $('#blah').show();
    });
})

//notification
function connectNotification() {
    $.ajax({
        url: "../ajax/notifications",
        method: "get"
    }).done(function (res) {
        if (res.notifications.length > 0) {
            $('#count-notification').html(res.countNotReaded);
            showNotification(res.notifications);
        }
    });
}
function showNotification(res) {
    var str = '';
    $(res).each(function (index, value) {
        str += templeteNotification(value);
    });
    $('#list-notification').html(str);
}
function templeteNotification(value) {
    var str = '';
    var title = '<b>' + value.title + '</b>';
    var href = '../admin/booking/new';
    if (value.is_readed == 1) {
        title = value.title;
    }
    if (title.indexOf('vừa được hủy') != -1) {
        href = '../admin/booking/cancel';
    }
    str += '<li>';
    str += '<a href="' + href + '?notification_id=' + value.notification_id + '&is_readed=1">';
    // str += '<span class="time">3 mins</span>';
    str += '<span class="details">';
    str += '<span class="label label-sm label-icon label-danger">';
    str += '<i class="fa fa-bolt"></i>';
    str += '</span>';
    str += title;
    str += '</span>';
    str += '</a>';
    str += '</li>';
    return str;
}


//format currency & number
function formatCurrency(number) {
    var n = number.split('').reverse().join("");
    var n2 = n.replace(/\d\d\d(?!$)/g, "$&,");
    return n2.split('').reverse().join('') + 'VNĐ';
}
function formatNumber(nStr, decSeperate, groupSeperate) {
    nStr += '';
    x = nStr.split(decSeperate);
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
    }
    return x1 + x2;
}

//function check formart email, phone
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
function checkPhoneNumber(phone) {
    var flag = false;
    phone = phone.replace('(+84)', '0');
    phone = phone.replace('+84', '0');
    phone = phone.replace('0084', '0');
    phone = phone.replace(/ /g, '');
    if (phone != '') {
        var firstNumber = phone.substring(0, 2);
        if ((firstNumber == '09' || firstNumber == '08' || firstNumber == '07' || firstNumber == '05' || firstNumber == '03') && phone.length == 10) {
            if (phone.match(/^\d{10}/)) {
                flag = true;
            }
        } else if (firstNumber == '02' && phone.length == 11) {
            if (phone.match(/^\d{11}/)) {
                flag = true;
            }
        }
    }
    return flag;
}

//show image
function readURL(input) {
    $('#blah').hide();
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// modal show img 
$(document).on('click', '.img_modal', function () {
    $('#imagepreview').attr('src', $(this).find('img').attr('src'));
    $('.modal-title').text('Ảnh chi tiết');
    $('#imageModal').modal('show');
});

$('input.number').keyup(function(event) {
 // skip for arrow keys
 if(event.which >= 37 && event.which <= 40) return;

 // format number
 $(this).val(function(index, value) {
   return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
 });
  });
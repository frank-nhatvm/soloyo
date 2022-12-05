/**
 * Created by frank on 8/27/18.
 */

var diachigiaohang_all;
var list_quanhuyen_current;
var isRemovingCoupon = false;
function showInputCouponArea() {
    console.log("showInpuCouponArea");
    if ($j('.inputCode').css('display') == 'none') {
        $j('.inputCode').css('display', 'block');
    }
}

function applyCouponCode() {
    console.log("applyCouponCode");
    $j('#btnApplyCouponCode').attr('disabled', true);
    var couponData = {};
    var code = $j('#CouponCode').val();
    couponData['code'] = code;
    var data = {};
    data['coupon'] = couponData;
    data['process_coupon'] = true;


    $j.ajax(
        {
            url: update_couponcode_url,
            data: data,
            method: 'POST',
            success: function (result) {
                var response = result.evalJSON(true);

                if (response.error) {
                    alert(response.message.stripTags().toString());
                    return false;
                }

                if (response.message_coupon) {
                    $j('#CouponCode-error').css('display', 'block');
                    $j('#btnApplyCouponCode').attr('disabled', false);
                }

                else if (response.update_section) {

                    var content_shipping_method = response.update_section['shipping-method'];
                    if (typeof  content_shipping_method != 'undefined') {
                        $j('#checkout-shipping-method-load').html(content_shipping_method);
                        $j('.btn-checkout').attr('disabled', false);
                    }

                    var totals_html = response.update_section['totals'];
                    if (typeof totals_html != 'undefined') {
                        $j('#container-totals').html(totals_html);
                    }
                }
            }

        }
    );
}

function removeCouponCode() {
    if (isRemovingCoupon) {
        return;
    }
    isRemovingCoupon = true;
    console.log("removeCouponCode");

    var couponData = {};

    couponData['remove'] = '1';
    var data = {};
    data['coupon'] = couponData;
    data['process_coupon'] = true;


    $j.ajax(
        {
            url: update_couponcode_url,
            data: data,
            method: 'POST',
            success: function (result) {
                var response = result.evalJSON(true);
                isRemovingCoupon = false;
                if (response.error) {
                    alert(response.message.stripTags().toString());
                    return false;
                }

                if (response.update_section) {

                    var content_shipping_method = response.update_section['shipping-method'];
                    if (typeof  content_shipping_method != 'undefined') {
                        $j('#checkout-shipping-method-load').html(content_shipping_method);
                        $j('.btn-checkout').attr('disabled', false);
                    }

                    var totals_html = response.update_section['totals'];
                    if (typeof totals_html != 'undefined') {
                        $j('#container-totals').html(totals_html);
                    }
                }
            }

        }
    );
}

jQuery(document).ready(function () {
    get_diachigiaohang();
    if (get_billing_address()) {
        console.log(' billing address ok');
        update_checkout();
        $j('.btn-checkout').attr('disabled', false);
    }

    $j('#billing_telephone').on('change', function () {
        update_checkout();
    })

    $j('#billing_email').on('change', function () {
        update_checkout();
    })

    $j('#billing_firstname').on('change', function () {
        update_checkout();
    })

    $j('#billing_street1').on('change', function () {
        update_checkout();
    })


});


function get_diachigiaohang() {
    $j.ajax(
        {
            url: diachigiaohang_url,
            method: 'GET',
            success: function (result) {
                diachigiaohang_all = result;
                if ((typeof  diachigiaohang_all != 'undefined') && diachigiaohang_all.length > 0) {
                    draw_tinhthanh();
                } else {
                    maintain_message();
                }
            },
            error: function (result) {
                maintain_message();
            }
        }
    );
}

function maintain_message() {
}

function draw_tinhthanh() {
    $j('#billing-state-value').html('');
    if ($j('#billing-state-value').css('disaplay') == 'none') {
        $j('#billing-state-value'.css('display', 'block'));
    }

    var current_tinhthanh_id = $j('#billing_region_id').val();

    var html_tinhthanh = '<ul>';

    for (var i = 0; i < diachigiaohang_all.length; i++) {
        var diachigiaohang_i = diachigiaohang_all[i];
        var id_diachigiaohang_id = diachigiaohang_i['id'];
        var name_diachigiaohang_id = diachigiaohang_i['name'];
        html_tinhthanh = html_tinhthanh + '<li onclick="select_option_billing_state(this.id)" id="optionstate-' + id_diachigiaohang_id + '"';


        if ((typeof  current_tinhthanh_id != 'undefined' ) && current_tinhthanh_id != '' && current_tinhthanh_id == id_diachigiaohang_id) {
            html_tinhthanh = html_tinhthanh + 'class="option-selected"';
            $j('#billing-state span').html(name_diachigiaohang_id);
            list_quanhuyen_current = diachigiaohang_i['list_quanhuyen'];
            draw_default_quanhuyen();
        }

        html_tinhthanh = html_tinhthanh + '>' + name_diachigiaohang_id + '</li>';

    }

    html_tinhthanh = html_tinhthanh + '</ul>';

    $j('#billing-state-value').html(html_tinhthanh);

}

function draw_default_quanhuyen() {
    var html_quanhuyen = '<ul>';

    var current_quanhuyen_id = $j('#billing_city').val();

    for (var i = 0; i < list_quanhuyen_current.length; i++) {
        var quanhuyen_i = list_quanhuyen_current[i];
        var id_quanhuyen_i = quanhuyen_i['id'];
        var name_quanhuyen_i = quanhuyen_i['name'];
        html_quanhuyen = html_quanhuyen + '<li onclick="select_option_billing_city(this.id)" id="optioncity-' + id_quanhuyen_i + '"';

        if ((typeof  current_quanhuyen_id != 'undefined' ) && current_quanhuyen_id != '' && current_quanhuyen_id == id_quanhuyen_i) {
            html_quanhuyen = html_quanhuyen + 'class="option-selected"';
            $j('#billing-city span').html(name_quanhuyen_i);
            var list_xaphuong = quanhuyen_i['list_xaphuong'];
            draw_default_xaphuong(list_xaphuong);
        }

        html_quanhuyen = html_quanhuyen + '>' + name_quanhuyen_i + '</li>';
    }

    html_quanhuyen = html_quanhuyen + '</ul>';
    $j('#billing-city-value').html(html_quanhuyen);
}

function draw_default_xaphuong(list_xaphuong) {
    var html_xaphuong = '<ul>';

    var current_xaphuong_id = $j('#billing_xaphuong').val();

    for (var i = 0; i < list_xaphuong.length; i++) {
        var xaphuong_i = list_xaphuong[i];
        var id_xaphuong_i = xaphuong_i['id'];
        var name_xaphuong_i = xaphuong_i['name'];
        html_xaphuong = html_xaphuong + '<li onclick="select_option_billing_xaphuong(this.id)" id="optionxaphuong-' + id_xaphuong_i + '"';
        if ((typeof  current_xaphuong_id != 'undefined' ) && current_xaphuong_id != '' && current_xaphuong_id == id_xaphuong_i) {
            html_xaphuong = html_xaphuong + 'class="option-selected"';
            $j('#billing-xaphuong span').html(name_xaphuong_i);
        }
        html_xaphuong = html_xaphuong + '>' + name_xaphuong_i + '</li>';
    }

    html_xaphuong = html_xaphuong + '</ul>';

    $j('#billing-xaphuong-value').html(html_xaphuong);
}

function draw_quanhuyen() {

    $j('#billing-city-value').html('');
    if ($j('#billing-city-value').css('display') == 'none') {
        $j('#billing-city-value').css('display', 'block');
        $j('#billing-city i').css('color', '#0bb5a2');
    }


    var html_quanhuyen = '<ul>';

    for (var i = 0; i < list_quanhuyen_current.length; i++) {
        var quanhuyen_i = list_quanhuyen_current[i];
        var id_quanhuyen_i = quanhuyen_i['id'];
        var name_quanhuyen_i = quanhuyen_i['name'];
        html_quanhuyen = html_quanhuyen + '<li onclick="select_option_billing_city(this.id)" id="optioncity-' + id_quanhuyen_i + '">' + name_quanhuyen_i + '</li>';
    }

    html_quanhuyen = html_quanhuyen + '</ul>';
    $j('#billing-city-value').html(html_quanhuyen);
}

function draw_xaphuong(list_xaphuong) {

    $j('#billing-xaphuong-value').html('');
    if ($j('#billing-xaphuong-value').css('display') == 'none') {
        $j('#billing-xaphuong-value').css('display', 'block');
        $j('#billing-xaphuong i').css('color', '#0bb5a2');
    }

    var html_xaphuong = '<ul>';

    for (var i = 0; i < list_xaphuong.length; i++) {
        var xaphuong_i = list_xaphuong[i];
        var id_xaphuong_i = xaphuong_i['id'];
        var name_xaphuong_i = xaphuong_i['name'];
        html_xaphuong = html_xaphuong + '<li onclick="select_option_billing_xaphuong(this.id)" id="optionxaphuong-' + id_xaphuong_i + '">' + name_xaphuong_i + '</li>';
    }

    html_xaphuong = html_xaphuong + '</ul>';

    $j('#billing-xaphuong-value').html(html_xaphuong);


}

function select_billing_state() {
    if ($j('#billing-state-value').css('display') == 'none') {
        $j('#billing-state-value').css('display', 'block');
        $j('#billing-state i').css('color', '#0bb5a2');

    } else {
        $j('#billing-state-value').css('display', 'none');
        $j('#billing-state i').css('color', '#000000');
    }
}

function select_option_billing_state(option_id) {
    var array_op = option_id.split('-');
    var state_id = array_op[1];
    $j('#billing-state-value').css('display', 'none');
    var state_name = $j('#' + option_id).text();
    $j('#billing-state span').html(state_name);
    $j('#billing-state i').css('color', '#000000');
    $j('#billing_region_id').val(state_id);
    $j('#billing_region').val(state_name);

    for (var i = 0; i < diachigiaohang_all.length; i++) {
        var tinhthanh = diachigiaohang_all[i];
        if (tinhthanh['id'] == state_id) {
            list_quanhuyen_current = tinhthanh['list_quanhuyen'];
            draw_quanhuyen();
            break;
        }
    }

    $j('#billing-state-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#' + id_element).removeClass('option-selected');
    });

    $j('#' + option_id).addClass('option-selected');

}

function select_billing_city() {
    if ($j('#billing-city-value').css('display') == 'none') {
        $j('#billing-city-value').css('display', 'block');
        $j('#billing-city i').css('color', '#0bb5a2');

    } else {
        $j('#billing-city-value').css('display', 'none');
        $j('#billing-city i').css('color', '#000000');
    }

}

function select_option_billing_city(option_id) {
    var array_op = option_id.split('-');
    var city_id = array_op[1];
    $j('#billing-city-value').css('display', 'none');
    var city_name = $j('#' + option_id).text();
    $j('#billing-city span').html(city_name);
    $j('#billing_city i').css('color', '#000000');
    $j('#billing_city').val(city_id);
    $j('#billing_city_label').val(city_name);

    for (var i = 0; i < list_quanhuyen_current.length; i++) {
        var quanhuyen = list_quanhuyen_current[i];
        if (quanhuyen['id'] == city_id) {
            var list_xaphuong = quanhuyen['list_xaphuong'];
            draw_xaphuong(list_xaphuong);
            break;
        }
    }

    $j('#billing-city-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#' + id_element).removeClass('option-selected');
    });

    $j('#' + option_id).addClass('option-selected');


}

function select_billing_xaphuong() {
    if ($j('#billing-xaphuong-value').css('display') == 'none') {
        $j('#billing-xaphuong-value').css('display', 'block');
        $j('#billing-xaphuong i').css('color', '#0bb5a2');

    } else {
        $j('#billing-xaphuong-value').css('display', 'none');
        $j('#billing-xaphuong i').css('color', '#000000');
    }

}

function select_option_billing_xaphuong(option_id) {
    var array_op = option_id.split('-');
    var xaphuong_id = array_op[1];
    $j('#billing-xaphuong-value').css('display', 'none');
    var xaphuong_name = $j('#' + option_id).text();
    $j('#billing-xaphuong span').html(xaphuong_name);
    $j('#billing_xaphuong i').css('color', '#000000');
    $j('#billing_xaphuong').val(xaphuong_id);
    $j('#billing_xaphuonglabel').val(xaphuong_name);

    $j('#billing-xaphuong-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#' + id_element).removeClass('option-selected');
    });

    $j('#' + option_id).addClass('option-selected');
    update_checkout();
}


function update_checkout() {

    var data_billing_address = get_billing_address();
    if (!data_billing_address) {
        console.log('update checkout false');
        return;
    }

    $j.ajax(
        {
            url: update_checkout_url,
            data: data_billing_address,
            method: 'POST',
            success: function (result) {
                var response = result.evalJSON(true);

                if (response.error) {
                    alert(response.message.stripTags().toString());
                    return false;
                }

                if (response.update_section) {

                    var content_shipping_method = response.update_section['shipping-method'];
                    if (typeof  content_shipping_method != 'undefined') {
                        $j('#checkout-shipping-method-load').html(content_shipping_method);
                        $j('.btn-checkout').attr('disabled', false);
                    }

                    var totals_html = response.update_section['totals'];
                    if (typeof totals_html != 'undefined') {
                        $j('#container-totals').html(totals_html);
                    }
                }
            }

        }
    );
}


function get_billing_address() {

    var data_billing_address = {};

    var billing_firstname = $j('#billing_firstname').val();
    if (!check_validate_data(billing_firstname)) {
        return false;
    }
    data_billing_address['firstname'] = billing_firstname;

    var billing_email = $j('#billing_email').val();
    if (!check_validate_data(billing_email)) {
        return false;
    }
    data_billing_address['email'] = billing_email;

    var billing_telephone = $j('#billing_telephone').val();
    if (!check_validate_data(billing_telephone)) {
        return false;
    }
    data_billing_address['telephone'] = billing_telephone;


    var billing_region_id = $j('#billing_region_id').val();
    if (!check_validate_data(billing_region_id)) {
        return false;
    }
    data_billing_address['region_id'] = billing_region_id;

    var billing_city = $j('#billing_city').val();
    if (!check_validate_data(billing_city)) {
        return false;
    }
    data_billing_address['city'] = billing_city;

    var billing_xaphuong = $j('#billing_xaphuong').val();
    if (!check_validate_data(billing_xaphuong)) {
        return false;
    }
    data_billing_address['xaphuong'] = billing_xaphuong;

    var billing_street1 = $j('#billing_street1').val();
    // if(!check_validate_data(billing_street1)){
    //     return false;
    // }
    data_billing_address['street'] = [];
    data_billing_address['street'][0] = billing_street1;


    var billing_address_id = $j('#billing_address_id').val();
    data_billing_address['address_id'] = billing_address_id;
    data_billing_address['use_for_shipping'] = 1;

    var result = {};
    result['billing'] = data_billing_address;
    return result;
}


function check_validate_data(source) {
    if (typeof (source) == 'undefined') {
        return false;
    }

    if (source == '') {
        return false;
    }

    return true;

}

var is_loading = false;

function show_loading(is_show) {
    is_loading = is_show;
    $j('.btn-checkout').attr('disabled', is_show)
    if (is_loading) {
        $j('#loading-label').css('display', 'block');
    } else {
        $j('#loading-label').css('display', 'none');
    }
}

function save_order() {
    if (is_loading) {
        return;
    }

    show_loading(true);
    var params = $j('#onestepcheckout_orderform').serialize();
    $j.ajax(
        {
            url: save_order_url,
            method: 'POST',
            data: params,
            success: function (result) {
                var response = result.evalJSON();
                if (response.redirect) {
                    location.href = check_secure_url(response.redirect);
                    return true
                }
                if (response.order_created) {
                    window.location = success_url;
                    return
                } else if (response.error_messages) {
                    var msg = response.error_messages;
                    if (typeof (msg) == 'object') {
                        msg = msg.join("\n")
                    }
                    alert(msg)
                }
                show_loading(false);
            },
            error: function (result) {
                show_loading(false);
            }
        }
    );

}
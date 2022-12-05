/**
 * Created by frank on 8/29/18.
 */

var diachigiaohang_all;
var list_quanhuyen_current;


jQuery(document).ready(function () {
    get_diachigiaohang();
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
    $j('#address-state-value').html('');
    if ($j('#address-state-value').css('disaplay') == 'none') {
        $j('#address-state-value'.css('display', 'block'));
    }
    var current_tinhthanh_id = $j('#address_region').val();
    var html_tinhthanh = '<ul>';

    for (var i = 0; i < diachigiaohang_all.length; i++) {
        var diachigiaohang_i = diachigiaohang_all[i];
        var id_diachigiaohang_id = diachigiaohang_i['id'];
        var name_diachigiaohang_id = diachigiaohang_i['name'];
        html_tinhthanh = html_tinhthanh + '<li onclick="select_option_address_state(this.id)" id="optionstate-' + id_diachigiaohang_id +'"';

        if( (typeof  current_tinhthanh_id != 'undefined' ) && current_tinhthanh_id != '' && current_tinhthanh_id == id_diachigiaohang_id){
            html_tinhthanh = html_tinhthanh + 'class="option-selected"';
            $j('#address-state span').html(name_diachigiaohang_id);
            list_quanhuyen_current = diachigiaohang_i['list_quanhuyen'];
            draw_default_quanhuyen();
        }

        html_tinhthanh = html_tinhthanh + '>' + name_diachigiaohang_id + '</li>';
    }

    html_tinhthanh = html_tinhthanh + '</ul>';

    $j('#address-state-value').html(html_tinhthanh);
}



function draw_default_quanhuyen() {
    var html_quanhuyen = '<ul>';

    var current_quanhuyen_id  = $j('#address_city').val();

    for (var i = 0; i < list_quanhuyen_current.length; i++) {
        var quanhuyen_i = list_quanhuyen_current[i];
        var id_quanhuyen_i = quanhuyen_i['id'];
        var name_quanhuyen_i = quanhuyen_i['name'];
        html_quanhuyen = html_quanhuyen + '<li onclick="select_option_address_city(this.id)" id="optioncity-' + id_quanhuyen_i +'"';

        if( (typeof  current_quanhuyen_id != 'undefined' ) && current_quanhuyen_id != '' && current_quanhuyen_id == id_quanhuyen_i){
            html_quanhuyen = html_quanhuyen + 'class="option-selected"';
            $j('#address-city span').html(name_quanhuyen_i);
            var list_xaphuong = quanhuyen_i['list_xaphuong'];
            draw_default_xaphuong(list_xaphuong);
        }

        html_quanhuyen = html_quanhuyen  + '>' + name_quanhuyen_i + '</li>';
    }

    html_quanhuyen = html_quanhuyen + '</ul>';
    $j('#address-city-value').html(html_quanhuyen);
}

function draw_default_xaphuong(list_xaphuong) {
    var html_xaphuong = '<ul>';

    var current_xaphuong_id = $j('#address_xaphuong').val();

    for (var i = 0; i < list_xaphuong.length; i++) {
        var xaphuong_i = list_xaphuong[i];
        var id_xaphuong_i = xaphuong_i['id'];
        var name_xaphuong_i = xaphuong_i['name'];
        html_xaphuong = html_xaphuong + '<li onclick="select_option_address_xaphuong(this.id)" id="optionxaphuong-' + id_xaphuong_i + '"';
        if( (typeof  current_xaphuong_id != 'undefined' ) && current_xaphuong_id != '' && current_xaphuong_id == id_xaphuong_i){
            html_xaphuong = html_xaphuong + 'class="option-selected"';
            $j('#address-xaphuong span').html(name_xaphuong_i);
        }
        html_xaphuong = html_xaphuong  + '>' + name_xaphuong_i + '</li>';
    }

    html_xaphuong = html_xaphuong + '</ul>';

    $j('#address-xaphuong-value').html(html_xaphuong);
}



function draw_quanhuyen() {

    $j('#address-city-value').html('');
    if ($j('#address-city-value').css('display') == 'none') {
        $j('#address-city-value').css('display', 'block');
        $j('#address-city i').css('color', '#0bb5a2');
    }


    var html_quanhuyen = '<ul>';

    for (var i = 0; i < list_quanhuyen_current.length; i++) {
        var quanhuyen_i = list_quanhuyen_current[i];
        var id_quanhuyen_i = quanhuyen_i['id'];
        var name_quanhuyen_i = quanhuyen_i['name'];
        html_quanhuyen = html_quanhuyen + '<li onclick="select_option_address_city(this.id)" id="optioncity-' + id_quanhuyen_i + '">' + name_quanhuyen_i + '</li>';
    }

    html_quanhuyen = html_quanhuyen + '</ul>';
    $j('#address-city-value').html(html_quanhuyen);
}

function draw_xaphuong(list_xaphuong) {

    $j('#address-xaphuong-value').html('');
    if ($j('#address-xaphuong-value').css('display') == 'none') {
        $j('#address-xaphuong-value').css('display', 'block');
        $j('#address-xaphuong i').css('color', '#0bb5a2');
    }

    var html_xaphuong = '<ul>';

    for (var i = 0; i < list_xaphuong.length; i++) {
        var xaphuong_i = list_xaphuong[i];
        var id_xaphuong_i = xaphuong_i['id'];
        var name_xaphuong_i = xaphuong_i['name'];
        html_xaphuong = html_xaphuong + '<li onclick="select_option_address_xaphuong(this.id)" id="optionxaphuong-' + id_xaphuong_i + '">' + name_xaphuong_i + '</li>';
    }

    html_xaphuong = html_xaphuong + '</ul>';

    $j('#address-xaphuong-value').html(html_xaphuong);


}

function select_address_state() {
    if ($j('#address-state-value').css('display') == 'none') {
        $j('#address-state-value').css('display', 'block');
        $j('#address-state i').css('color', '#0bb5a2');

    } else {
        $j('#address-state-value').css('display', 'none');
        $j('#address-state i').css('color', '#000000');
    }
}

function select_option_address_state(option_id) {
    var array_op = option_id.split('-');
    var state_id = array_op[1];
    $j('#address-state-value').css('display', 'none');
    var state_name = $j('#' + option_id).text();
    $j('#address-state span').html(state_name);
    $j('#address-state i').css('color', '#000000');
    $j('#address_region').val(state_id);

    for (var i = 0; i < diachigiaohang_all.length; i++) {
        var tinhthanh = diachigiaohang_all[i];
        if (tinhthanh['id'] == state_id) {
            list_quanhuyen_current = tinhthanh['list_quanhuyen'];
            draw_quanhuyen();
            break;
        }
    }

    $j('#address-state-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#'+id_element).removeClass('option-selected');
    });

    $j('#' + option_id).addClass('option-selected');

}

function select_address_city() {
    if ($j('#address-city-value').css('display') == 'none') {
        $j('#address-city-value').css('display', 'block');
        $j('#address-city i').css('color', '#0bb5a2');

    } else {
        $j('#address-city-value').css('display', 'none');
        $j('#address-city i').css('color', '#000000');
    }

}

function select_option_address_city(option_id) {
    var array_op = option_id.split('-');
    var city_id = array_op[1];
    $j('#address-city-value').css('display', 'none');
    var city_name = $j('#' + option_id).text();
    $j('#address-city span').html(city_name);
    $j('#address_city i').css('color', '#000000');
    $j('#address_city').val(city_id);

    for (var i = 0; i < list_quanhuyen_current.length; i++) {
        var quanhuyen = list_quanhuyen_current[i];
        if (quanhuyen['id'] == city_id) {
            var list_xaphuong = quanhuyen['list_xaphuong'];
            draw_xaphuong(list_xaphuong);
            break;
        }
    }

    $j('#address-city-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#'+id_element).removeClass('option-selected');
    });

    $j('#' + option_id).addClass('option-selected');

}

function select_address_xaphuong() {
    if ($j('#address-xaphuong-value').css('display') == 'none') {
        $j('#address-xaphuong-value').css('display', 'block');
        $j('#address-xaphuong i').css('color', '#0bb5a2');

    } else {
        $j('#address-xaphuong-value').css('display', 'none');
        $j('#address-xaphuong i').css('color', '#000000');
    }

}

function select_option_address_xaphuong(option_id) {
    var array_op = option_id.split('-');
    var xaphuong_id = array_op[1];
    $j('#address-xaphuong-value').css('display', 'none');
    var xaphuong_name = $j('#' + option_id).text();
    $j('#address-xaphuong span').html(xaphuong_name);
    $j('#address_xaphuong i').css('color', '#000000');
    $j('#address_xaphuong').val(xaphuong_id);

    $j('#address-xaphuong-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#'+id_element).removeClass('option-selected');
    });

    $j('#' + option_id).addClass('option-selected');
}

/**
 * Created by frank on 10/1/18.
 */


$j = jQuery.noConflict();

var brands_model_data;


function get_brand_model() {

    console.log('brand model url: ' + brand_model_url);
    $j.ajax({
        url: brand_model_url,
        method: "GET",
        success: function (result) {
            brands_model_data = result;

            draw_brand();
        },
        error: function (result) {
            alert('Chúng tôi đang bảo trì dịch vụ này. Vui lòng thử lại sau')
        }
    });
}

var is_draw_brand = false;
function draw_brand() {
    is_draw_brand = true;
    // if ($j('#brand-mobile-value').css('display') == 'none') {
    //     $j('#brand-mobile-value').css('display', 'block');
    //     $j('#brand-mobile i').css('color', '#0bb5a2');
    // }

    $j('#brand-mobile-value').html('');
    var html_brand = '<ul>';

    for (var i = 0; i < brands_model_data.length; i++) {
        var brand_i = brands_model_data[i];
        var brand_id = brand_i["brand"];
        var brand_name = brand_i["brand_name"];

        html_brand = html_brand + '<li onclick="select_option_brand(this.id)" id="optionbrand-' + brand_id + '">' + brand_name + '</li>';
    }

    html_brand = html_brand + '</ul>';
    $j('#brand-mobile-value').html(html_brand);
}

function select_option_brand(option_brand_id) {

    var array_brand = option_brand_id.split('-');
    var brand_id = array_brand[1];


    $j('#brand-mobile-input').val(brand_id);

    $j('#brand-mobile i').css('color', '#000000');

    var list_model;
    for (var i = 0; i < brands_model_data.length; i++) {
        var brand_i = brands_model_data[i];
        var brand_id_i = brand_i["brand"];

        if (brand_id_i == brand_id) {
            list_model = brand_i['list_model'];
            $j('#brand-mobile span').html(brand_i["brand_name"]);
            break;
        }
    }


    if ((typeof  list_model != 'undefined') && list_model.length > 0) {
        draw_model(list_model);
    } else {
        $j('#model-main').css('display', 'none');
    }

    $j('#brand-mobile-value').css('display', 'none');
    $j('#brand-mobile-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#' + id_element).removeClass('option-selected');
    });

    $j('#' + option_brand_id).addClass('option-selected');

}

function select_brand() {

    if (!is_draw_brand) {
        draw_brand();
    }

    if ($j('#brand-mobile-value').css('display') == 'none') {
        $j('#brand-mobile-value').css('display', 'block');
        $j('#brand-mobile i').css('color', '#0bb5a2');

    } else {
        $j('#brand-mobile-value').css('display', 'none');
        $j('#brand-mobile i').css('color', '#000000');
    }
}

function select_mobile() {
    if ($j('#model-mobile-value').css('display') == 'none') {
        $j('#model-mobile-value').css('display', 'block');
        $j('#model-mobile i').css('color', '#0bb5a2');

    } else {
        $j('#model-mobile-value').css('display', 'none');
        $j('#model-mobile i').css('color', '#000000');
    }
}

function draw_model(list_model) {

    $j('#model-main').css('display', 'block');
    if ($j('#model-mobile-value').css('display') == 'none') {
        $j('#model-mobile-value').css('display', 'block');
        $j('#model-mobile i').css('color', '#0bb5a2');
    }

    $j('#model-mobile-value').html('');
    var html_model = '<ul>';

    for (var i = 0; i < list_model.length; i++) {
        var model_i = list_model[i];
        var model_id = model_i["brand_model"];
        var model_name = model_i["model_name"];
        html_model = html_model + '<li onclick="select_option_model(this.id)" id="optionmodel-' + model_id + '">' + model_name + '</li>';
    }

    html_model = html_model + '</ul>';
    $j('#model-mobile-value').html(html_model);

}

function select_option_model(option_model_id) {

    var array_model = option_model_id.split('-');
    var model_id = array_model[1];
    $j('#model-mobile i').css('color', '#000000');
    $j('#model-mobile-input').val(model_id);

    var model_name = $j('#' + option_model_id).text();
    $j('#model-mobile span').html(model_name);


    $j('#model-mobile-value').css('display', 'none');
    $j('#model-mobile-value > ul > li').each(function () {
        var id_element = this.id;
        $j('#' + id_element).removeClass('option-selected');
    });

    $j('#' + option_model_id).addClass('option-selected');



}


jQuery(document).ready(function () {

    $j('#btn-cancel-mockup').on('click', function () {
        location.href = mockup_url;
    });

    get_brand_model();

    $j('#mockup_file-design').on('change',function () {
        enable_save_button(true);
    })


    $j('#btn-save-new-mockup').on('click', function () {
        if (!$j('#btn-save-new-mockup').hasClass('btn-save-new-mockup-disable')) {
            $j('#btn-save-new-mockup').addClass('btn-save-new-mockup-disable');
            $j('#form-new-mockup').submit();
        }
    });

    function enable_save_button(is_enable) {

        if (is_enable) {

            if ($j('#btn-save-new-mockup').hasClass('btn-save-new-mockup-disable')) {
                $j('#btn-save-new-mockup').removeClass('btn-save-new-mockup-disable');
            }

        } else {
            if (!$j('#btn-save-new-mockup').hasClass('btn-save-new-mockup-disable')) {
                $j('#btn-save-new-mockup').addClass('btn-save-new-mockup-disable');
            }
        }

    }

});
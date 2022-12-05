/**
 * Created by frank on 12/21/18.
 */

$j = jQuery.noConflict();
var brands_model_data;
jQuery(document).ready(function () {
        get_brand_model();

    $j('#btn-get-size').on('click',function () {
        $j('#btn-get-size').css('display','none');
        $j('#tb-loading-size').css('display','block');
        var brand_id = $j('#brand-mobile-input').val();
        var model_id =  $j('#model-mobile-input').val();

        var brand_name = $j('#brand-mobile span').text();
        var model_name = $j('#model-mobile span').text();

        var url = size_url + '?brand_id='+brand_id+'&model_id='+model_id;
        $j.ajax({
            url: url,
            method: "GET",
            success: function (result) {
                if(result.status == 1){
                    $j('#message-size').css('display','none');
                    $j('#btn-get-size').css('display','block');
                    $j('#tb-loading-size').css('display','none');

                    var width = result.width;
                    var height = result.height;

                    var result_html = '<tr>';
                    result_html = result_html + '<td>'+brand_name+'</td>';
                    result_html = result_html + '<td>'+model_name+'</td>';
                    result_html = result_html + '<td>'+width+'</td>';
                    result_html = result_html + '<td>'+height+'</td>';
                    result_html = result_html + '<td>300</td>';
                    result_html = result_html + '</tr>';

                    $j('#tbody-result-size').html(result_html);


                }else{
                    $j('#btn-get-size').css('display','block');
                    $j('#tb-loading-size').css('display','none');
                    $j('#message-size span').html(result.message);
                    $j('#message-size').css('display','block');
                }
            },
            error: function (result) {
                $j('#btn-get-size').css('display','block');
                $j('#tb-loading-size').css('display','none');
                alert('Chúng tôi đang bảo trì dịch vụ này. Vui lòng thử lại sau')
            }
        });

    });

    }
);


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
    console.log('select option model done');
    $j('#btn-get-size').css('display','block');

}


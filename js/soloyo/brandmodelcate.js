/**
 * Created by frank on 12/4/18.
 */

var brands_model_data;

jQuery(document).ready(function () {
    console.log(' is_shoud_request_brand_model ' + is_shoud_request_brand_model);
    if(is_shoud_request_brand_model && (typeof  brands_model_data == 'undefined')){
        get_brand_model();
    }

    $j('#btn-view-case').on('click',function () {
        var current_brand_id = $j('#brand-mobile-input').val();
        var current_model_id = $j('#model-mobile-input').val();
        var current_brand_name = $j('#brand-mobile span').text();
        var current_model_name = $j('#model-mobile span').text();

    var    open_cate_url = current_url_cate + '?brand_id=' + current_brand_id + '&model_id=' + current_model_id+'&model_name='+current_model_name+'&brand_name='+current_brand_name+'&cate_id='+cate_id+'&is_refresh=1';
        location.href = open_cate_url;
    })

    $j('#btn-clear').on('click',function () {
        var    open_cate_url = current_url_cate +'?cate_id='+cate_id+'&is_refresh=1';
        location.href = open_cate_url;
    })


});

function show_filter_brand_model() {
    if(typeof  brands_model_data != 'undefined' && brands_model_data.length > 0){

    }else{
        $j('.selected-brand-box').css('display','none');
        get_brand_model();
    }
}

function get_brand_model() {


    show_loading(true);
    $j.ajax({
        url: brand_model_url,
        method: "GET",
        success: function (result) {
            show_loading(false);
            brands_model_data = result;
            if ((typeof  brands_model_data != 'undefined') && brands_model_data.length > 0) {
                draw_brand();
            }

        },
        error: function (result) {
            show_loading(false);
            alert('Chúng tôi đang bảo trì dịch vụ này. Vui lòng thử lại sau')
        }
    });
}

function show_loading(is_show) {
    if(is_show){

        $j('#filter-brand-model-box').css('display','block');

        $j('#filter-box-body').css('display','none');
        $j('#loading-brand-model').css('display','block');
    }else{
        $j('#filter-box-body').css('display','block');
        $j('#loading-brand-model').css('display','none');
    }
}


function draw_brand() {
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

    change_status_button(false);

    var array_brand = option_brand_id.split('-');
    var brand_id = array_brand[1];


    $j('#brand-mobile-input').val(brand_id);

    $j('#brand-mobile i').css('color', '#000000');

    var list_model;
    for (var i = 0; i < brands_model_data.length; i++) {
        var brand_i = brands_model_data[i];
        var brand_id_i = brand_i["brand"];
        console.log(i + ' selected brand id ' + brand_id + ' current brand id ' + brand_id_i);
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

    change_status_button(true);
}

function change_status_button(is_active) {
    if (is_active) {
        $j('.div-btn-view-case').css('display', 'block');
        var current_brand_name = $j('#brand-mobile span').text();
        var current_model_name = $j('#model-mobile span').text();
        var text = 'Xem ốp cho ' + current_brand_name + ' ' + current_model_name;
        $j('#btn-view-case').html(text);
    } else {
        $j('.div-btn-view-case').css('display', 'none');
    }
}
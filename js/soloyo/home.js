/**
 * Created by frank on 8/10/18.
 */

var $j = jQuery.noConflict();

jQuery(document).ready(function () {


    // get_product_list();
    // get_categories();
    // get_recently_product();

    function get_recently_product() {
        $j.ajax(
            {
                url: recently_product_url,
                method: "GET",
                success: function (result) {

                    draw_recently_product(result);
                    $j("#owl-recently-product").owlCarousel(
                        {
                            pagination: false,
                            items: 4,
                            itemsDesktop: [1199,4],
                            itemsDesktopSmall: [979,4],
                            itemsMobile: [479,3],


                        }

                    );
                }
            }
        );
    }

    function draw_recently_product(data) {
        $j('#owl-recently-product').html('');
        if (data.length > 0) {
            $j('#container-owl-recently-product').css('display','block');
            var html = '';
            for (var i = 0; i < data.length; i++) {

                var data_i = data[i];

                html = html + draw_recently_product_row(data_i);
            }

            $j('#owl-recently-product').html(html);
        }
        else{
            $j('#container-owl-recently-product').css('display','none');
        }
    }

    function draw_recently_product_row(data) {

        var name = data['name'];
        var image = data['image'];
        var url = data['url'];

        var html = '<div style="margin-right: 10px;">';
        html = html + '<a href="'+url+'" title="'+name+'">';
        html = html + '<img style="border: solid 1px #f2f3f5;max-width: 100%"  src="'+image+'" alt="'+name+'">';
        html = html + '</a>';
        html = html +'<h3 class="name-home-productlist" style="text-align: center" >'+name+'</h3>';
        html = html + '</div>';
        return html;
    }


    function get_product_list() {
        $j.ajax(
            {
                url: get_home_product_list,
                method: "GET",
                success: function (result) {
                    draw_product_list(result);
                }
            }
        );

    }

    function draw_product_list(data) {

        $j('#home-productlist').html('');

        if (data.length > 0) {
            var html = '';
            for (var i = 0; i < data.length; i++) {
                var product_list_i = data[i];
                html = html + draw_product_list_row(product_list_i);
            }
            $j('#home-productlist').html(html);

            for (var i = 0; i < data.length; i++) {
                var product_list_i = data[i];
                var product_list_i_id = product_list_i['homeproducts_id'];
                $j('#owl-carousel-productlist-'+product_list_i_id).owlCarousel(
                    {
                        pagination: false,
                        items: 4,
                        itemsDesktop: [1199,4],
                        itemsDesktopSmall: [979,4],
                        itemsMobile: [479,3],


                    }

                );
            }
        }

    }

    function draw_product_list_row(product_list_i) {

        var html = '<div class=" row-home-productlist row">';
        html = html + '<div class="title-h2-home">';
        html = html + '<h2>';
        html = html + product_list_i['name'].toUpperCase();
        html = html + '</h2>';
        html = html + '</div>';

        html = html + '<hr class="line-home">';
        var product_list_i_id = product_list_i['homeproducts_id'];
        html = html + '<div style="margin-top: 10px" class="row-productlist-home owl-carousel" id="owl-carousel-productlist-'+product_list_i_id+'">';


        var list_items = product_list_i['items'];
        for (var i = 0; i < list_items.length; i++) {
            html = html + draw_item_product_list_row(list_items[i]);
        }


        html = html + '</div>';

        html = html + '</div>';

        return html;
    }

    function draw_item_product_list_row(item_i) {
        var name = item_i['name'];
        var image = item_i['image'];
        var url =  item_i['url'];

        var html = '<div style="margin-right: 10px;">';
        html = html + '<a href="'+url+'" title="'+name+'">';
        html = html + '<img style="border: solid 1px #f2f3f5;max-width: 100%"  src="'+image+'" alt="'+name+'">';
        html = html + '</a>';
        html = html +'<h3 class="name-home-productlist" style="text-align: center" >'+name+'</h3>';
        html = html + '</div>';
        return html;

        // var html = '<li>';
        // html = html + '<a href="' + item_i['url'] + '" title="' + item_i['name'] + '">';
        // html = html + '<img style="border: solid 1px #f2f3f5" class="img-home-productlist"src="' + item_i['image'] + '"alt="' + item_i['name'] + '">';
        // html = html + '</a>';
        // html = html + ' <h3 style="text-align: left" class="name-home-productlist">' + item_i['name'] + '</h3>';
        //
        // html = html + '</li>';
        // return html;
    }


    function get_categories() {
        console.log(' Get category');
        $j.ajax({
            url: get_home_cate,
            method: "GET",
            success: function (result) {
                draw_categories(result);
            }
        });
    }

    function draw_categories(data) {
        console.log('Draw categories');
        $j('#body-cac-chu-de').html('');
        var html = '';
        for (var i = 0; i < data.length; i++) {

            var home_category = data[i];
            var class_name = '';
            if (i % 3 == 0) {
                class_name = 'first'
            }
            else if (i % 3 == 2) {
                class_name = 'third'
            }
            html = html + '<div class="' + class_name + ' home-cate">';

            html = html + '<a href="' + home_category['url'] + '" title="' + home_category['name'] + '">';
            html = html + '<img class="image-home-cate" src="' + home_category['image'] + ' "alt="' + home_category['name'] + '">';
            html + html + '</a>';
            html = html + ' <div class="title-category-home"><span>' + home_category['name'] + '</span></div>';
            html = html + '</div>';

        }

        $j('#body-cac-chu-de').html(html);


    }

});



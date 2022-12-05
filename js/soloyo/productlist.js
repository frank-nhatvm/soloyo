/**
 * Created by frank on 8/20/18.
 */

var is_loading = false;

jQuery(document).ready(function () {

    $j(window).scroll(function (event) {
        var window_height = jQuery(window).height();
        var document_height = jQuery(document).height();
        var top = jQuery(window).scrollTop();

        if (top = document_height - window_height) {
            load_more_product();
        }
        //console.log('windown_height ' + window_height + ' document height ' + document_height + ' top ' + top);
    });

});

function load_more_product() {
    if (!is_loading) {
        is_loading = true;

    }
}
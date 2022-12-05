/**
 * Created by frank on 12/27/18.
 */

var $j = jQuery.noConflict();
jQuery(document).ready(function () {

    if( typeof  $j('#owl-related-product') != 'undefined'){
        $j("#owl-related-product").owlCarousel(
            {
                pagination: false,
                items: 4,
                itemsDesktop: [1199,4],
                itemsDesktopSmall: [979,4],
                itemsMobile: [479,3],


            }
        )
    }else{
        $j('#soloyo-related-product').css('display','none');
    }


});

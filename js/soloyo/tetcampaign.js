/**
 * Created by frank on 1/5/19.
 */

$j = jQuery.noConflict();
jQuery(document).ready(function () {

    $j("#owl-recently-product-tet").owlCarousel(
        {
            pagination: false,
            items: 4,
            itemsDesktop: [1199,4],
            itemsDesktopSmall: [979,4],
            itemsMobile: [479,3],


        }

    );

})

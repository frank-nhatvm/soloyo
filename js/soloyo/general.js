/**
 * Created by frank on 8/12/18.
 */
var $j = jQuery.noConflict();
function openNav() {
    console.log("Call open Nav");
    $j('#mobile-menu-container').css('width','100%');
}

function closeNav() {
    console.log("Call close Nav");
    $j('#mobile-menu-container').css('width','0px');
}

function goHome() {
    location.href = home_url;
}

jQuery(document).ready(function () {

    $j('.demo-notice').css('display', 'none');

    $j('#menu-top-logo').on('click', function () {

        location.href = home_url;
    });




    // var lastScrollTop = 130;
    // $j(window).scroll(function (event) {
    //     var st = jQuery(this).scrollTop();
    //     if (st > lastScrollTop) {
    //         $j('#header-category').addClass('sticky');
    //         //jQuery('#fakeHeader').addClass('opened');
    //     } else {
    //         $j('#header-category').removeClass('sticky');
    //         //jQuery('#fakeHeader').removeClass('opened');
    //     }
    // });

    $j('#shopping_cart').click(function () {
        if ($j('#addedshCartItems').css('display') == 'none') {
            $j('#addedshCartItems').css('display', 'block');
        } else {
            $j('#addedshCartItems').css('display', 'none');
        }
    });

    $j('#menu-top-search-icon').click(function () {
        console.log('click on search');
        location.href = search_device_url;

    });

    $j('#close-menu-top-search').click(function () {
        $j('#menu-top-search-container').css('display', 'none');
    });

    $j('#show-account-menu').click(function () {
        console.log('click on menu ' + $j('.list-account-action').css('display'));
        if ($j('.list-account-action').css('display') == 'none') {
            $j('.list-account-action').css('display', 'block');
        } else {
            $j('.list-account-action').css('display', 'none');
        }
    });

});



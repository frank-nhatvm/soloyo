/**
 * Created by frank on 9/30/18.
 */

$j = jQuery.noConflict();

jQuery(document).ready(function () {

    $j('#btn-edit').on('click',function () {
        $j('#btn-cancel').css('display','inline');
        $j('#btn-update').css('display','inline');
        $j('#btn-edit').css('display','none');
        $j('.designer-input').each(function () {
            $j('#'+this.id).prop("disabled",false);
        })
    });

    $j('#btn-cancel').on('click',function () {
        $j('#btn-cancel').css('display','none');
        $j('#btn-update').css('display','none');
        $j('#btn-edit').css('display','inline');
        $j('.designer-input').each(function () {
            $j('#'+this.id).prop("disabled",true);
        })
    });


    $j('#btn-update').on('click',function () {

        $j("#update_info_form").submit();

    });

    }
);

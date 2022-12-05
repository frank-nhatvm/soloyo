/**
 * Created by frank on 10/1/18.
 */


$j = jQuery.noConflict();


var is_uploaded_image_print = false;
var is_uploaded_image_product = false;



jQuery(document).ready(function () {

    $j('#btn-cancel-design').on('click', function () {
        location.href = design_url;
    });

    $j('#description').on('change',function () {
        console.log('description change');
        enable_save_button(true);
    });


    $j('#image_product-design').on('change', function () {
        is_uploaded_image_product = true;
        show_preview_product_image(this);
        validate();
    });

    function show_preview_product_image(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $j('#image_product-preview').css('display', 'block');
                $j('#image_product-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $j('#image_print-design').on('change', function () {
        is_uploaded_image_print = true;
        show_preview_image_print(this);
        validate();
    });

    function show_preview_image_print(input) {

        var url_pdf = URL.createObjectURL(input.files[0]);
        $j('#image_print-preview').css('display', 'block');
        $j('#image_print-preview').attr('src', url_pdf);

        // if (input.files && input.files[0]) {
        //     var reader = new FileReader();
        //
        //     reader.onload = function (e) {
        //         $j('#image_print-preview').css('display', 'block');
        //         $j('#image_print-preview').attr('src', e.target.result);
        //     }
        //
        //     reader.readAsDataURL(input.files[0]);
        // }
    }

    $j('#btn-save-new-design').on('click', function () {
        if (!$j('#btn-save-new-design').hasClass('btn-save-new-design-disable')) {
            $j('#form-new-design').submit();
        }
    });


    $j('#btn-save-new-design').on('click', function () {
        if (!$j('#btn-save-new-design').hasClass('btn-save-new-design-disable')) {
            $j('#form-new-design').submit();
        }
    });

    $j('#btn-delete-design').on('click', function () {
        if(!$j('#btn-delete-design').hasClass('btn-delete-design-disable')){
            $j('#message').html('Đang gửi yêu cầu xoá. Vui lòng đợi trong giây lát.');
            $j('#btn-delete-design').addClass('btn-delete-design-disable');
            delete_design();
        }

    });

    function delete_design() {
        $j.ajax({
            url: deleteDesign_url,
            type: "GET",
            success: function (result) {
                if (result.status == 1) {
                    location.href = design_url;
                } else {
                    $j('#btn-delete-design').removeClass('btn-delete-design-disable');
                    $j('#message').html(result.message);
                }
            },
            error: function (result) {
                $j('#btn-delete-design').removeClass('btn-delete-design-disable');
                $j('#message').html(result.message);
            }
        });
    }

    function validate() {
        if (!is_uploaded_image_print && !is_uploaded_image_product) {
            enable_save_button(false);
            return false;
        }


        enable_save_button(true);

        return true;

    }

    function enable_save_button(is_enable) {

        if (is_enable) {

            if ($j('#btn-save-new-design').hasClass('btn-save-new-design-disable')) {
                $j('#btn-save-new-design').removeClass('btn-save-new-design-disable');
            }

        } else {
            if (!$j('#btn-save-new-design').hasClass('btn-save-new-design-disable')) {
                $j('#btn-save-new-design').addClass('btn-save-new-design-disable');
            }
        }

    }




});
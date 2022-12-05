/**
 * Created by frank on 10/1/18.
 */
$j = jQuery.noConflict();

jQuery(document).ready(function () {


    $j('#amount').on('change', function () {
        var current_amount = $j('#amount').val();

        if ($j.isNumber(current_amount) && $j.isNumber(balance)) {

            if (current_amount > balance) {
                var message = 'Số tiền muốn rút phải nhỏ hơn số dư';
                disable_button_draw(true);
                show_waring_amount(message)
            }
            else if(current_amount < 50000){
                var message = 'Số tiền muốn rút phải lớn hơn 50000';
                disable_button_draw(true);
                show_waring_amount(message)
            }

        } else {
            disable_button_draw(true);
        }
    });

    function disable_button_draw(is_disable) {
        $j('#btn-send-draw-request').attr('disable', is_disable);
    }

    function show_waring_amount(message) {
        $j('#amount-warning').html(message);
    }

    $j('#btn-send-draw-request').on('click',function () {
        var current_amount = $j('#amount').val();

        if (isNum(current_amount) && isNum(balance)) {

            var x = current_amount % 50000;
            if (current_amount > balance) {
                var message = 'Số tiền muốn rút phải nhỏ hơn số dư';
                disable_button_draw(true);
                show_waring_amount(message)
            }
            else if(current_amount < 50000){
                var message = 'Số tiền muốn rút phải lớn hơn 50000';
                disable_button_draw(true);
                show_waring_amount(message)
            }
            else if(x != 0){
                var message = 'Số tiền muốn rút phải là bội số của  50000';
                disable_button_draw(true);
                show_waring_amount(message)
            }
            else{
                $j('#form-draw-request').submit();
            }


        } else {
            disable_button_draw(true);
        }
    });



});
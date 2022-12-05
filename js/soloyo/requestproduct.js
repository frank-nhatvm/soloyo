var $j = jQuery.noConflict();
jQuery(document).ready(function () {

        $j('#btn-send-request').on('click', function () {

            var data_request = get_data();

            if (!data_request) {
                $j('#message-request span').html('Vui lòng nhập đầy đủ dữ liệu');
                return;
            }
            show_loading(true);
            $j.ajax({
                url: request_product_url,
                method: "POST",
                data: data_request,
                success: function (result) {
                    if (result.status == 1) {
                        show_message('', true);
                    } else {
                        show_message(result.message, false);
                    }
                },
                error: function (result) {
                    show_message('Đã có lỗi xảy ra. Vùi lòng thử lại', false);
                }
            });


        });


        function get_data() {

            var data = {};

            var user_id = $j('#user_id').val();
            if (check_validate_data(user_id)) {
                data['user_id'] = user_id;
                data['email'] = $j('#email').val();
            } else {
                var email = $j('#request-email').val();
                if (!check_validate_data(email)) {
                    return false;
                }
                data['email'] = email;

                var phone = $j('#request-phone').val();
                if (!check_validate_data(phone)) {
                    return false;
                }
                data['phone'] = phone;
            }


            var requirement = $j('#request-requirement').val();
            if (!check_validate_data(requirement)) {
                return false;
            }
            data['requirement'] = requirement;

            var designer_id = $j('#designer_id').val();
            data['designer_id'] = designer_id;

            var product_id = $j('#product_id').val();
            data['product_id'] = product_id;

            return data;
        }


        function check_validate_data(source) {
            if (typeof (source) == 'undefined') {
                return false;
            }

            if (source == '') {
                return false;
            }

            return true;

        }

        function show_message(message, is_success) {

            show_loading(false);
            if (is_success) {
                $j('#message-request span').html('Đã gửi yêu cầu thành công.');
                $j('#message-request a').css('display', 'block');
                $j('#btn-send-request').css('display', 'none');
            } else {
                $j('#message-request span').html(message);
            }

        }

        function show_loading(is_show) {
            if (is_show) {
                $j('#message-request span').html('');
                $j('#tb-loading').css('display', 'block');
                $j('#btn-send-request').css('display', 'none');
            } else {
                $j('#tb-loading').css('display', 'none');
                $j('#btn-send-request').css('display', 'block');
            }
        }

    }
);


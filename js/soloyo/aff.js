/**
 * Created by frank on 12/7/18.
 */
$j = jQuery.noConflict();
jQuery(document).ready(function () {

    $j('#btn-join-aff').on('click', function () {
        checkLoginState();
    });

    function checkLoginState() {

        $j('#save-success-msg').css('display','none');
        showLoading(true);
        FB.login(function (response) {
            if (response.status === 'connected') {
                console.log('CONNECTED');
                console.log(response);
                // dang ky va chuyen sang trang tu thiet ke
                fetchInforUser();

            } else {
                showLoading(false);
            }

        }, {scope: 'public_profile,email'});

        // FB.getLoginStatus(function (response) {
        //     $j('#save-success-msg').css('display','none');
        //     showLoading(true);
        //     statusChangeCallback(response);
        // });
    }

    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            fetchInforUser();
            // da co user_id, chuyen sang trang tu thiet ke
        }
        else if (response.status === 'unknown') {
            // need to redirect to login of facebook
            console.log('Redirect to Facebook Login');
            showLoading(false);
            show_message('Bạn chưa đăng ký thành công. Vui lòng thử lại');
        }

        else {
            FB.login(function (response) {
                if (response.status === 'connected') {
                    console.log('CONNECTED');
                    console.log(response);
                    // dang ky va chuyen sang trang tu thiet ke
                    fetchInforUser();

                } else {
                    showLoading(false);
                }

            }, {scope: 'public_profile,email'});
        }
    }

    function fetchInforUser() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', {locale: 'en_US', fields: 'name, email'}, function (response) {
            console.log(response);
            var name = response.name;
            var email = response.email;
            var face_id = response.id;

            register_aff(name, face_id, email);
        });
    }

    function register_aff(player_aff_name, player_aff_id, player_aff_email) {
        $j.ajax({
            url: register_aff_url,
            type: "POST",
            data: {"email": player_aff_email, "face_id": player_aff_id, "name": player_aff_name},

            success: function (result) {
                showLoading(false);
                if (result.status === 'success') {
                    joinAFF();

                } else {
                    if(result.is_joined == 1){
                        $j('#btn-join-aff').css('display','none');
                    }
                    show_message(result.message);
                }


            },
            error: function (result) {
                showLoading(false);
                show_message('Bạn chưa đăng ký thành công. Vui lòng thử lại');
            }
        });
    }

    function joinAFF() {

        location.href = cate_design_url;
    }

    function show_message(message) {
        $j('#save-success-msg').css('display','block');
        $j('#save-success-msg span').html(message);
    }


    function showLoading(is_show) {
        if(is_show){
            $j('#btn-join-aff').css('display','none');
            $j('#tb-loading-design').css('display','block');
        }else{
            $j('#btn-join-aff').css('display','block');
            $j('#tb-loading-design').css('display','none');
        }
    }

})
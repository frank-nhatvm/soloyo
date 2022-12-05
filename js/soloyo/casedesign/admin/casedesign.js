/**
 * Created by frank on 9/12/18.
 */

$j = jQuery.noConflict();
$j(document).ready(function () {


        $j('.design-area').draggable({
            containment: "parent",
            cursor: "crosshair",
            // start: function( event, ui ) {
            //     $j('.design-area').css('z-index', '5000');
            //     $j('.design-area').css('opacity', '0.5');
            // },
            drag: function (event, ui) {
                var width = $j('.design-area').width();
                var height = $j('.design-area').height();

                $j('#design_area_width').val(width);
                $j('#design_area_height').val(height);

                var top = ui.position.top;
                var left = ui.position.left;

                $j('#design_area_top').val(top);
                $j('#design_area_left').val(left);
            },
            stop: function (event, ui) {

                var width = $j('.design-area').width();
                var height = $j('.design-area').height();

                $j('#design_area_width').val(width);
                $j('#design_area_height').val(height);

                var top = ui.position.top;
                var left = ui.position.left;

                $j('#design_area_top').val(top);
                $j('#design_area_left').val(left);
            }

        });


        $j('.design-area').resizable({
            aspectRatio: false,
            containment: "parent",


            // start: function( event, ui ) {
            //     $j('.design-area').css('z-index', '5000');
            //     $j('.design-area').css('opacity', '0.5');
            // },
            resize: function (event, ui) {
                var width = $j('.design-area').width();
                var height = $j('.design-area').height();

                $j('#design_area_width').val(width);
                $j('#design_area_height').val(height);

                var top = ui.position.top;
                var left = ui.position.left;

                $j('#design_area_top').val(top);
                $j('#design_area_left').val(left);
            },
            stop: function (event, ui) {


                var width = $j('.design-area').width();
                var height = $j('.design-area').height();

                $j('#design_area_width').val(width);
                $j('#design_area_height').val(height);

                var top = ui.position.top;
                var left = ui.position.left;

                $j('#design_area_top').val(top);
                $j('#design_area_left').val(left);
            }

        });


        $j('#overlay_image').change(function () {
            show_overlayimage(this);
        });

        function show_overlayimage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $j('#img_overlay_design').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }


    }
);

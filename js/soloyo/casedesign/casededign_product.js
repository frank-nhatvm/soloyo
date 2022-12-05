/**
 * Created by frank on 9/13/18.
 */
var $j = jQuery.noConflict();

var listUserText = [];

function draw_main_color(id, title) {

    var html = ' <div class="color-container">';

    html = html + '<div class="custom-select" id="' + id + '" onclick="select_main_text_color(this.id)"  >';

    html = html + '<input onchange="change_color_input(' + '\'' + id + '\'' + ')" type="text" name="' + id + 'input-name' + '" id="' + id + 'input-id' + '" value="" maxlength="7"/>';

    html = html + '</div>';

    html = html + '<div class="custom-select-option box-shadow-class" id="' + id + '-value" style="display: none">';
    html = html + '</div>';

    html = html + '</div>';
    return html;
}

function change_color_input(parent_id) {
    var input_id = parent_id + 'input-id';
    var input_value = $j('#' + input_id).val();

    if ((typeof  input_value != 'undefined')) {
        var id_value_div = '#' + parent_id + '-value';
        if (input_value.length == 7) {
            if ($j(id_value_div).css('display') == 'block') {
                $j(id_value_div).css('display', 'none');
            }
            if (parent_id == 'cd-textcolor-select') {
                CaseDesign.update_text('fill', input_value);
            }
            else if (parent_id == 'cd-background-select') {
                CaseDesign.update_text('textBackgroundColor', input_value);
            }

        } else {
            if ($j(id_value_div).css('display') == 'none') {
                $j(id_value_div).css('display', 'block');

            }
        }
    }
}

function select_main_text_color(id) {

    var id_value_div = '#' + id + '-value';
    var id_main_div = '#' + id;
    if ($j(id_value_div).css('display') == 'none') {
        $j(id_value_div).css('display', 'block');

    }
    else {
        $j(id_value_div).css('display', 'none');

    }

    //if (!this.is_draw_option) {
    draw_option(id);
    //}
}

function draw_option(parent_id) {
    //this.is_draw_option = true;
    var data_option = [
        {"key": "ffffff", "value": "#ffffff"},
        {"key": "000000", "value": "#000000"},
        {"key": "2d2d2d", "value": "#2d2d2d"},
        {"key": "39241e", "value": "#39241e"},
        {"key": "1d8b24", "value": "#1d8b24"},
        {"key": "809c79", "value": "#809c79"},
        {"key": "02b218", "value": "#02b218"},
        {"key": "3ec797", "value": "#3ec797"},
        {"key": "86825a", "value": "#86825a"},
        {"key": "947565", "value": "#947565"},
        {"key": "a0db8e", "value": "#a0db8e"},
        {"key": "9dd7b4", "value": "#9dd7b4"},
        {"key": "ffa500", "value": "#ffa500"},
        {"key": "ff9913", "value": "#ff9913"},
        {"key": "fda60a", "value": "#fda60a"},
        {"key": "ceb6ca", "value": "#ceb6ca"},
        {"key": "bfb7c7", "value": "#bfb7c7"},
        {"key": "262639", "value": "#262639"},
        {"key": "656598", "value": "#656598"},
        {"key": "4c4c72", "value": "#4c4c72"},
        {"key": "d8d8eb", "value": "#d8d8eb"},
        {"key": "cbcbe5", "value": "#cbcbe5"},
        {"key": "d8d8eb", "value": "#d8d8eb"},
        {"key": "d8d8eb", "value": "#d8d8eb"},
        {"key": "bfbfdf", "value": "#bfbfdf"},
        {"key": "7f7fbf", "value": "#7f7fbf"},
        {"key": "000080", "value": "#000080"},
        {"key": "c4fcdd", "value": "#c4fcdd"},
        {"key": "b6fcd5", "value": "#b6fcd5"},
        {"key": "cbbeb5", "value": "#cbbeb5"},
        {"key": "dddddd", "value": "#dddddd"},
        {"key": "f7d9f7", "value": "#f7d9f7"},
        {"key": "eadcce", "value": "#eadcce"},
        {"key": "d6ba9d", "value": "#d6ba9d"},
        {"key": "dff8df", "value": "#dff8df"},
        {"key": "afeeb0", "value": "#afeeb0"},
        {"key": "afeecf", "value": "#afeecf"},
        {"key": "afcfee", "value": "#afcfee"},
        {"key": "afeeee", "value": "#afeeee"},
        {"key": "c1e0ff", "value": "#c1e0ff"},
        {"key": "80bfff", "value": "#80bfff"},
        {"key": "d6eaff", "value": "#d6eaff"},
        {"key": "99ccff", "value": "#99ccff"},
        {"key": "84c1ff", "value": "#84c1ff"},
        {"key": "70b7ff", "value": "#70b7ff"}];

    if (parent_id == 'cd-background-select') {
        data_option.unshift({"key": "", "value": ""})
    }


    var id_value_div = '#' + parent_id + '-value';
    $j(id_value_div).html('');

    if ((typeof  data_option != 'undefined') && data_option.length > 0) {

        var html_options = '<ul>';
        for (var i = 0; i < data_option.length; i++) {
            var data_i = data_option[i];
            var key = data_i['key'];
            var value = data_i['value'];
            var option_id = 'option-' + key;
            html_options = html_options + '<li style="background: ' + value + '" onclick="select_option_color(this.id' + ',\'' + parent_id + '\'' + ')" id="' + option_id + '">' + value + '</li>';
        }

        html_options = html_options + '</ul>';

        $j(id_value_div).html(html_options);
    }

}

function select_option_color(id_option, parent_id) {

    var id_main_div = '#' + parent_id;
    var text = $j('#' + id_option).text();
    var id_input = parent_id + 'input-id';
    $j('#' + id_input).val(text);
    if (parent_id == 'cd-background-select') {
        CaseDesign.update_text('textBackgroundColor', text);
    } else {
        CaseDesign.update_text('fill', text);
    }

    var id_value_div = '#' + parent_id + '-value';
    $j(id_value_div).css('display', 'none');

}

var CaseDesign = {
    init: function (cv) {
        this.canvas = cv;
    },

    addTextToCanvas: function (text_obj) {
        this.canvas.add(text_obj);
        this.canvas.setActiveObject(this.canvas.item(this.canvas.getObjects().length - 1));
        this.orderObjects();
    },

    update_text: function (key, value) {
        var text_obj = this.canvas.getActiveObject();
        if (text_obj) {
            text_obj.bringForward(true);
            text_obj.bringToFront();
            text_obj.set(key, value);
            this.canvas.renderAll();
        }
    },

    delete_text_obj: function (text_obj) {
        if (text_obj) {
            this.canvas.remove(text_obj);
        }
    },

    getItemById: function (id) {
        var object = null,
            objects = this.canvas.getObjects();
        var len = this.canvas.size();

        for (var i = 0; i < len; i++) {

            if (objects[i].myid && objects[i].myid == id) {
                object = objects[i];
                break;
            }
        }

        return object;
    },

    orderObjects: function () {

        this.canvas.getObjects().sort(function (x, y) {

            if (x.type != y.type) {
                if (x.type == 'i-text') {

                    return false;
                }

                return true;
            }
            else {
                return x.width * x.height < y.width * y.height;
            }
        });
        this.canvas.renderAll();
    }


}


function addUserTextToContainer(id) {
    if ($j('#body_added_text').css('display') == 'none') {
        $j('#body_added_text').css('display', 'block');
    }
    listUserText.push(id);
    $j('#body_added_text').append(drawItemText(id));
    activeTextDesignController(id);
    if ($j('#casedesign-button-container').css('display') == 'none') {
        $j('#casedesign-button-container').css('display', 'block');
    }

    $j('#user-text-content').focus();
    // var firstTime = localStorage.getItem("first_time_add_text");
    //
    // if(!firstTime) {
    //     localStorage.setItem("first_time_add_text", "1");
    //     showIntroduceTextTool();
    // }

}

function showIntroduceImageTool() {
    var width = $j('#active-content-container').width();
    var margin = width / 2;
    $j("#guide-introduce").css('margin-left', margin);
    $j("#background-guide").css('display', 'block');
    $j('#guide-introduce span').html('Sau đây, Soloyo xin phép được hướng dẫn cách sử dụng cách thao tác với công cụ cụ ImageTool để thiết kế ốp lưng.Quý khách chỉ có thể thao tác  với công  sau khi hướng dẫn kết thúc.');
    $j('#guide-introduce').css('display', 'inline-block').delay(5000).fadeOut('slow');
    setTimeout("showGuideDeleteImage()", 6000);
}

function showGuideDeleteImage() {
    var width = $j('#active-content-container').width();
    var margin = width - 23;
    $j('#guide-body-active-content').css('margin-left', margin);
    $j('#guide-body-active-content span').html('Bấm vào đây để xoá ảnh đã thêm');
    $j('#guide-body-active-content').css('display', 'inline-block').delay(3000).fadeOut('slow');
    setTimeout("showGuideEditImage()", 4000);
}

function showGuideEditImage() {
    var width = $j('#active-content-container').width();
    var margin = width - 93;
    $j('#guide-body-active-content').css('margin-left', margin);
    $j('#guide-body-active-content span').html('Bấm vào đây khi bắt đầu chỉnh sửa ảnh. Bút sẽ chuyển màu xanh khi bạn đang sửa ảnh');
    $j('#guide-body-active-content').css('display', 'inline-block').delay(3000).fadeOut('slow');
    $j("#background-guide").css('display', 'none');
    // var firstTimeShowSave = localStorage.getItem("first_time_show_save");
    // if(!firstTimeShowSave) {
    //     localStorage.setItem("first_time_show_save", "1");
    //     setTimeout("showGuideSaveDesignButtonForImage()",4000);
    // }
    // else{
    //     setTimeout("showGuideDesignForImage()", 4000);
    // }

}

function showGuideDesignForImage() {
    $j('#modal-guide-design-image').modal('show');
}

function showIntroduceTextTool() {
    var width = $j('#active-content-container').width();
    var margin = width / 2;
    $j("#guide-introduce").css('margin-left', margin);
    $j("#background-guide").css('display', 'block');
    $j('#guide-introduce span').html('Sau đây, Soloyo xin phép được hướng dẫn cách sử dụng cách thao tác với công cụ cụ TextTool để thiết kế ốp lưng.Quý khách chỉ có thể thao tác  với công  sau khi hướng dẫn kết thúc.');
    $j('#guide-introduce').css('display', 'inline-block').delay(5000).fadeOut('slow');
    setTimeout("showGuideBodyAddedTextDelete()", 6000);
}


function showGuideBodyAddedTextDelete() {

    var width = $j('#active-content-container').width();
    var margin = width - 13;
    $j('#guide-body-active-content').css('margin-left', margin);
    $j('#guide-body-active-content span').html('Bấm vào đây để xoá text');
    $j('#guide-body-active-content').css('display', 'inline-block').delay(3000).fadeOut('slow');
    setTimeout("showGuideBodyAddedTextEdit()", 4000);
}

function showGuideBodyAddedTextEdit() {
    var width = $j('#active-content-container').width();
    var margin = width - 53;
    $j('#guide-body-active-content').css('margin-left', margin);
    $j('#guide-body-active-content span').html('Bấm vào đây khi bắt đầu chỉnh sửa. Bút sẽ chuyển màu xanh khi bạn đang sửa Text này');
    $j('#guide-body-active-content').css('display', 'inline-block').delay(3000).fadeOut('slow');
    setTimeout("showGuideTextContent()", 4000);
}

function showGuideTextContent() {
    var width = $j('#text-group-content').width();
    var margin = width / 2;
    $j('#guide-text-group').css('margin-left', margin);
    $j('#guide-text-group span').html('Bấm vào đây để chỉnh sửa nội dung');
    $j('#guide-text-group').css('display', 'inline-block').delay(3000).fadeOut('slow');
    setTimeout("showGuideTextFont()", 4000);
}

function showGuideTextFont() {
    var width_content = $j('#text-group-content').width();
    var width_font = $j('#text-group-font').width();
    var margin = width_content + (width_font / 2);
    $j('#guide-text-group').css('margin-left', margin);
    $j('#guide-text-group span').html('Bấm vào đây để chọn font chữ kích thước chữ, độ đậm của chữ');
    $j('#guide-text-group').css('display', 'inline-block').delay(3000).fadeOut('slow');
    setTimeout("showGuideTextColor()", 4000);
}

function showGuideTextColor() {
    var width_content = $j('#text-group-content').width();
    var width_font = $j('#text-group-font').width();
    var width_color = $j('#text-group-color').width();
    var margin = width_content + width_font + (width_color / 2);
    $j('#guide-text-group').css('margin-left', margin);
    $j('#guide-text-group span').html('Bấm vào đây để thay đổi màu chữ, màu nền của chữ');
    $j('#guide-text-group').css('display', 'inline-block').delay(3000).fadeOut('slow');
    setTimeout("showGuideEditTextContent()", 4000);
}

function showGuideEditTextContent() {
    $j('#guide-user-text-content').css('display', 'inline-block').delay(3000).fadeOut('slow');

    var firstTimeShowSave = localStorage.getItem("first_time_show_save");
    if (!firstTimeShowSave) {
        localStorage.setItem("first_time_show_save", "1");
        setTimeout("showGuideSaveDesignButtonForText()", 4000);
    }
    else {
        setTimeout("showGuideDesignForText()", 4000);
    }
}

function showGuideDesignForText() {
    $j("#background-guide").css('display', 'none');
    $j('#modal-guide-design-text').modal('show');
}

function showGuideSaveDesignButtonForText() {
    var width = $j('#casedesign-button-container').width();
    var save_button_width = $j('#savedesign').width();
    var margin = width - (save_button_width / 2);
    $j('#guide-save-design').css('margin-left', margin);
    $j('#guide-save-design').css('display', 'inline').delay(3000).fadeOut('slow');
    setTimeout("showGuideDesignForText()", 4000);
}

function showGuideSaveDesignButtonForImage() {
    var width = $j('#casedesign-button-container').width();
    var save_button_width = $j('#savedesign').width();
    var margin = width - (save_button_width / 2);
    $j('#guide-save-design').css('margin-left', margin);
    $j('#guide-save-design').css('display', 'inline').delay(300).fadeOut('slow');
    setTimeout("showGuideDesignForImage()", 4000);
}

function drawItemText(id) {
    var html = '<div id="' + id + '_row" class="row-added-text" style="border-top: solid 1px #f2f3f5;padding: 5px 5px;">';
    html = html + '<span>Soloyo</span>';
    html = html + '<a onclick="deleteUserText(' + id + ')" class="a-user-text-delete" href="javascript:void(0)" style="width: 24px; height: 24px;text-decoration: none">';
    html = html + '<i style="float: right;color: #636363" class="material-icons">delete_forever</i>';
    html = html + '</a>';
    html = html + '<a id="a-user-text-edit-' + id + '" onclick="editUserText(' + id + ')" class="a-user-text-edit" href="javascript:void(0)" style="width: 24px; height: 24px;text-decoration: none">';
    html = html + '<i style="float: right;color: #636363;margin-right: 10px;"  class="material-icons">edit</i>';
    html = html + '</a>';

    html = html + '</div>';

    return html;
}

function deleteUserText(id) {

    var result = confirm("Xoá thiệt à?");
    if (result) {
        var text_obj = CaseDesign.getItemById(id);
        CaseDesign.delete_text_obj(text_obj);
        $j('#' + id + '_row').css('display', 'none');
        listUserText.splice(listUserText.indexOf(id.toString()), 1);
        if (listUserText.length == 0) {
            $j('#body_added_text').html('');
            $j('#body_added_text').css('display', 'none');
            $j('#add_text_design').css('display', 'none');
            if (($j('#uploaded-images-container').css('display') == 'none')) {
                $j('#casedesign-button-container').css('display', 'none');
                $j('.casedesign-action-after').css('display', 'none');
            }
        } else {
            var next_current_id = listUserText[0];
            editUserText(next_current_id);
        }
    }


}

function editUserText(id) {

    if ($j('#uploaded-images-container').css('display') == 'block') {
        $j('#edit-customer-uploaded-image i').css('color', '#636363');
    }

    if ($j('#add_text_design').css('display') == 'none') {
        $j('#add_text_design').css('display', 'block');
    }

    // var current_active_id = $j('#actived-text-id').val();
    //
    // if (id != current_active_id) {

    $j('.a-user-text-edit').each(function () {
        var id_element = this.id;

        $j('#' + id_element + ' i').css('color', '#636363');
    });

    $j('#a-user-text-edit-' + id + ' i').css('color', '#0bb5a2');


    $j('#actived-text-id').val(id);

    var text_obj = CaseDesign.getItemById(id);
    text_obj.bringToFront();
    text_obj.bringForward();
    CaseDesign.canvas.setActiveObject(text_obj);

    if ($j('#body_added_text').css('display') == 'none') {
        $j('#body_added_text').css('display', 'block');
    }

    if (text_obj.text) {
        $j('#user-text-content').val(text_obj.text);
    }

    if (text_obj.fontFamily) {

        $j('#text-fontfamily-select').val(text_obj.fontFamily);
    }

    if (text_obj.fontWeight) {
        $j('#user-text-weight').val(text_obj.fontWeight);
    }

    if (text_obj.fontSize) {
        $j('#user-text-size').val(text_obj.fontSize);
    }

    if (text_obj.fill) {
        if (!is_draw_text_color) {
            drawTextColor();
        }
        $j('#cd-textcolor-selectinput-id').val(text_obj.fill);
    }

    if (text_obj.textBackgroundColor) {
        if (!is_draw_background_color) {
            drawBackgroundColor();
        }
        $j('#cd-background-selectinput-id').val(text_obj.textBackgroundColor);
    }


    // }

}

function activeTextDesignController(id) {
    if ($j('#add_text_design').css('display') == 'none') {
        $j('#add_text_design').css('display', 'block');
    }
    editUserText(id);
    $j('#actived-text-id').val(id);

}

function disableAllEditTextIcon() {
    if (listUserText.length > 0) {

        $j('.a-user-text-edit').each(function () {
            var id_element = this.id;
            $j('#' + id_element + ' i').css('color', '#636363');
        });
        $j('#add_text_design').css('display', 'none');
    }
}

var is_draw_text_color = false;

function drawTextColor() {
    if (!is_draw_text_color) {
        is_draw_text_color = true;
        $j('#casedesign-textcolor').html('');
        var id = 'cd-textcolor-select';
        var title = 'Select text color';
        var html_text_color = draw_main_color(id, title);
        $j('#casedesign-textcolor').html(html_text_color);

    }

}

var is_draw_background_color = false;

function drawBackgroundColor() {
    if (!is_draw_background_color) {
        is_draw_background_color = true;
        $j('#casedesign-backgroundcolor').html('');
        var id = 'cd-background-select';
        var title = 'Select background color';
        var html_text_color = draw_main_color(id, title);
        $j('#casedesign-backgroundcolor').html(html_text_color);
    }
}


jQuery(document).ready(function () {


        $j('#btnfacebook-share').on('click', function () {
            console.log('Click on facebook share');
            console.log('url ' + campaign_url);
            console.log(' player code ' + aff_player_code);
            console.log(' player name ' + aff_player_name);
            console.log('campaign name ' + campaign_name);
            console.log('url image share ' + url_image_share);

            FB.ui({
                method: 'share_open_graph',
                action_type: 'og.shares',
                display: 'popup',
                action_properties: JSON.stringify({
                    object: {
                        'og:url': campaign_url,
                        'og:title': 'Ma trung thuong: ' + aff_player_code,
                        'og:description': aff_player_name + ' đã tham gia chương trình ' + campaign_name,
                        'og:image': url_image_share
                    }
                })
            }, function (response) {
                console.log(response);
                if (response && !response.error_message) {
                    $j.ajax({
                        url: update_after_share,
                        type: "GET",
                    });
                }
            });
        })



        $j('.casedesign-action-after').css('display', 'none');


        var canvas = new fabric.Canvas('canvas-desgin');

        var canvas_print = new fabric.Canvas('canvas-print');


        CaseDesign.init(canvas);

        canvas.on('selection:updated', function () {
            var active_object = canvas.getActiveObject();

            if ((typeof active_object == 'undefined') || active_object == null) {

            }

            if (active_object.type == 'i-text') {
                if ($j('#uploaded-images-container').css('display') == 'block') {
                    $j('#edit-customer-uploaded-image i').css('color', '#636363');
                }

                editUserText(active_object.myid);
            }
            else {

                $j('#edit-customer-uploaded-image i').css('color', '#0bb5a2');
                disableAllEditTextIcon();

            }


        });

        fabric.Image.fromURL(overlay_image_url, function (img_bg) {

            canvas.setOverlayImage(img_bg, canvas.renderAll.bind(canvas), {
                scaleX: canvas.width / img_bg.width,
                scaleY: canvas.height / img_bg.height
            });

        });

        $j('#a_add_new_text').on('click', function () {

            add_text_to_canvas();
        });

        function add_text_to_canvas() {
            var default_text = 'Soloyo';
            var newID = (new Date()).getTime().toString().substr(5);


            var text_obj = new fabric.IText(default_text, {
                left: 10,
                top: 100,
                myid: newID,
                fontFamily: 'vietnam_font',
                fontWeight: 300,
                fill: '#2d2d2d',
                fontSize: 18,
            });
            text_obj.editable = false;
            CaseDesign.addTextToCanvas(text_obj);
            //  canvas_print.add(text_obj);
            addUserTextToContainer(newID);
        }


        $j('#user-text-content').keyup(function () {
            var content = $j('#user-text-content').val();
            var current_active_id = $j('#actived-text-id').val();
            $j('#' + current_active_id + '_row span').html(content);
            CaseDesign.update_text('text', content);
        });

        $j('#text-fontfamily-select').change(function () {
            var font_family = $j(this).val();
            if (font_family == 'arial') {
                CaseDesign.update_text('fontFamily', font_family);
            }
            else {
                var myfont = new FontFaceObserver(font_family)
                myfont.load()
                    .then(function () {
                        canvas.getActiveObject().set("fontFamily", font_family);
                        canvas.requestRenderAll();
                    }).catch(function (e) {

                    alert('font loading failed ' + font_family);
                });
            }

        });


        $j('#user-text-size').change(function () {
            var text_size = $j(this).val();
            CaseDesign.update_text('fontSize', text_size);
        });

        $j('#text-style-select').change(function () {
            var style = $j(this).val();
            CaseDesign.update_text('fontStyle', style);
        });

        $j('#user-text-weight').change(function () {
            var font_weight = $j(this).val();
            CaseDesign.update_text('fontWeight', font_weight);
        });

        $j('#btn-cancel-upload').click(function () {
            hideUploadModal();
        });

        $j('#custom_image_user').on('change', function () {
            if (this.files && this.files[0]) {

                $j('#preview-customer-image').css('display', 'block');
                $j('#btn-upload-image').removeClass('button-disable');

                var reader = new FileReader();

                reader.onload = function (e) {
                    $j('#preview-customer-image').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                $j('#preview-customer-image').css('display', 'none');
                $j('#btn-upload-image').addClass('button-disable');
            }
        })


        $j('#btn-upload-image').click(function () {
            if (!$j('#btn-upload-image').hasClass('button-disable')) {
                $j('#btn-upload-image').css('display', 'none');
                $j('#btn-cancel-upload').css('display', 'none');
                $j('#tb-upload-image').css('display', 'block');
                upload_customer_image();
            }

        });

        function upload_customer_image() {

            var formdata = new FormData();
            formdata.append('custom_image_user', $j('#custom_image_user').prop('files')[0]);

            $j.ajax({
                url: customer_upload_url,
                type: "POST",
                processData: false,
                contentType: false,
                data: formdata,
                success: function (result) {

                    if (result['success'] == 1) {
                        var base_url = result['base_url'];
                        var url_file = result['url_file'];
                        var image_url = base_url + url_file;
                        hideUploadModal();
                        add_image_to_canvas(image_url);

                        $j('#start-upload-customer-image').css('display', 'none');
                        $j('#uploaded-images-container').css('display', 'block');
                        $j('#customer_uploaded_image').attr('src', image_url);
                        $j('#casedesign-button-container').css('display', 'block');
                        $j('#tb-upload-image').css('display', 'none');
                        $j('#warning-customer-image').css('display', 'block');
                        $j('#preview-customer-image').css('display', 'none');
                        $j('#btn-upload-image').addClass('button-disable');
                        disableAllEditTextIcon();
                    } else {
                        uploadImageFail();
                    }

                },
                error: function (result) {
                    uploadImageFail();
                }
            });
        }

        function uploadImageFail() {
            $j('#message-upload-customer-image').css('display', 'block');
            $j('#message-upload-customer-image').html('Tải ảnh lên không thành công. Vui lòng thử lại');
            $j('#btn-upload-image').css('display', 'inline');
            $j('#btn-cancel-upload').css('display', 'inline');
            $j('#tb-upload-image').css('display', 'none');
        }

        function add_image_to_canvas(image_url) {
            fabric.Image.fromURL(image_url, function (oImg) {
                oImg.set({
                    top: 10,
                    left: 10,

                });
                canvas.add(oImg);
                canvas.setActiveObject(canvas.item(canvas.getObjects().length - 1));

                var max_width = canvas.width * .9;
                var max_height = canvas.height * .9;
                var new_width = max_width;
                if (oImg.width < max_width) new_width = oImg.width;
                var width_ratio = new_width / oImg.width;
                var new_height = oImg.height * width_ratio;
                if (new_height > max_height) {
                    new_height = max_height;
                    var height_ratio = new_height / oImg.height;
                    new_width = oImg.width * height_ratio
                }
                canvas.getActiveObject().set({
                    scaleX: new_width / oImg.width,
                    scaleY: new_height / oImg.height
                });

                CaseDesign.orderObjects();
                // var firstTime = localStorage.getItem("first_time_add_image");
                //
                // if(!firstTime) {
                //     localStorage.setItem("first_time_add_image", "1");
                //     showIntroduceImageTool();
                // }

            });
        }

        function hideUploadModal() {
            $j('#modal_upload_image').modal('hide');
        }

        $j('#start-upload-customer-image').on('click', function () {
            $j('#btn-upload-image').css('display', 'inline');
            if (!$j('#btn-upload-image').hasClass('button-disable')) {
                $j('#btn-upload-image').addClass('button-disable');
            }
            $j('#btn-cancel-upload').css('display', 'inline');
            $j('#modal_upload_image').modal('show');
        });

        $j('#delete-customer-uploaed-image').on('click', function () {
            var result = confirm('Xoá thiệt à?');
            if (result) {
                $j('#start-upload-customer-image').css('display', 'block');
                $j('#uploaded-images-container').css('display', 'none');
                var obj = CaseDesign.canvas.getObjects();
                CaseDesign.canvas.remove(obj[0]);
                if (listUserText.length == 0) {
                    $j('#casedesign-button-container').css('display', 'none');
                    $j('.casedesign-action-after').css('display', 'none');
                    $j('#warning-customer-image').css('display', 'none');
                }
            }

        });


        $j('#text-group-content').click(function () {
            if (!checkTextGroupSelected($j(this).attr('id'))) {
                clearTextGroupSelected();
                $j(this).addClass('text-group-selected');
                clearTextGroupBody();
                $j('#text-body-content').css('display', 'block');
                $j('#user-text-content').focus();

            }
        });

        $j('#text-group-font').click(function () {
            if (!checkTextGroupSelected($j(this).attr('id'))) {
                clearTextGroupSelected();
                $j(this).addClass('text-group-selected');
                clearTextGroupBody();
                $j('#text-body-font').css('display', 'flex');
            }
        });

        $j('#text-group-color').click(function () {
            if (!checkTextGroupSelected($j(this).attr('id'))) {
                clearTextGroupSelected();
                $j(this).addClass('text-group-selected');
                clearTextGroupBody();
                $j('#text-body-color').css('display', 'flex');

                drawTextColor();

                drawBackgroundColor();


            }
        });

        $j('#text-group-function').click(function () {
            if (!checkTextGroupSelected($j(this).attr('id'))) {
                clearTextGroupSelected();
                $j(this).addClass('text-group-selected');
                clearTextGroupBody();
            }
        });

        function checkTextGroupSelected(id_clicked) {
            var selected_array = $j('.text-group-selected');
            if ((typeof  selected_array != 'undefined') && selected_array.length > 0) {
                var selected = selected_array[0];
                if (typeof selected != 'undefined' && selected.id == id_clicked) {
                    return true;
                }
            }

            return false;
        }

        function clearTextGroupSelected() {
            $j('.text-group-action').each(function () {
                $j(this).removeClass('text-group-selected');
            });
        }

        function clearTextGroupBody() {
            $j('.text-content-container').each(function () {
                $j(this).css('display', 'none');
            });
        }

        $j('#savedesign').on('click', function () {

            CaseDesign.orderObjects();
            CaseDesign.canvas.discardActiveObject().renderAll();
            save_design();
        })

        $j('#a-preview').on('click', function () {
            CaseDesign.orderObjects();
            CaseDesign.canvas.discardActiveObject().renderAll();

        })

        $j('#edit-customer-uploaded-image').on('click', function () {

            $j('#edit-customer-uploaded-image i').css('color', '#0bb5a2');

            if (listUserText.length > 0) {

                $j('.a-user-text-edit').each(function () {
                    var id_element = this.id;
                    $j('#' + id_element + ' i').css('color', '#636363');
                });
                $j('#add_text_design').css('display', 'none');
            }

            var list_object = canvas.getObjects();
            for (var i = 0; i < list_object.length; i++) {
                if (list_object[i].type == 'image') {
                    canvas.setActiveObject(list_object[i]);
                }
            }
        })


        function save_design() {

            var data_to_save = canvas.toJSON();
            var list_objects = canvas.getObjects();
            var scale_width = canvas_print.width / canvas.width;
            var scale_height = canvas_print.height / canvas.height;

            for (var i = 0; i < list_objects.length; i++) {

                var tmp_obj = list_objects[i];


                var top = tmp_obj.top;

                var left = tmp_obj.left;
                var scaleX = tmp_obj.scaleX;
                var scaleY = tmp_obj.scaleY;


                tmp_obj.set({
                    top: scale_height * top,
                    left: scale_width * left,
                    scaleX: scale_width * scaleX,
                    scaleY: scale_height * scaleY
                });
                canvas_print.add(tmp_obj);
            }

            canvas_print.renderAll();

            var dataURL = canvas_print.toDataURL('png');

            var data_send = {};
            data_send['product_id'] = product_id;
            data_send['design_json'] = JSON.stringify(data_to_save);
            data_send['image'] = dataURL;

            show_loading_save_design(true);
            $j.ajax({
                url: save_design_url,
                type: "POST",
                data: data_send,

                success: function (result) {
                    show_loading_save_design(false);
                    canvas.loadFromJSON(data_to_save);
                    $j('.casedesign-action-after').css('display', 'none');
                    if (result['status'] == 1) {
                        $j('.casedesign-action-after').css('display', 'block');
                        $j('#save-success-msg').css('display', 'block');
                        $j('#save-success-msg').html('Thiết kế của bạn đã lưu thành công. Bấm "Đặt hàng ngay" để tiến hành đặt hàng');
                        $j('#save-success-msg').delay(5000).fadeOut('slow');

                    } else {
                        $j('.casedesign-action-after').css('display', 'none');
                        $j('#save-success-msg').css('display', 'block');
                        $j('#save-success-msg').html('Thiết kế của bạn chưa lưu thành công. Vui lòng thử lại');
                        $j('#save-success-msg').delay(5000).fadeOut('slow');
                    }

                },
                error: function (result) {
                    canvas.loadFromJSON(data_to_save);
                    $j('.casedesign-action-after').css('display', 'none');
                    $j('#save-success-msg').css('display', 'block');
                    $j('#save-success-msg').html('Thiết kế của bạn chưa lưu thành công. Vui lòng thử lại');
                    $j('#save-success-msg').delay(5000).fadeOut('slow');
                    show_loading_save_design(false);
                }
            });
        }

        function show_loading_save_design(is_show) {
            if (is_show) {
                $j('#tb-loading-design').css('display', 'block');
                $j('#a-savedesign').css('display', 'none');
                $j('#a-preview').css('display', 'none');
            } else {
                $j('#tb-loading-design').css('display', 'none');
                $j('#a-savedesign').css('display', 'block');
                $j('#a-preview').css('display', 'block');
            }
        }

        function showSaveDesignButton(is_show) {

        }

        $j('#btn-next-guide-text').on('click', function () {
            $j('#img-guide-text1').css('display', 'none');
            $j('#img-guide-text2').css('display', 'inline');
            $j('#btn-next-guide-text').css('display', 'none');
            $j('#btn-back-guide-text').css('display', 'inline');
            $j('#btn-done-guide-text').css('display', 'inline');
        })

        $j('#btn-back-guide-text').on('click', function () {
            $j('#img-guide-text1').css('display', 'inline');
            $j('#img-guide-text2').css('display', 'none');
            $j('#btn-next-guide-text').css('display', 'inline');
            $j('#btn-back-guide-text').css('display', 'none');
            $j('#btn-done-guide-text').css('display', 'none');
        })

        $j('#btn-done-guide-text').on('click', function () {
            $j('#modal-guide-design-text').modal('hide');
            showStartDesignMessage();
        })

        $j('#btn-next-guide-image').on('click', function () {
            $j('#img-guide-image1').css('display', 'none');
            $j('#img-guide-image2').css('display', 'inline');
            $j('#btn-next-guide-image').css('display', 'none');
            $j('#btn-back-guide-image').css('display', 'inline');
            $j('#btn-done-guide-image').css('display', 'inline');
        })

        $j('#btn-back-guide-image').on('click', function () {
            $j('#img-guide-image1').css('display', 'inline');
            $j('#img-guide-image2').css('display', 'none');
            $j('#btn-next-guide-image').css('display', 'inline');
            $j('#btn-back-guide-image').css('display', 'none');
            $j('#btn-done-guide-image').css('display', 'none');
        })

        $j('#btn-done-guide-image').on('click', function () {
            $j('#modal-guide-design-image').modal('hide');
            showStartDesignMessage();
        })

        function showStartDesignMessage() {
            $j('#guide-introduce span').html('Hướng dẫn đã kết thúc. Bây giờ quý khách có thể thao tác với công cụ');
            $j('#guide-introduce').css('display', 'inline-block').delay(3000).fadeOut('slow');
        }


    }
);
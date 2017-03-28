<?php
Theme::asset()->add('iCheck_blue', 'public/themes/adminlte2/plugins/iCheck/square/blue.css');
// Theme::asset()->add('dataTables-bootstrap', 'public/themes/adminlte2/plugins/iCheck/all.css');
Theme::asset()->add('uploadify-css', 'public/themes/adminlte2/plugins/uploadify/uploadify.css');
Theme::asset()->add('ace-css', 'public/themes/adminlte2/plugins/css/ace.thumbnails.css');
Theme::asset()->add('datepicker3', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/css/datepicker.css');

Theme::asset()->container('footer')->add('icheck', 'public/themes/adminlte2/plugins/iCheck/icheck.min.js');
Theme::asset()->container('footer')->add('jquery-ui.min', 'http://code.jquery.com/ui/1.11.2/jquery-ui.min.js');
Theme::asset()->container('footer')->add('bootbox', 'public/themes/adminlte2/plugins/bootbox/bootbox.min.js');
Theme::asset()->container('footer')->add('validate', 'public/themes/adminlte2/plugins/jQuery/jquery.validate.min.js');
Theme::asset()->container('footer')->add('uploadify', 'public/themes/adminlte2/plugins/uploadify/jquery.uploadify.min.js');
// Theme::asset()->container('footer')->add('zclip', 'public/themes/adminlte2/plugins/zclip/jquery.zclip.js');
Theme::asset()->container('footer')->add('datepicker-bootrap-th-1', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/js/bootstrap-datepicker.js');
Theme::asset()->container('footer')->add('datepicker-bootrap-th-2', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/js/bootstrap-datepicker-thai.js');
Theme::asset()->container('footer')->add('datepicker-bootrap-th-3', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/js/locales/bootstrap-datepicker.th.js');

?>

<script type="text/javascript">
    var num_images = 0;
    $(function () {
        <?php $timestamp = time();?>
        $('#file_upload').uploadify({
            'formData'     : {
                'user_id': '<?php echo array_get($data, "id", "");?>',
                'timestamp': '<?php echo $timestamp;?>',
                'token':     '<?php echo md5(Config::get("web.ranbandokmaisod-keys") . $timestamp);?>'
            },
            'removeCompleted' : true,
            //'debug'    : true,
            'multi'    : false,
            'swf'      : '<?php echo URL::to("public/themes/adminlte2");?>/plugins/uploadify/uploadify.swf',
            'uploader' : '<?php echo URL::to("images/uploads");?>',
            'onUploadSuccess' : function(file, data, response) {
                console.log(data);
                var data      = jQuery.parseJSON(data);
                var status_code = data.status_code;
                if(status_code != '0'){
                    var error       = data.data;
                    bootbox.dialog({
                      message: error,
                      buttons: {
                        success: {
                          label: "ตกลง",
                          className: "btn-small btn-primary"
                        }
                      }
                    });
                }else if(status_code == '0'){
                    var id        = data.data.id;
                    var url       = data.data.url;
                    var code      = data.data.code;
                    var user_id   = data.data.user_id;
                    var extension = data.data.extension;

                    var url_img = '<?php echo URL::to("public");?>/uploads/'+user_id+'/'+code+'.'+extension;

                    $('#images').val('');
                    var img_upload_tag = ''+
                    '<div id="'+id+'" align="center"><ul class="ace-thumbnails">'+
                        '<li>'+
                            '<a href="javascript:void(0)">'+
                                '<img alt="200x200" src="'+ url +'" width="200" height="200">'+
                            '</a>'+
                            '<!--<div class="tools tools-bottom">'+
                                '<a href="javascript:void(0)" onclick="return image_delete(\''+id+'\',\''+code+'\', \''+user_id+'\', \''+extension+'\');" title="Delete">'+
                                    '<i class="fa fa-fw fa-trash-o"></i>'+
                                '</a>'+
                                '<a class="copy-link-wrap" href="javascript:void(0)" title="Copy Link" data="'+url_img+'">'+
                                    '<i class="fa fa-fw fa-link"></i>'+
                                '</a>'+
                            '</div>-->'+
                        '</li>'+
                    '</ul><input type="hidden" name="images[]" value="'+id+'">'+
                    '</div>';

                    $("#show_image_upload").html(img_upload_tag);

                    // $('.copy-link-wrap').zclip({
                    //     path: '<?php echo URL::to("public");?>/themes/adminlte2/plugins/zclip/ZeroClipboard.swf',
                    //     copy:function(){return $(this).attr('data');},
                    //     afterCopy:function(){
                    //         console.log('Copy Success');
                    //         // bootbox.dialog({
                    //         //   message: "Data in clipboard! '"+$(this).attr('data')+"'",
                    //         //   buttons: {
                    //         //     success: {
                    //         //       label: "ตกลง",
                    //         //       className: "btn-small btn-primary"
                    //         //     }
                    //         //   }
                    //         // });
                    //     }
                    // });

                    num_images++;
                }
                //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
            },
            'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
            } 
        });

        $('input').iCheck({
              checkboxClass: 'icheckbox_square-blue',
              radioClass: 'iradio_square-blue',
              increaseArea: '20%' // optional
        });

        jQuery.validator.addMethod("checkEmailExists", 
            function(value, element) {
                if(value == '1'){
                    return true;
                }else{
                    var isValid = true;
                    $("input").each(function() {
                       var element = $(this);
                       if (element.val() == "") {
                           isValid = false;
                       }
                    });

                    if(isValid){
                        bootbox.dialog({
                          message: "Please check box to agree!",
                          buttons: {
                            success: {
                              label: "ตกลง",
                              className: "btn-small btn-primary"
                            }
                          }
                        });
                    }

                    return false;
                }
            }, 
            " "
        );

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            // language: 'th',
        })

        $("#frm_main").validate({
            ignore: [],
            errorElement: 'span',
            errorClass: 'text-red',
            focusInvalid: true,
            rules: {
                name: {
                    'required':true,
                    'minlength': 3,
                    'maxlength': 40
                },
                email: {
                    'required':true,
                    'email': true,
                    //'checkEmailExists': true
                    'remote': {url: "<?php echo URL::to('checkmail');?>?email_old=<?php echo array_get($data, 'email', '');?>", type : "get"}
                },
                password: {
                    'required':true,
                    'minlength': 4,
                    'maxlength': 40
                },
                repassword: {
                    'required':true,
                    'equalTo': "#password"
                },
                birthday: {
                    'required': true
                },
                phone: {
                    'digits': true,
                    'minlength': 8,
                    'maxlength': 12
                },
            },
            messages: {
                // name:  "This field is required",
                // email: "This field is required",
                // password: "This field is required",
                // repassword: "This field is required",
                // agree: "",
            }
        });
    });

    function image_delete(id, code, user_id, extension){
        bootbox.confirm("คุณต้องการลบหรือไม่", function(result) {
            if(result){
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('images/deleteimages');?>",
                    data: 'id='+id+'&code='+code+'&user_id='+user_id+'&extension='+extension,
                    dataType: "json",
                    success: function(data) {
                        
                        if(data.status_code == '0')
                        {
                            num_images--;
                            if(num_images < 0){
                                $('#images').val('');
                            }

                            console.log("Success");
                            $('#'+id).remove();

<?php           if(isset($user->gender) && ($user->gender == 'female')):
                    $img_rand = rand(001, 002);
                    $img_rand = sprintf("%'.03d\n", $img_rand);
                    $img_rand = 'f'.$img_rand;
                else:  
                    $img_rand = rand(001, 003);
                    $img_rand = sprintf("%'.03d\n", $img_rand);
                    $img_rand = 'm'.$img_rand;
                endif;

                $url_avatar = URL::to('public/themes/adminlte2').'/dist/img/avatar/'.trim($img_rand).'.png';
?>
                            var url_avatar = '<?php echo $url_avatar;?>';
                            $('#images').val('');
                            var img_upload_tag = ''+
                            '<div id="'+id+'" align="center"><ul class="ace-thumbnails">'+
                                '<li>'+
                                    '<a href="javascript:void(0)">'+
                                        '<img alt="200x200" src="'+url_avatar+'" width="200" height="200">'+
                                    '</a>'+
                                '</li>'+
                            '</ul><input type="hidden" name="images" value="'+id+'">'+
                            '</div>';

                            $("#show_image_upload").html(img_upload_tag);
                            console.log("Success");
                        }else{
                            bootbox.dialog({
                              message: "Can't delete this item!",
                              buttons: {
                                success: {
                                  label: "ตกลง",
                                  className: "btn-small btn-primary"
                                }
                              }
                            });
                            console.log(data);
                        }
                        $('.order_up_down').attr("disabled", false);
                        processing = false;
                    },
                    error: function(){
                        bootbox.dialog({
                          message: "Can't delete this item!",
                          buttons: {
                            success: {
                              label: "ตกลง",
                              className: "btn-small btn-primary"
                            }
                          }
                        });
                        onsole.log("Unsuccess");
                    }
                });
            }
        });
    }
</script>

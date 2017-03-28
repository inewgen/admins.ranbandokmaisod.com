<?php
Theme::asset()->add('dataTables-bootstrap', 'public/themes/adminlte2/plugins/iCheck/all.css');
Theme::asset()->add('uploadify-css', 'public/themes/adminlte2/plugins/uploadify/uploadify.css');
Theme::asset()->add('ace-css', 'public/themes/adminlte2/plugins/css/ace.thumbnails.css');
Theme::asset()->add('icheck-css', 'public/themes/adminlte2/plugins/iCheck/all.css');
Theme::asset()->add('tagsinput-css', 'public/themes/adminlte2/plugins/jQuery-Tags-Input-master/src/jquery.tagsinput.css');
Theme::asset()->add('tagsinput-css2', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css');

Theme::asset()->container('footer')->add('jquery-ui.min', 'http://code.jquery.com/ui/1.11.2/jquery-ui.min.js');
Theme::asset()->container('footer')->add('bootbox', 'public/themes/adminlte2/plugins/bootbox/bootbox.min.js');
Theme::asset()->container('footer')->add('validate', 'public/themes/adminlte2/plugins/jQuery/jquery.validate.min.js');
Theme::asset()->container('footer')->add('uploadify', 'public/themes/adminlte2/plugins/uploadify/jquery.uploadify.min.js');
Theme::asset()->container('footer')->add('ckeditor', 'public/themes/adminlte2/plugins/ckeditor2/ckeditor.js');
/*Theme::asset()->container('footer')->add('zclip', 'public/themes/adminlte2/plugins/zclip/jquery.zclip.js');*/
Theme::asset()->container('footer')->add('tagsinput', 'public/themes/adminlte2/plugins/jQuery-Tags-Input-master/src/jquery.tagsinput.js');
Theme::asset()->container('footer')->add('icheck', 'public/themes/adminlte2/plugins/iCheck/icheck.min.js');
Theme::asset()->container('footer')->add('fastclick', 'public/themes/adminlte2/plugins/fastclick/fastclick.min.js');
?>

<script type="text/javascript">
    var num_images = <?php echo $num_image;?>;

    $(function () {

        /*iCheck for checkbox and radio inputs*/
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

        var url_to_autocomplete_api = '<?php echo URL::to("news/tags");?>';
    
        $('#tags').tagsInput({
            'autocomplete_url': url_to_autocomplete_api,
            'height':'100px',
            'width':'100%',
            'interactive':true,
            'defaultText':'add a tag',
            'delimiter': [','],
            'removeWithBackspace' : true,
            'minChars' : 0,
            'maxChars' : 0, /*if not provided there is no limit*/
            'placeholderColor' : '#666666'
        });

        CKEDITOR.config.extraPlugins = 'toolbar';
        CKEDITOR.replace( 'description', {
            /* Reset toolbar settings, so full toolbar will be generated automatically.*/
            toolbar: null,
            toolbarGroups: null,
            removeButtons: null,
            height: 400
        } );

        $("#frm_main").validate({
            ignore: [],
            errorElement: 'span',
            errorClass: 'text-red',
            focusInvalid: true,
            rules: {
                title: "required",
                sub_description: "required",
                description: "required",
                position: "required",
                status: "required",
                tags: "required",
                type: "required",
                images_code: "required",
                category_id: "required",
            },
            messages: {
                image: "This field is required",
                sub_description: "This field is required",
                description: "This field is required",
                position: "This field is required",
                status: "This field is required",
                tags: "This field is required",
                type: "This field is required",
                images_code: "This field is required",
                category_id: "This field is required",
            }
        });

        <?php $timestamp = time();?>
        $('#file_upload').uploadify({
            'formData'     : {
                'w': '200',
                'h': '143',
                'section': 'news',
                'user_id': '<?php echo $user->id;?>',
                'timestamp': '<?php echo $timestamp;?>',
                'token':     '<?php echo md5(Config::get("web.ranbandokmaisod-keys") . $timestamp);?>'
            },
            'removeCompleted' : true,
            /*'debug'    : true,*/
            'multi'    : true,
            'swf'      : '<?php echo URL::to("public/themes/adminlte2");?>/plugins/uploadify/uploadify.swf',
            'uploader' : '<?php echo URL::to("images/uploads");?>',
            'onUploadSuccess' : function(file, data, response) {
                console.log(data);
                var data      = jQuery.parseJSON(data);
                var status_code = data.status_code;
                if(status_code == '2000'){
                    alert(data.data);
                }else if(status_code != '0'){
                    var error       = data.data.message;
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
                    var url_real  = data.data.url_real;
                    var code      = data.data.code;
                    var user_id   = data.data.user_id;
                    var extension = data.data.extension;
                    num_img++;
                    ids_img[num_img] = id;

                    var w = 200;
                    var h = 143;
                    var show_hover_button = true;

                    $('#images_code').val(code);

                    var img_upload_tag = ''+
                    '<div id="'+id+'" align="center" class=""><ul class="ace-thumbnails">'+
                        '<li>'+
                            '<a href="javascript:void(0)">'+
                                '<img alt="'+w+'x'+h+'" src="'+ url +'" width="'+w+'" height="'+h+'">'+
                            '</a>';
                    if(show_hover_button){
                        img_upload_tag = img_upload_tag +
                            '<div class="tools tools-bottom">'+
                                '<a id="img-pos-left-'+id+'" onclick="positionLeft(\''+num_img+'\', \''+id+'\')" href="javascript:void(0)" title="Position Left">'+
                                    '<i class="fa fa-fw fa-arrow-left"></i>'+
                                '</a>'+
                                '<a href="javascript:void(0)" onclick="return image_delete(\''+id+'\',\''+code+'\', \''+user_id+'\', \''+extension+'\', \''+num_img+'\');" title="Delete">'+
                                    '<i class="fa fa-fw fa-trash-o"></i>'+
                                '</a>'+
                                '<a class="copy-link-wrap" id="copy_link_'+num_images+'" href="javascript:void(0)" title="Copy Link" onclick="copyLinkWrap(\''+url_real+'\')">'+
                                    '<i class="fa fa-fw fa-link"></i>'+
                                '</a>'+
                                '<a id="img-pos-right-'+id+'" onclick="positionRight(\''+num_img+'\', \''+id+'\')" href="javascript:void(0)" title="Position Right">'+
                                    '<i class="fa fa-fw fa-arrow-right"></i>'+
                                '</a>'+
                            '</div>';
                    }   
                        img_upload_tag = img_upload_tag +
                        '</li>'+
                    '</ul><input type="hidden" name="images['+num_images+']" value="'+id+'">'+
                    '</div>';

                    $("#show_image_upload").append(img_upload_tag);

                    // $('#copy_link_'+num_images).click(function() {
                    //     var url_real = $(this).attr('data');
                        
                    //     bootbox.dialog({
                    //       message: "<input id='image_link' type='text' size='85' value='"+$(this).attr('data')+"'/>",
                    //       buttons: {
                    //         success: {
                    //           label: "ตกลง",
                    //           className: "btn-small btn-primary"
                    //         }
                    //       }
                    //     });

                    //     $("#image_link").click(function() {
                    //         $(this).select();
                    //     });
                    // });

                    num_images++;
                }
            },
            'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
            } 
        });
        
    //     $('.copy-link-wrap').click(function() {
    //         var url_real = $(this).attr('data');
            
    //         bootbox.dialog({
    //           message: "<input id='image_link' type='text' size='85' value='"+$(this).attr('data')+"'/>",
    //           buttons: {
    //             success: {
    //               label: "ตกลง",
    //               className: "btn-small btn-primary"
    //             }
    //           }
    //         });

    //         $("#image_link").click(function() {
    //             $(this).select();
    //         });
    //     });
    });

    var ids_img = [];
    var num_img = 0;
<?php if(isset($news['images']) && is_array($news['images'])):?>
<?php $i = 0; $j = 0; ?>
<?php foreach ($news['images'] as $key => $image): ?>
    ids_img[<?php echo $i; ?>] = '<?php echo $image['id'];?>';
    num_img = '<?php echo $j;?>';
<?php $i++; $j++; ?>
<?php endforeach;?>
<?php endif;?>

    function positionLeft(position, id)
    {
        if (position > 0) {
            var position_left = parseInt(position) - 1;
            var id_left = ids_img[position_left];
            ids_img[position] = id_left;
            ids_img[position_left] = id;

            // Left box
            $('#' + id).attr('class', 'image-remove');
            var images_box = $('#' + id).html();
            images_box = '<div id="'+id+'" class="">' + images_box + '</div>';
            $(images_box).insertBefore('#' + id_left);
            $('.image-remove').remove();

            // Left box
            $('#img-pos-left-'+id).attr('onclick', 'positionLeft('+position_left+', '+id+')');
            $('#img-pos-right-'+id).attr('onclick', 'positionRight('+position_left+', '+id+')');

            // Right box
            $('#img-pos-left-'+id_left).attr('onclick', 'positionLeft('+position+', '+id_left+')');
            $('#img-pos-right-'+id_left).attr('onclick', 'positionRight('+position+', '+id_left+')');
        } else {
            bootbox.dialog({
              message: "ตำแหน่งนี้ไม่สามารถย้ายได้",
              buttons: {
                success: {
                  label: "ตกลง",
                  className: "btn-small btn-primary"
                }
              }
            });
        }
    }

    function copyLinkWrap(url_real)
    {  
        bootbox.dialog({
          message: "<input id='image_link' type='text' size='85' value='"+url_real+"'/>",
          buttons: {
            success: {
              label: "ตกลง",
              className: "btn-small btn-primary"
            }
          }
        });

        $("#image_link").click(function() {
            $(this).select();
        });
    }

    function positionRight(position, id)
    {
        if (position < num_img) {
            var position_right = parseInt(position) + 1;
            var id_right = ids_img[position_right];
            ids_img[position] = id_right;
            ids_img[position_right] = id;

            // Left box
            $('#' + id).attr('class', 'image-remove');
            var images_box = $('#' + id).html();
            images_box = '<div id="'+id+'" class="">' + images_box + '</div>';
            $(images_box).insertAfter('#' + id_right);
            $('.image-remove').remove();

            // Left box
            $('#img-pos-right-'+id).attr('onclick', 'positionRight('+position_right+', '+id+')');
            $('#img-pos-left-'+id).attr('onclick', 'positionLeft('+position_right+', '+id+')');

            // Right box
            $('#img-pos-right-'+id_right).attr('onclick', 'positionRight('+position+', '+id_right+')');
            $('#img-pos-left-'+id_right).attr('onclick', 'positionLeft('+position+', '+id_right+')');
        } else {
            bootbox.dialog({
              message: "ตำแหน่งนี้ไม่สามารถย้ายได้",
              buttons: {
                success: {
                  label: "ตกลง",
                  className: "btn-small btn-primary"
                }
              }
            });
        }
    }

    function image_delete(id, code, user_id, extension, position){
        bootbox.confirm("คุณต้องการลบหรือไม่", function(result) {
            if(result){
                console.log("Success");
                if(num_images == 1){
                    $('#images_code').val('');
                }
                num_images--;
                console.log("Success");
                $('#'+id).remove();

                var ids_img2 = [];
                var j = 0;
                for (var i = 0; i < ids_img.length; i++) {
                    if (ids_img[i] != id) {
                        ids_img2[j] = ids_img[i];

                        $('#img-pos-right-'+ids_img2[j]).attr('onclick', 'positionRight('+j+', '+ids_img2[j]+')');
                        $('#img-pos-left-'+ids_img2[j]).attr('onclick', 'positionLeft('+j+', '+ids_img2[j]+')');
                        j++;
                    }
                };

                ids_img = ids_img2;
                console.log(ids_img);
                /* $.ajax({
                //     type: "GET",
                //     url: "<?php echo URL::to('images/delete');?>",
                //     data: 'id='+id+'&code='+code+'&user_id='+user_id+'&extension='+extension,
                //     dataType: "json",
                //     success: function(data) {
                        
                //         if(data.status_code == '0')
                //         {   
                //             if(num_images == 1){
                //                 $('#images_code').val('');
                //             }
                //             num_images--;
                //             console.log("Success");
                //             $('#'+id).remove();
                //         }else{
                //             var message_i = data.status_txt;
                //             bootbox.dialog({
                //               message: message_i,
                //               buttons: {
                //                 success: {
                //                   label: "ตกลง",
                //                   className: "btn-small btn-primary"
                //                 }
                //               }
                //             });
                //             console.log(data);
                //         }
                //         $('.order_up_down').attr("disabled", false);
                //         processing = false;
                //     },
                //     error: function(){
                //         bootbox.dialog({
                //           message: "Can't delete this item!",
                //           buttons: {
                //             success: {
                //               label: "ตกลง",
                //               className: "btn-small btn-primary"
                //             }
                //           }
                //         });
                //         console.log("Unsuccess");
                //     }
                // });*/
            }
        });
    }
</script>

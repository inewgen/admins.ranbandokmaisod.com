<?php
Theme::asset()->add('dataTables-bootstrap', 'public/themes/adminlte2/plugins/iCheck/all.css');
Theme::asset()->add('bootstrap3-wysihtml5', 'public/themes/adminlte2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');
Theme::asset()->add('uploadify-css', 'public/themes/adminlte2/plugins/uploadify/uploadify.css');
Theme::asset()->add('ace-css', 'public/themes/adminlte2/plugins/css/ace.thumbnails.css');

Theme::asset()->container('footer')->add('jquery-ui.min', 'http://code.jquery.com/ui/1.11.2/jquery-ui.min.js');
Theme::asset()->container('footer')->add('bootstrap3-wysihtml5.all.min', 'public/themes/adminlte2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');
Theme::asset()->container('footer')->add('bootbox', 'public/themes/adminlte2/plugins/bootbox/bootbox.min.js');
Theme::asset()->container('footer')->add('validate', 'public/themes/adminlte2/plugins/jQuery/jquery.validate.min.js');
Theme::asset()->container('footer')->add('uploadify', 'public/themes/adminlte2/plugins/uploadify/jquery.uploadify.min.js');
?>

<script type="text/javascript">
    var num_images = 0;
    $(function () {
        <?php $timestamp = time();?>
        $('#file_upload').uploadify({
            'formData'     : {
                'w': '200',
                'section': 'banners',
                'user_id': '<?php echo $user->id;?>',
                'timestamp': '<?php echo $timestamp;?>',
                'token':     '<?php echo md5(Config::get("web.ranbandokmaisod-keys") . $timestamp);?>'
            },
            'removeCompleted' : true,
            /*'debug'    : true,*/
            'multi'    : false,
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

                    var w = '100%';
                    var show_hover_button = false;

                    $('#images_code').val(code);

                    var img_upload_tag = ''+
                    '<div id="'+id+'" align="center"><ul class="ace-thumbnails">'+
                        '<li>'+
                            '<a href="javascript:void(0)">'+
                                '<img alt="'+w+'" src="'+ url +'" width="'+w+'" height="">'+
                            '</a>';
                    if(show_hover_button){
                        img_upload_tag = img_upload_tag +
                            '<div class="tools tools-bottom">'+
                                '<a href="javascript:void(0)" onclick="return image_delete(\''+id+'\',\''+code+'\', \''+user_id+'\', \''+extension+'\');" title="Delete">'+
                                    '<i class="fa fa-fw fa-trash-o"></i>'+
                                '</a>'+
                                '<a class="copy-link-wrap" id="copy_link_'+num_images+'" href="javascript:void(0)" title="Copy Link" data="'+url_real+'">'+
                                    '<i class="fa fa-fw fa-link"></i>'+
                                '</a>'+
                            '</div>';
                    }   
                        img_upload_tag = img_upload_tag +
                        '</li>'+
                    '</ul><input type="hidden" name="images['+num_images+']" value="'+id+'">'+
                    '</div>';

                    $("#show_image_upload").html(img_upload_tag);

                    if(show_hover_button){
                    $('#copy_link_'+num_images).click(function() {
                        var url_real = $(this).attr('data');
                        
                        bootbox.dialog({
                          message: "<input id='image_link' type='text' size='85' value='"+$(this).attr('data')+"'/>",
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
                    });
                    }

                    num_images++;
                }
            },
            'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
            } 
        });
        
        /*
        $('.copy-link-wrap').click(function() {
            var url_real = $(this).attr('data');
            
            bootbox.dialog({
              message: "<input id='image_link' type='text' size='85' value='"+$(this).attr('data')+"'/>",
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
        });*/

        $("#frm_main").validate({
            ignore: [],
            errorElement: 'span',
            errorClass: 'text-red',
            focusInvalid: true,
            rules: {
                // position: "required",
<?php if(!isset($banners['images']) || !is_array($banners['images'])):?>
                images: "required",
<?php endif;?>
            },
            messages: {
                // position: "This field is required",
<?php if(!isset($banners['images']) || !is_array($banners['images'])):?>
                images: "This field is required",
<?php endif;?>
            }
        });
    });
</script>

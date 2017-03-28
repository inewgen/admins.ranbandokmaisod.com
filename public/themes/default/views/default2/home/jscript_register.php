<?php
Theme::asset()->add('iCheck_blue', 'public/themes/adminlte2/plugins/iCheck/square/blue.css');
Theme::asset()->add('datepicker3', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/css/datepicker.css');

Theme::asset()->container('footer')->add('icheck', 'public/themes/adminlte2/plugins/iCheck/icheck.min.js');
Theme::asset()->container('footer')->add('validate', 'public/themes/adminlte2/plugins/jQuery/jquery.validate.min.js');
Theme::asset()->container('footer')->add('bootbox', 'public/themes/adminlte2/plugins/bootbox/bootbox.min.js');
Theme::asset()->container('footer')->add('datepicker-bootrap-th-1', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/js/bootstrap-datepicker.js');
Theme::asset()->container('footer')->add('datepicker-bootrap-th-2', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/js/bootstrap-datepicker-thai.js');
Theme::asset()->container('footer')->add('datepicker-bootrap-th-3', 'public/themes/adminlte2/plugins/bootstrap-datepicker-thai-thai/js/locales/bootstrap-datepicker.th.js');

?>

<script type="text/javascript">
	$(function () {
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
                    if($('#agree').is(':checked')){
                        isValid = false;
                    }

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
                        return false;
                    }

                    return true;
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
                    'remote': {url: "<?php echo URL::to('checkmail');?>", type : "get"}
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
                phone: {
                    'digits': true,
                    'minlength': 8,
                    'maxlength': 12
                },
                birthday: {
                    'required': true
                },
            	agree: {
                    // 'required':true,
                    'checkEmailExists': true
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
</script>

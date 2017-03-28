<?php
Theme::asset()->add('iCheck_blue', 'public/themes/adminlte2/plugins/iCheck/square/blue.css');

Theme::asset()->container('footer')->add('icheck', 'public/themes/adminlte2/plugins/iCheck/icheck.min.js'); 
Theme::asset()->container('footer')->add('validate', 'public/themes/adminlte2/plugins/jQuery/jquery.validate.min.js');
?>

<script type="text/javascript">
	$(function () {
		$('input').iCheck({
			  checkboxClass: 'icheckbox_square-blue',
			  radioClass: 'iradio_square-blue',
			  increaseArea: '20%' // optional
		});

		$("#frm_login").validate({
            ignore: [],
            errorElement: 'span',
            errorClass: 'text-red',
            focusInvalid: true,
            rules: {
                email: {
                    'required':true,
                    'email': true
                },
            	password: "required"
            },
            messages: {
                // email: {"This field is required"},
                password: "This field is required",
            }
        });
	});
</script>

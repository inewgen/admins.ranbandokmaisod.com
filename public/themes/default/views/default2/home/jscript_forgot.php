<?php
Theme::asset()->add('iCheck_blue', 'public/themes/adminlte2/plugins/iCheck/square/blue.css');

Theme::asset()->container('footer')->add('icheck', 'public/themes/adminlte2/plugins/iCheck/icheck.min.js');
Theme::asset()->container('footer')->add('validate', 'public/themes/adminlte2/plugins/jQuery/jquery.validate.min.js');
Theme::asset()->container('footer')->add('bootbox', 'public/themes/adminlte2/plugins/bootbox/bootbox.min.js');
?>

<script type="text/javascript">
	$(function () {

		$("#frm_register").validate({
            ignore: [],
            errorElement: 'span',
            errorClass: 'text-red',
            focusInvalid: true,
            rules: {
                email: {
                    'required':true,
                    'email': true,
                    //'checkEmailExists': true
                    // 'remote': {url: "<?php echo URL::to('checkmail');?>", type : "get"}
                }
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

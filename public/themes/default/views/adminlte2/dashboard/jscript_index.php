<?php
Theme::asset()->add('flat-blue', 'public/themes/adminlte2/plugins/iCheck/flat/blue.css');
Theme::asset()->add('morris', 'public/themes/adminlte2/plugins/morris/morris.css');
Theme::asset()->add('jvectormap', 'public/themes/adminlte2/plugins/jvectormap/jquery-jvectormap-1.2.2.css');
Theme::asset()->add('datepicker3', 'public/themes/adminlte2/plugins/datepicker/datepicker3.css');
Theme::asset()->add('daterangepicker-bs3', 'public/themes/adminlte2/plugins/daterangepicker/daterangepicker-bs3.css');
Theme::asset()->add('bootstrap3-wysihtml5', 'public/themes/adminlte2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');

Theme::asset()->container('footer')->add('jquery-ui.min', 'http://code.jquery.com/ui/1.11.2/jquery-ui.min.js');
Theme::asset()->container('footer')->add('raphael-min', 'http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js');
Theme::asset()->container('footer')->add('morris', 'public/themes/adminlte2/plugins/morris/morris.min.js');
Theme::asset()->container('footer')->add('jquery.sparkline.min', 'public/themes/adminlte2/plugins/sparkline/jquery.sparkline.min.js');
Theme::asset()->container('footer')->add('jquery-jvectormap-1.2.2.min', 'public/themes/adminlte2/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');
Theme::asset()->container('footer')->add('jquery-jvectormap-world-mill-en', 'public/themes/adminlte2/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');
Theme::asset()->container('footer')->add('jquery.knob', 'public/themes/adminlte2/plugins/knob/jquery.knob.js');
Theme::asset()->container('footer')->add('daterangepicker', 'public/themes/adminlte2/plugins/daterangepicker/daterangepicker.js');
Theme::asset()->container('footer')->add('bootstrap-datepicker', 'public/themes/adminlte2/plugins/datepicker/bootstrap-datepicker.js');
Theme::asset()->container('footer')->add('bootstrap3-wysihtml5.all.min', 'public/themes/adminlte2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');
Theme::asset()->container('footer')->add('icheck.min', 'public/themes/adminlte2/plugins/iCheck/icheck.min.js');
Theme::asset()->container('footer')->add('dashboard', 'public/themes/adminlte2/dist/js/pages/dashboard.js');
?>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script type="text/javascript">
    $.widget.bridge('uibutton', $.ui.button);
</script>
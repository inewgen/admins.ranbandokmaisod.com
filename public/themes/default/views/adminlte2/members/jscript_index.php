<?php
Theme::asset()->add('dataTables-bootstrap', 'public/themes/adminlte2/plugins/datatables/dataTables.bootstrap.css');
Theme::asset()->add('bootstrap3-wysihtml5', 'public/themes/adminlte2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');

Theme::asset()->container('footer')->add('jquery.dataTables', 'public/themes/adminlte2/plugins/datatables/jquery.dataTables.js');
Theme::asset()->container('footer')->add('dataTables.bootstrap', 'public/themes/adminlte2/plugins/datatables/dataTables.bootstrap.js');
Theme::asset()->container('footer')->add('jquery-ui.min', 'http://code.jquery.com/ui/1.11.2/jquery-ui.min.js');
Theme::asset()->container('footer')->add('bootstrap3-wysihtml5.all.min', 'public/themes/adminlte2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');
Theme::asset()->container('footer')->add('bootbox', 'public/themes/adminlte2/plugins/bootbox/bootbox.min.js');
?>

<script type="text/javascript">
  $(function () {

    $('.del_rows').click(function() {
        var id = $(this).data('id');

        bootbox.confirm("Are you sure to delete?", function(result) {
            if(result){
                $('#id').val(id);
                $('#action').val('delete');
                $('#frm_main2').attr('method', 'post');
                $('#frm_main2').submit();
            }
        });
    });

    $('#tb_main th').click(function() {
        var order = $(this).data('order');
        if(order!='' && order != undefined){
            var oldClass = $(this).attr('class');
            var orderCmd = 'desc';
            var newClass = 'sorting';

            if(oldClass == 'sorting'){
                orderCmd = 'desc';
                newClass = 'sorting_desc';
            }else if(oldClass == 'sorting_desc'){
                orderCmd = 'asc';
                newClass = 'sorting_asc';
            }else{
                orderCmd = 'desc';
                newClass = 'sorting_desc';
            }

            //$('#table-sort th').attr('class', 'sorting');
            $('#order').val(order);
            $('#sort').val(orderCmd);
            $(this).attr('class', newClass);
            $('#frm_main').submit();
        }
    });

    $('#perpage').change(function () {
        $('#frm_main').submit();
    });

  });

</script>

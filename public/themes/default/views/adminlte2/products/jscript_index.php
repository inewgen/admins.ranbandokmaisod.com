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

    $('#tb_products').dataTable( {
        'bPaginate':true,
        'bInfo':false,
        // 'bFilter': false ,
        "asStripClasses": null,
        "aoColumns": [
          { "bSortable": false },
          { "bSortable": false },
          { "bSortable": false },
          { "bSortable": false },
          { "bSortable": false },
          { "bSortable": false },
          { "bSortable": false },
          { "bSortable": false },
          { "bSortable": false },
        ]
    });

    $("#tb_products_filter input").keyup(function(e) {
        var search = $(this).val();
        if(search != ''){
            $("#tb_products tbody").sortable('disable');
        }else{
            $("#tb_products tbody").sortable('enable');
        }
    });

    $("#tb_products tbody").sortable({
        placeholder: "ui-state-highlight",
        connectWith: '.table table-bordered table-hover',
        opacity: 0.6,
        cursor: "move",
        update: function(event, ui) {
        },
        start : function(event, ui){
            ui.item.startPos = ui.item.index();
        },
        stop : function(event, ui){
            $('#action').val('order');
            $('#frm_products').submit();
        }
    })
    .droppable({
        over: function() { isInside = true;},
        out: function() { isInside = false;},
        accept: "tr"
    });
    $( "#tb_products tbody" ).disableSelection();

  });

    function del_products(id, images, member_id){
        $('#action').val('delete');
        $('#id').val(id);
        $('#images').val(images);
        $('#member_id').val(member_id);
        bootbox.confirm("Are you sure to delete?", function(result) {
            if(result){
                $('#frm_products').submit();
            }
        });
    }
</script>

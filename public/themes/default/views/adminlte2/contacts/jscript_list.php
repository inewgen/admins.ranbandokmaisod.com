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
function ajaxUpdate(id, status, btn_status){
    $.ajax({
        type: "GET",
        url: "<?php echo URL::to('contacts/ajaxupdate');?>",
        data: 'action=edit&id='+id+'&status='+status,
        dataType: "json",
        success: function(data) {
            
            if(data.status_code == '0')
            {   
                $(btn_status).html(btn_status_txt);
                // Message
                var msg = "Update successful.";
                swal("Success!", msg, "success");
                toastr["success"](msg);

                console.log("Success");
            }else{
                // Message
                var msg = "Can't update this item!";
                swal("Error!", msg, "error");
                toastr["error"](msg);
                console.log(data);
            }
        },
        error: function(){
            // Message
            var msg = "Can't update this item!";
            swal("Error!", msg, "error");
            toastr["error"](msg);
            console.log("Unsuccess");
        }
    });
}
</script>
<script type="text/javascript">
// Page Loader
  $(window).load(function () {
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-center",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    <?php checkAlertMessageFlash();?>
  });

  $(function () {
    $('.btn_status').click(function() {
        var id   = $(this).data('id');
        var btn_status = $(this);
        bootbox.dialog({
          message: "Please select one status ("+id+")",
          title: "Custom contacts status",
          buttons: {
            danger: {
              label: "New",
              className: "btn-danger",
              callback: function() {
                status = 1;
                btn_status_txt = '<small class="label pull-right bg-red">New</small>';
                ajaxUpdate(id, status, btn_status);
              }
            },
            warning: {
              label: "Read",
              className: "btn-warning",
              callback: function() {
                status = 2;
                btn_status_txt = '<small class="label pull-right bg-yellow">Read</small>';
                ajaxUpdate(id, status, btn_status);
              }
            },
            info: {
              label: "Active",
              className: "btn-info",
              callback: function() {
                status = 3;
                btn_status_txt = '<small class="label pull-right bg-blue">Active</small>';
                ajaxUpdate(id, status, btn_status);
              }
            },
            success: {
              label: "Close",
              className: "btn-success",
              callback: function() {
                status = 0;
                btn_status_txt = '<small class="label pull-right bg-green">Close</small>';
                ajaxUpdate(id, status, btn_status);
              }
            }
          }
        });
    });

    $('.del_rows').click(function() {
        var id   = $(this).data('id');
        var code = $(this).data('code');
        var user_id = $(this).data('uid');
        var images_id = $(this).data('iid');

        bootbox.confirm("Are you sure to delete?", function(result) {
            if(result){
                $('#id').val(id);
                $('#code').val(code);
                $('#user_id').val(user_id);
                $('#images_id').val(images_id);
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

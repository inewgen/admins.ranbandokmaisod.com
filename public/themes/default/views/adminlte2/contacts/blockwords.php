<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Blockwords Comments
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Blockwords Comments
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <!-- Check alert message -->
        <?php checkAlertMessage(); ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <!-- <h3 class="box-title">List Comments</h3> -->
                        <a href="javascript:void(0)">
                            <button class="btn btn-success" id="blockwords_comments">
                                <i class="fa fa-plus"></i>
                                Filter comment
                            </button>
                        </a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="frm_main" role="form" method="get" action="<?php echo URL::to('comments/blockwords');?>" enctype="multipart/form-data">
                            
                            <div class="row">
                                <div class="col-xs-6">
                                    <div id="tb_comments_length" class="dataTables_length">
                                        <label>
                                            <select id="perpage" name="perpage" size="1" aria-controls="tb_comments">

                                    <?php   $perpage_arr = array('5', '10', '25', '50', '100'); 
                                            foreach ($perpage_arr as $value) { ?>
                                                <option value="<?php echo $value;?>"<?php echo ($param['perpage'] == $value ? ' selected="selected"':'');?>><?php echo $value;?></option>        
                                    <?php   } ?>

                                            </select> <strong>records per page</strong></label>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="dataTables_filter">
                                        <label><strong>Search:</strong>
                                            <input type="text" name="s" value="<?php echo array_get($param, 's', '');?>">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- <table id="tb_main" class="table table-bordered table-striped"> -->
                            <table id="tb_main" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                <?php   foreach ($table_title as $key => $value):
                                            if(($value[1] == 1) || ($value[1] == 2) || ($value[1] == 3) || ($value[1] == 4)):?>
                                        <th data-order="<?php echo $key;?>" class="<?php if(isset($param['order']) && $param['order']== $key ):?>sorting_<?php echo isset($param['sort']) ? $param['sort'] : 'desc';?><?php else: ?>sorting<?php endif;?>">
                                            <a href="#">
                                                <?php echo $value[0];?>
                                            </a>
                                        </th>
                                <?php       else: ?>
                                        <th><?php echo $value[0];?></th>
                                <?php       endif;
                                        endforeach;?>
                                    </tr>
                                </thead>
                                <tbody>

                                <tr>
                                <?php   foreach ($table_title as $key => $value):
                                            if($value[1] == 1):?>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" placeholder="<?php echo $value[0];?>" value="<?php echo array_get($param, $key, '');?>" name="<?php echo $key;?>" class="seller-input width-80" id="<?php echo $key;?>_search" maxlength="10" size="10">
                                            </div>
                                        </td>
                                <?php       elseif($value[1] == 2):?>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control input-sm width-100" name="status">
                                                    <option value=""<?php echo array_get($param, $key, '') == ''?' selected="selected"':'';?>>ทั้งหมด</option>
                                                    <option value="1"<?php echo array_get($param, $key, '') == '1'?' selected="selected"':'';?>>New</option>
                                                    <option value="2"<?php echo array_get($param, $key, '') == '2'?' selected="selected"':'';?>>Completed</option>
                                                    <option value="3"<?php echo array_get($param, $key, '') == '3'?' selected="selected"':'';?>>Blocked</option>
                                                </select>
                                            </div>
                                        </td>
                                <?php       elseif($value[1] == 3):?>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control input-sm width-100" name="blockwords_type">
                                                    <option value=""<?php echo array_get($param, $key, '') == ''?' selected="selected"':'';?>>ทั้งหมด</option>
                                                    <option value="1"<?php echo array_get($param, $key, '') == '1'?' selected="selected"':'';?>>Blocked</option>
                                                    <option value="2"<?php echo array_get($param, $key, '') == '2'?' selected="selected"':'';?>>Warning</option>
                                                </select>
                                            </div>
                                        </td>
                                <?php       elseif($value[1] == 4):?>
                                        <td></td>
                                <?php       else: ?>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-info text-center">
                                                <i class="ace-icon fa fa-search bigger-20"></i>
                                            </button>
                                        </td>
                                <?php       endif;
                                        endforeach;?>
                                </tr>

    <?php $i = 1; ?>
    <?php foreach ($data as $key => $value): ?>
                                    <tr>
                    <?php   foreach ($table_title as $key2 => $value2): ?>
                    <?php       if ($key2 == 'images'):?>
                    <?php           if(array_get($value, 'images.0.url', false)): ?>
                                        <td>
                                            <img src="<?php echo getImageLink('images', array_get($value, 'images.0.user_id', ''), array_get($value, 'images.0.code', ''), array_get($value, 'images.0.extension', ''), 200, 143, array_get($value, 'images.0.name', ''));?>" width="200px">
                                        </td>
                    <?php           else: ?>
                                        <td>
                                            <img src="<?php echo getImageLink('img', 'comments', 'siamts', 'png', 200, 143, 'banner.jpg');?>" width="200px">
                                        </td>
                    <?php           endif;?>
                    <?php       elseif ($key2 == 'manage'): ?>
                                        <td>
                                            <a title="Edit" href="<?php echo URL::to('comments');?>/<?php echo array_get($value, 'id', '');?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                            <!-- <a class="del_rows" data-id="<?php echo array_get($value, 'id', '');?>" data-iid="<?php echo array_get($value, 'images.0.id', '');?>" data-code="<?php echo array_get($value, 'images.0.code', '');?>" data-uid="<?php echo array_get($value, 'images.0.user_id', '');?>" title="Delete" href="javascript:void(0)"><span class="glyphicon glyphicon-remove-sign danger"></span></a> -->
                                        </td>
                    <?php       else: ?>
                                        <td><?php echo array_get($value ,$key2, '');?></td>
                    <?php       endif;?>
                    <?php   endforeach;?> 
                    
                                    </tr>
    <?php $i++;?>
    <?php endforeach;?>
    <?php if ($num_rows == 0): ?>
        <tr><td colspan="<?php echo count($table_title);?>" align="center">Data not found</td></tr>
    <?php endif;?>

                                </tbody>
    <?php if ($num_rows > 0): ?>
                                <tfoot>
                                    <tr>

                                <?php   foreach ($table_title as $key => $value): ?>
                                        <th><?php echo $value[0];?></th>
                                <?php   endforeach;?>
                                    </tr>
                                </tfoot>
    <?php endif;?>
                            </table>

                            <?php if(isset($pagination)): ?>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="dataTables_info" id="tb_comments_info">Showing <?php echo $pagination->getFrom();?>
                                    to <?php echo $pagination->getTo();?>
                                    of <?php echo $pagination->getTotal();?> entries
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <ul class="pagination">
                                            <?php if(isset($param) && !empty($param)): ?>
                                            <?php echo $pagination->appends($param)->links();?>
                                            <?php else: ?>
                                            <?php echo $pagination->links();?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endif;?>

                            <input name="order" id="order" value="<?php echo $param['order'];?>" type="hidden">
                            <input name="sort" id="sort" value="<?php echo $param['sort'];?>" type="hidden">
                        </form>

                        <form id="frm_main2" role="form" method="post" action="<?php echo URL::to('comments');?>" enctype="multipart/form-data">
                            <input name="action" id="action" value="" type="hidden">
                            <input name="id" id="id" value="" type="hidden">
                            <input name="referer" id="referer" value="<?php echo $_SERVER['REQUEST_URI'];?>" type="hidden">
                        </form>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper
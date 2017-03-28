Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            List News
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                List News
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">

        <!-- Message Success -->
        <?php if($success = Session::has('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <i class="icon fa fa-check"></i>
            <?php echo (null !== Session::get('success')) ? Session::get('success') : '';?>       
        </div>
        <?php endif; ?>

        <!-- Message Error -->
        <?php if($error = Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <i class="icon fa fa-ban"></i>
            <?php echo (null !== Session::get('error')) ? Session::get('error') : '';?>
        </div>
        <?php endif; ?>

        <!-- Message Warning -->
        <?php if($error = Session::has('warning')): ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <i class="icon fa fa-warning"></i>
            <?php echo (null !== Session::get('warning')) ? Session::get('warning') : '';?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <!-- <h3 class="box-title">List Banner</h3> -->
                        <a href="<?php echo URL::to('news/add');?>">
                            <button class="btn btn-success" id="new_news">
                                <i class="fa fa-plus"></i>
                                Add News
                            </button>
                        </a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="frm_news" role="form" method="post" action="<?php echo URL::to('news');?>" enctype="multipart/form-data">
                            <table id="tb_news" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Reference</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Created</th>
                                        <th>Updated</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>

    <?php $i = 1; ?>
    <?php foreach ($news as $key => $value): ?>
                                    <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $value['title'];?></td>
                                        <td>
    <?php if($images = array_get($value, 'images', array())):?>
                                            <img src="<?php echo array_get($value, 'images.0.url', '');?>" width="200px">
    <?php endif;?>
                                        </td>
                                        <td><?php echo $value['reference'];?></td>
                                        <td><?php echo $value['categories']['title'];?></td>
                                        <td>

                                    <?php if($value['type'] == '2'): ?>
                                            <i class="fa fa-comments bg-blue" title="Hightlight"></i>
                                    <?php else: ?>
                                            <i class="fa fa-user bg-yellow" title="General"></i>
                                    <?php endif;?>
                         
                                        </td>
                                        <td><?php echo $value['created_at'];?></td>
                                        <td><?php echo $value['updated_at'];?></td>
                                        <td>
                                            <a title="Edit" href="<?php echo URL::to('news');?>/<?php echo $value['id'];?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                            <a class="del_news" data="<?php echo $value['id'];?>" title="Delete" href="javascript:void(0)" onclick="del_news('<?php echo array_get($value, 'id', 0);?>', '<?php echo array_get($value, 'images.0.ids', 0);?>', '<?php echo array_get($value, 'member_id', 0);?>')">
                                                <span class="glyphicon glyphicon-remove-sign danger"></span>
                                            </a>

    <?php foreach($images as $key_i => $value_i): ?>
                                            <input name="images_old[<?php echo $value_i['ids'];?>][]" id="" value="<?php echo $value_i['code'];?>" type="hidden">
                                            <input name="extension[<?php echo $value_i['ids'];?>][<?php echo $value_i['code'];?>]" id="" value="<?php echo $value_i['extension'];?>" type="hidden">
    <?php endforeach; ?>
                                            <input name="id_sel[]" id="id_sel_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" type="hidden">
                                        </td>
                                    </tr>
    <?php $i++;?>
    <?php endforeach;?>

                                </tbody>

                            </table>

                            <input name="action" id="action" value="order" type="hidden">
                            <input name="id" id="id" value="" type="hidden">
                            <input name="images" id="images" value="" type="hidden">
                            <input name="member_id" id="member_id" value="1" type="hidden">
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
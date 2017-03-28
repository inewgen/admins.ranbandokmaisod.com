Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            List Navigations
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                List Navigations
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
                        <!-- <h3 class="box-title">List Navigations</h3> -->
                        <a href="<?php echo URL::to('navigations/add');?>">
                            <button class="btn btn-success" id="new_banner">
                                <i class="fa fa-plus"></i>
                                New banner
                            </button>
                        </a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="frm_navigations" role="form" method="post" action="<?php echo URL::to('navigations');?>" enctype="multipart/form-data">
                            <table id="tb_navigations" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Title</th>
                                        <th>Url</th>
                                        <th>Status</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>

    <?php if(isset($navigations['data']['pagination']['total']) && ($navigations['data']['pagination']['total'] > 0)):?>
    <?php if(isset($navigations['data']['record']) && is_array($navigations['data']['record'])): ?>
    <?php $i = 1; ?>
    <?php foreach ($navigations['data']['record'] as $key => $value): ?>
                                    <tr>
                                        <td><?php echo $value['position'];?></td>
                                        <td><?php echo $value['title'];?></td>
                                        <td><?php echo $value['url'];?></td>
                                        <td><?php echo array_get($value, 'status', '');?></td>
                                        <td>
                                            <a title="Edit" href="<?php echo URL::to('navigations');?>/<?php echo $value['id'];?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                            <a class="del_banner" data="<?php echo array_get($value, 'id', '');?>" title="Delete" href="javascript:void(0)" onclick="del_banner('<?php echo array_get($value, 'id', '');?>', '<?php echo array_get($value, 'images.0.code', '');?>')"><span class="glyphicon glyphicon-remove-sign danger"></span></a>
                                        
                                            <input name="id_sel[]" id="id_sel" value="<?php echo $value['id'];?>" type="hidden">
                                        </td>
                                    </tr>
    <?php $i++;?>
    <?php endforeach;?>
    <?php endif;?>
    <?php endif;?>
                                </tbody>
                                <!-- <tfoot>
                                    <tr>
                                        <th>No.</th>
                                        <th>Title</th>
                                        <th>Subtitle</th>
                                        <th>Button</th>
                                        <th>Button_title</th>
                                        <th>Button_url</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Manage</th>
                                    </tr>
                                </tfoot> -->
                            </table>

                            <input name="action" id="action" value="order" type="hidden">
                            <input name="id" id="id" value="" type="hidden">
                            <input name="image_name" id="image_name" value="" type="hidden">
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
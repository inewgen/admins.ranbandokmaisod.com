<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Navigations
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Edit Navigations
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
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Navigations Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('navigations');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" placeholder="" name="title" class="form-control" value="<?php echo htmlspecialchars($navigations['title']);?>">
                            </div>
                            <div class="form-group">
                                <label for="">Position</label>
                                <input type="text" placeholder="" name="position" class="form-control" value="<?php echo $navigations['position'];?>">
                            </div>
                            <div class="form-group">
                                <label for="">Url</label>
                                <input type="text" placeholder="" name="url" class="form-control" value="<?php echo $navigations['url'];?>">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" value="1" <?php if($navigations['status'] == '1'):?>checked="checked"<?php endif;?>> <strong>Check me banner status on</strong>
                                </label>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="image_old" value="<?php echo array_get($navigations, 'images.0.code', '');?>">
                        <input type="hidden" name="id" value="<?php echo array_get($navigations, 'id', '');?>">
                        <input type="hidden" name="user_id" value="<?php echo $user->id;?>">

                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Categories
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Categories
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
                        <h3 class="box-title">Categories Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('categories/add');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" placeholder="" name="title" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <input type="text" placeholder="" name="description" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Parent level</label>
                                <input type="text" placeholder="" name="parent_id" class="form-control" value="0">
                            </div>
                            <div class="form-group">
                                <label for="">Position</label>
                                <input type="text" placeholder="" name="position" class="form-control" value="0">
                            </div>
                            <div class="form-group">
                                <label for="">Type</label>
                                <select name="type" class="form-control">
                                    <option value="4" selected="selected">Products</option>
                                    <option value="1">Banners</option>
                                    <option value="2">News</option>
                                    <option value="3">Pages</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" value="1" checked="checked"> <strong>Check me categories status on</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Image Categories</label>
                                <!-- <input type="file" name="image" id="image"> --> 
                                <input placeholder="" class="form-control" id="file_upload" name="file_upload" type="file" multiple="true">
                                <p class="help-block"></p>
                                <!-- <input type="hidden" name="images" id="images" value=""> -->
                                <div id="show_image_upload" style="hight:150"><input type="hidden" name="images" value=""></div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

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
<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Banner
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Edit Banner
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
                        <h3 class="box-title">Banner Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('banners');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" placeholder="" name="title" class="form-control" value="<?php echo htmlspecialchars($banners['title']);?>">
                            </div>
                            <div class="form-group">
                                <label for="">Subtitle</label>
                                <input type="text" placeholder="" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($banners['subtitle']);?>">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="button" value="1" <?php if($banners['button'] == '1'):?>checked="checked"<?php endif;?>> <strong>Check me button status on</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="">Button title</label>
                                <input type="text" placeholder="" name="button_title" class="form-control" value="<?php echo htmlspecialchars($banners['button_title']);?>">
                            </div>
                            <div class="form-group">
                                <label for="">Button url</label>
                                <input type="text" placeholder="" name="button_url" class="form-control" value="<?php echo $banners['button_url'];?>">
                            </div>
                
                            <div class="form-group">
                                <label for="">Position</label>
                                <input type="text" placeholder="" name="position" class="form-control" value="<?php echo $banners['position'];?>">
                            </div>
                            <div class="form-group">
                                <label for="">Type</label>
                                <select name="type" class="form-control">
                                    <option value="1" <?php if($banners['type'] == '1'):?>selected="selected"<?php endif;?>>Siamit web banner</option>
                                    <option value="2" <?php if($banners['type'] == '2'):?>selected="selected"<?php endif;?>>Adds banner</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" value="1" <?php if($banners['status'] == '1'):?>checked="checked"<?php endif;?>> <strong>Check me banner status on</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Image Banner</label>
                                <!-- <input type="file" name="image" id="image"> --> 
                                <input placeholder="" class="form-control" id="file_upload" name="file_upload" type="file" multiple="true">
                                <p class="help-block">Dimensions 1440 × 500 only</p>
                                <!-- <input type="hidden" name="images" id="images" value=""> -->
                                <div id="show_image_upload" style="hight:150">
                                    <img src="<?php echo getImageLink('image', array_get($banners, 'images.0.user_id', '1'), array_get($banners, 'images.0.code', '1'), array_get($banners, 'images.0.extension', 'jpg'), 1440, 500, array_get($banners, 'images.0.name', 'siamit.jpg'));?>" width="100%">
                                </div>
                                
                                <input name="images_old[user_id]" value="<?php echo array_get($banners, 'images.0.user_id', '');?>" type="hidden">
                                <input name="images_old[id]" value="<?php echo array_get($banners, 'images.0.id', '');?>" type="hidden">
                                <input name="images_old[code]" value="<?php echo array_get($banners, 'images.0.code', '');?>" type="hidden">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="image_old" value="<?php echo array_get($banners, 'images.0.code', '');?>">
                        <input type="hidden" name="id" value="<?php echo array_get($banners, 'id', '');?>">

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
<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Pages
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Pages
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
                        <h3 class="box-title">Pages Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('pages/add');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Title <font color="red">*</font></label>
                                <input type="text" placeholder="" name="title" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Sub Description <font color="red">*</font></label>
                                <input type="text" placeholder="" name="sub_description" class="form-control" maxlength="250">
                            </div>
                            <div class="form-group">
                                <label for="">Tags <font color="red">*</font></label>
                                <input type="text" placeholder="" name="tags" id="tags" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Position <font color="red">*</font></label>
                                <input type="text" placeholder="" name="position" class="form-control" value="0">
                            </div>
                            <div class="form-group">
                                <label for="">Status <font color="red">*</font></label>
                                <input type="text" placeholder="" name="status" class="form-control" value="1">
                            </div>
                            <div class="form-group">
                                <label for="">Reference</label>
                                <input type="text" placeholder="" name="reference" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Reference URLs</label>
                                <input type="text" placeholder="" name="reference_url" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Category <font color="red">*</font></label>
                                <select name="category_id" class="form-control">
                            <?php if(isset($categories) && is_array($categories)):?>
                            <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                            <?php endforeach;?>
                            <?php endif;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Type <font color="red">*</font></label>
                                <select name="type" class="form-control">
                                    <option value="1" selected="selected">ทั่วไป</option>
                                    <option value="2">Hightlight</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Socials Share</label>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="minimal"/>
                                </label>
                                <label>
                                    &nbsp;Share with Facebook.
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="">Description <font color="red">*</font></label>
                                <textarea name="description" class="textarea" placeholder="Place some text here" style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                </textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Images <font color="red">*</font></label>
                                <input placeholder="" class="form-control" id="file_upload" name="file_upload" type="file" multiple="true">
                                <input type="hidden" name="images_code" id="images_code" value="">
                                <div id="show_image_upload" style="hight:150">      
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

                        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                        <input type="hidden" name="ids" value="<?php echo $ids;?>">
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
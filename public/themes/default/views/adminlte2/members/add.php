<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Members
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Members
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Message Success -->
        <?php if($success = Session::has('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4>
                <i class="icon fa fa-check"></i>
                <?php echo (null !== Session::get('success')) ? Session::get('success') : '';?>
            </h4>         
        </div>
        <?php endif; ?>

        <!-- Message Error -->
        <?php if($error = Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4>
                <i class="icon fa fa-ban"></i>
                <?php echo (null !== Session::get('error')) ? Session::get('error') : '';?>
            </h4>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Members Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('members/add');?>" enctype="multipart/form-data">
                        <div class="box-body">
                    
                            <div class="form-group has-feedback">
                                <label for="">Name <font color="red">*</font></label>
                                <input name="name" id="name" type="text" class="form-control" placeholder="Full name" />
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="">Email <font color="red">*</font></label>
                                <input name="email" id="email" type="text" class="form-control" placeholder="Email" />
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="">Password <font color="red">*</font></label>
                                <input name="password" id="password" type="password" class="form-control" placeholder="Password" />
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="">Retype password <font color="red">*</font></label>
                                <input name="repassword" id="repassword" type="password" class="form-control" placeholder="Retype password" />
                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="">Birth Day <font color="red">*</font></label>
                                <input name="birthday" id="birthday" type="text" class="form-control datepicker input-medium" placeholder="Birth Day" data-date-language="th-th"/>
                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="">Phone number</label>
                                <input name="phone" id="phone" type="text" class="form-control" placeholder="Phone number" />
                                <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Gender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <label class="">
                                    <div class="iradio_minimal-blue" style="position: relative;" aria-checked="false" aria-disabled="false">
                                        <input type="radio" checked="checked" class="minimal" name="gender" style="position: absolute; opacity: 0;" value="male">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;">
                                        </ins>
                                    </div>
                                </label>
                                <label class="">
                                    Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </label>
                                <label class="">
                                    <div class="iradio_minimal-blue" style="position: relative;" aria-checked="false" aria-disabled="false">
                                        <input type="radio" class="minimal" name="gender" style="position: absolute; opacity: 0;" value="female">
                                        <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;">
                                        </ins>
                                    </div>
                                </label>
                                <label class="">
                                    Female
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="">Role</label>
                                <select name="role[]" class="form-control">
                                    <option value="1">Administrator</option>
                                    <option value="2">Modulator</option>
                                    <option value="3" selected="selected">General</option>
                                </select>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Photo</label>
                                <input name="photo" placeholder="Photo from facebook or google" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Facebook ID</label>
                                <input name="uid_fb" placeholder="Facebook ID" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Facebook Link</label>
                                <input name="link_fb" placeholder="Facebook Link" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Google ID</label>
                                <input name="uid_g" placeholder="Google ID" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Google Link</label>
                                <input name="link_g" placeholder="Google Link" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Locale</label>
                                <input name="locale" placeholder="Local" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Time zone</label>
                                <input name="timezone" placeholder="Time zone" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Active code</label>
                                <input name="active" placeholder="Active code" type="text" class="form-control input-medium"/>
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="">Status</label>
                                <input name="status" placeholder="Status" type="text" class="form-control input-medium" value="1" />
                                <span class="glyphicon glyphicon-link form-control-feedback"></span>
                            </div>
                           
                            <!-- <div class="form-group">
                                <label for="">Images</label>
                                <input placeholder="" class="form-control" id="file_upload" name="file_upload" type="file" multiple="true">
                                <input type="hidden" name="images" id="images" value="">
                                <div id="show_image_upload" style="hight:150"></div>
                            </div> -->
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

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
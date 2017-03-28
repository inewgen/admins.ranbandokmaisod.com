<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Comments
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Comments
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Check alert message -->
        <?php checkAlertMessage(); ?>

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Comments Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('comments/add');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Name <font color="red">*</font></label>
                                <input type="text" placeholder="" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Email <font color="red">*</font></label>
                                <input type="text" placeholder="" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Message <font color="red">*</font></label>
                                <input type="text" placeholder="" name="message" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Message2</label>
                                <input type="text" placeholder="" name="message2" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">User ID</label>
                                <input type="text" placeholder="" name="user_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Commentable_type <font color="red">*</font></label>
                                <select name="commentable_type" class="form-control">
                                    <option value="news" selected="selected">News</option>
                                    <option value="pages">Pages</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Commentable_id <font color="red">*</font></label>
                                <input type="text" placeholder="" name="commentable_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Number</label>
                                <input type="text" placeholder="" name="number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Status <font color="red">*</font></label>
                                <select name="status" class="form-control">
                                    <option value="1" selected="selected">ON</option>
                                    <option value="0">OFF</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">IP <font color="red">*</font></label>
                                <input type="text" placeholder="" name="ip" class="form-control" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">
                            </div>
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
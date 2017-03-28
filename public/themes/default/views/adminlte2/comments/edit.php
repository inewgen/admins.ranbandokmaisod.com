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
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('comments');?>" enctype="multipart/form-data">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="">Name <font color="red">*</font></label>
                                <input type="text" placeholder="" name="name" class="form-control" value="<?php echo array_get($data, 'name', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Email <font color="red">*</font></label>
                                <input type="text" placeholder="" name="email" class="form-control" value="<?php echo array_get($data, 'email', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Message <font color="red">*</font></label>
                                <input type="text" placeholder="" name="message" class="form-control" value="<?php echo array_get($data, 'message', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Message2</label>
                                <input type="text" placeholder="" name="message2" class="form-control" value="<?php echo array_get($data, 'message2', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">User ID</label>
                                <input type="text" placeholder="" name="user_id" class="form-control" value="<?php echo array_get($data, 'user_id', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Commentable_type <font color="red">*</font></label>
                                <select name="commentable_type" class="form-control">
                                    <option value="news"<?php if(array_get($data, 'commentable_type', '') == 'news'):?> selected="selected"<?php endif;?>>News</option>
                                    <option value="pages"<?php if(array_get($data, 'commentable_type', '') == 'pages'):?> selected="selected"<?php endif;?>>Pages</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Commentable_id <font color="red">*</font></label>
                                <input type="text" placeholder="" name="commentable_id" class="form-control" value="<?php echo array_get($data, 'commentable_id', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Number</label>
                                <input type="text" placeholder="" name="number" class="form-control" value="<?php echo array_get($data, 'number', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Status <font color="red">*</font></label>
                                <select name="status" class="form-control">
                                    <option value="1"<?php if(array_get($data, 'status', '') == '1'):?> selected="selected"<?php endif;?>>ON</option>
                                    <option value="0"<?php if(array_get($data, 'status', '') == '0'):?> selected="selected"<?php endif;?>>OFF</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">IP <font color="red">*</font></label>
                                <input type="text" placeholder="" name="ip" class="form-control" value="<?php echo array_get($data, 'ip', '');?>">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

						<input type="hidden" name="action" value="edit">
                        <input type="hidden" name="user_id" value="<?php echo array_get($data, 'user_id', '');?>">
                        <input type="hidden" name="referer" value="comments/<?php echo array_get($data, 'id', '');?>">
                        <input type="hidden" name="id" value="<?php echo array_get($data, 'id', '');?>">
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
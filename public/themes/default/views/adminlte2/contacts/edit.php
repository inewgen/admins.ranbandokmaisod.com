<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Contacts
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Contacts
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
                        <h3 class="box-title">Contacts Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('contacts');?>" enctype="multipart/form-data">
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
                                <label for="">Mobile <font color="red">*</font></label>
                                <input type="text" placeholder="" name="mobile" class="form-control" value="<?php echo array_get($data, 'mobile', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Message</label>
                                <input type="text" placeholder="" name="message" class="form-control" value="<?php echo array_get($data, 'message', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">User ID</label>
                                <input type="text" placeholder="" name="user_id" class="form-control" value="<?php echo array_get($data, 'user_id', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Status <font color="red">*</font></label>
                                <select name="status" class="form-control">
                                    <option value="1"<?php if(array_get($data, 'status', '') == '1'):?> selected="selected"<?php endif;?>>New</option>
                                    <option value="2"<?php if(array_get($data, 'status', '') == '2'):?> selected="selected"<?php endif;?>>Read</option>
                                    <option value="3"<?php if(array_get($data, 'status', '') == '3'):?> selected="selected"<?php endif;?>>Active</option>
                                    <option value="0"<?php if(array_get($data, 'status', '') == '0'):?> selected="selected"<?php endif;?>>Close</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">IP <font color="red">*</font></label>
                                <input type="text" placeholder="" name="ip" class="form-control" value="<?php echo array_get($data, 'ip', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">URL</label>
                                <input type="text" placeholder="" name="url" class="form-control" value="<?php echo array_get($data, 'url', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Note</label>
                                <input type="text" placeholder="" name="note" class="form-control" value="<?php echo array_get($data, 'note', '');?>">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

						<input type="hidden" name="action" value="edit">
                        <input type="hidden" name="user_id" value="<?php echo array_get($data, 'user_id', '');?>">
                        <input type="hidden" name="referer" value="contacts/<?php echo array_get($data, 'id', '');?>">
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
<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Blockwords
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Blockwords
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
                        <h3 class="box-title">Blockwords Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('blockwords');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" placeholder="" name="title" class="form-control" value="<?php echo array_get($data, 'title', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <input type="text" placeholder="" name="description" class="form-control" value="<?php echo array_get($data, 'description', '');?>">
                            </div>
                             <div class="form-group">
                                <label for="">Type</label>
                                <select name="type" class="form-control">
                                    <option value="1"<?php if(array_get($data, 'type', '') == '1'):?> selected="selected"<?php endif;?>>Blocked</option>
                                    <option value="2"<?php if(array_get($data, 'type', '') == '2'):?> selected="selected"<?php endif;?>>Warning</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1"<?php if(array_get($data, 'status', '') == '1'):?> selected="selected"<?php endif;?>>ON</option>
                                    <option value="0"<?php if(array_get($data, 'status', '') == '0'):?> selected="selected"<?php endif;?>>OFF</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

						<input type="hidden" name="action" value="edit">
                        <input type="hidden" name="user_id" value="<?php echo array_get($data, 'user_id', '');?>">
                        <input type="hidden" name="referer" value="blockwords/<?php echo array_get($data, 'id', '');?>">
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
<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Quotes
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Quotes
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
 
		<?php checkAlertMessage(); ?>

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Quotes Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('quotes');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" placeholder="" name="title" class="form-control" value="<?php echo array_get($data, 'title', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea rows="5" name="description" class="form-control"><?php echo array_get($data, 'description', '');?></textarea>
                                <!-- <input type="text" placeholder="" name="description" class="form-control" value="<?php echo array_get($data, 'description', '');?>"> -->
                            </div>
                            <div class="form-group">
                                <label for="">Author</label>
                                <input type="text" placeholder="" name="author" class="form-control" value="<?php echo array_get($data, 'author', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Position</label>
                                <input type="text" placeholder="" name="position" class="form-control" value="<?php echo array_get($data, 'position', '');?>">
                            </div>
                            <div class="form-group">
                                <label for="">Type</label>
                                <select name="type" class="form-control">
                                    <option value="1"<?php if(array_get($data, 'type', '') == '1'):?> selected="selected"<?php endif;?>>General</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>
                                	<input name="status" value="0" type="hidden" />
                                    <input type="checkbox" name="status" value="1"<?php if(array_get($data, 'status', '') == '1'):?> checked="checked"<?php endif;?>> <strong>Check me quotes status on</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Image Quotes</label>
                                <!-- <input type="file" name="image" id="image"> --> 
                                <input placeholder="" class="form-control" id="file_upload" name="file_upload" type="file" multiple="true">
                                <p class="help-block"></p>
                                <!-- <input type="hidden" name="images" id="images" value=""> -->
                                <div id="show_image_upload" style="hight:150">
                                    <img src="<?php echo getImageLink('image', array_get($data, 'images.0.user_id', '1'), array_get($data, 'images.0.code', '1'), array_get($data, 'images.0.extension', 'jpg'), 200, 143, array_get($data, 'images.0.name', 'ranbandokmaisod.jpg'));?>" width="200">
                                </div>
                                <input name="images_old[user_id]" value="<?php echo array_get($data, 'images.0.user_id', '');?>" type="hidden">
                                <input name="images_old[id]" value="<?php echo array_get($data, 'images.0.id', '');?>" type="hidden">
                                <input name="images_old[code]" value="<?php echo array_get($data, 'images.0.code', '');?>" type="hidden">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button class="btn btn-danger" type="reset">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>

						<input type="hidden" name="action" value="edit">
                        <input type="hidden" name="user_id" value="<?php echo array_get($data, 'user_id', '');?>">
                        <input type="hidden" name="referer" value="quotes/<?php echo array_get($data, 'id', '');?>">
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
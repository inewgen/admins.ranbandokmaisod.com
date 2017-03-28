<div class="content-wrapper" style="min-height: 948px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Navigations
            <!-- <small>advanced tables</small> -->
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li>
                Add Navigations
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
                        <h3 class="box-title">Navigations Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="frm_main" role="form" method="post" action="<?php echo URL::to('navigations/add');?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input type="text" placeholder="" name="title" class="form-control">
                            </div>
                            <!-- <div class="form-group">
                                <label for="">Subtitle</label>
                                <input type="text" placeholder="" name="subtitle" class="form-control">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="button" value="1" checked="checked"> <strong>Check me button status on</strong>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="">Button title</label>
                                <input type="text" placeholder="" name="button_title" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Button url</label>
                                <input type="text" placeholder="" name="button_url" class="form-control">
                            </div> -->
                            <div class="form-group">
                                <label for="">Position</label>
                                <input type="text" placeholder="" name="position" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Url</label>
                                <input type="text" placeholder="" name="url" class="form-control">
                            </div>
                            <!-- <div class="form-group">
                                <label for="">Type</label>
                                <select name="type" class="form-control">
                                    <option value="1" selected="selected">Siamit web banner</option>
                                    <option value="2">Adds banner</option>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" value="1" checked="checked"> <strong>Check me navigation status on</strong>
                                </label>
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
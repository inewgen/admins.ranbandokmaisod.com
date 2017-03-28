<div class="register-box">
    <div class="register-logo">
        <a href="<?php echo URL::to('/');?>"><img src="<?php echo getLogo(140, 40);?>"/></a>
    </div>

    <div class="register-box-body">

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
        
<?php if(!Session::has('success')): ?>
        <p class="login-box-msg">Reset password</p>

        <form action="<?php echo URL::to('forgot/password');?>" method="post" id="frm_register" enctype="multipart/form-data">
            <div class="form-group has-feedback">
                <input name="newpassword" id="newpassword" type="password" class="form-control" placeholder="New Password" value="" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="newpassword_confirmation" id="newpassword_confirmation" type="password" class="form-control" placeholder="Retype new password" value="" />
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            
            <div class="row">
                <div class="col-xs-4">
                    <div class="checkbox icheck">
                        <label>
                            <!-- <input name="agree" id="agree" type="checkbox" value="1"> I agree to the <a href="<?php echo URL::to('policy');?>">terms</a> -->
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <div class="checkbox icheck">
                        <label>
                            <!-- <input name="agree" id="agree" type="checkbox" value="1"> I agree to the <a href="<?php echo URL::to('policy');?>">terms</a> -->
                        </label>
                    </div>
                </div>
            </div>

            <input name="email" type="hidden" value="<?php echo array_get($data, 'email', '');?>">
            <input name="id" type="hidden" value="<?php echo array_get($data, 'id', '');?>">
            <input name="token" type="hidden" value="<?php echo array_get($data, 'active', '');?>">
        </form>
<?php endif;?>
<?php if(Session::has('success')): ?>
        <div class="row">
            <div class="col-xs-12">
                <a href="<?php echo URL::to('login');?>">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Go to sing in page</button>
                </a>
            </div>
        </div>
        <div class="social-auth-links text-center">
            <!-- <p>- OR -</p> -->
            <!-- <a href="<?php echo URL::to('login/fb');?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using Facebook</a> -->
            <!-- <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign up using Google+</a> -->
        </div>
<?php endif;?>
        <!-- <a href="<?php echo URL::to('login');?>" class="text-center">I already have a membership</a> -->
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
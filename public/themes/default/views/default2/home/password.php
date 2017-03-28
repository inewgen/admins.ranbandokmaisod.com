<div class="register-box">
    <div class="register-logo">
        <a href="<?php echo URL::to('/');?>"><img src="<?php echo URL::to('public/themes/adminlte2');?>/images/ranbandokmaisod_logo_140x40.png"/></a>
    </div>

    <div class="register-box-body">

<?php if(Session::has('message')): ?>
         <p class="login-box-msg"><font color="green"><?php echo (null !== Session::get('message')) ? Session::get('message') : '';?></font></p>
<?php endif; ?>
<?php if(Session::has('error')): ?>
         <p class="login-box-msg"><font color="red"><?php echo (null !== Session::get('error')) ? Session::get('error') : '';?></font></p>
<?php endif; ?>

        <p class="login-box-msg">Change password</p>
        <form action="<?php echo URL::to('profile/password');?>" method="post" id="frm_register" enctype="multipart/form-data">

            <div class="form-group has-feedback">
                <input name="password" id="password" type="password" class="form-control" placeholder="Old Password" value="" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
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
        </form>

        <div class="social-auth-links text-center">
            <!-- <p>- OR -</p> -->
            <!-- <a href="<?php echo URL::to('login/fb');?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using Facebook</a> -->
            <!-- <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign up using Google+</a> -->
        </div>

        <!-- <a href="<?php echo URL::to('login');?>" class="text-center">Go to login page</a> -->
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
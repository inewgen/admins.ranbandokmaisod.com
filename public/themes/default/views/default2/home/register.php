<div class="register-box">
    <div class="register-logo">
        <a href="<?php echo URL::to('/');?>"><img src="<?php echo URL::to('public/themes/adminlte2');?>/images/ranbandokmaisod_logo_140x40.png"/></a>
    </div>

    <div class="register-box-body">

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

        <p class="login-box-msg">Register a new membership</p>
        <form action="<?php echo URL::to('register');?>" method="post" id="frm_main" enctype="multipart/form-data">
            <div class="form-group has-feedback">
                <input name="name" id="name" type="text" class="form-control" placeholder="Full name" />
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="email" id="email" type="text" class="form-control" placeholder="Email" />
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="password" id="password" type="password" class="form-control" placeholder="Password" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="repassword" id="repassword" type="password" class="form-control" placeholder="Retype password" />
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="birthday" id="birthday" type="text" class="form-control datepicker input-medium" placeholder="&nbsp;&nbsp;&nbsp;Birth Day" data-date-language="th-th"/>
                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="phone" id="phone" type="text" class="form-control" placeholder="Phone number" />
                <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
            </div>
			<div class="form-group">
                <label class="">
                    Gender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </label>
				<label class="">
					<div class="iradio_minimal-blue" style="position: relative;" aria-checked="false" aria-disabled="false">
						<input type="radio" checked="" class="minimal" name="gender" style="position: absolute; opacity: 0;" value="male">
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
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input name="agree" type="hidden" value="0">
                            <input name="agree" id="agree" type="checkbox" value="1"> I agree to the <a href="<?php echo URL::to('policy');?>">terms</a>
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="<?php echo URL::to('fbauth');?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using Facebook</a>
            <a href="<?php echo URL::to('gauth');?>" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign up using Google+</a>
        </div>

        <a href="<?php echo URL::to('login');?>" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
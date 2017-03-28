<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo URL::to('/');?>"><img src="<?php echo getLogo(140, 40);?>"/></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
<!-- Message Success -->
<?php if($success = Session::has('success')): ?>
<div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h5>
        <i class="icon fa fa-check"></i>
        <?php echo (null !== Session::get('success')) ? Session::get('success') : '';?>
    </h5>         
</div>
<?php elseif (isset($user->status) && ($user->status == '0')): ?>
<div class="alert alert-warning alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <i class="icon fa fa-warning"></i>
    Please verify in your email, We send email to <?php echo isset($user->email)?$user->email:'';?>
</div>
<?php elseif(isset($user->status) && ($user->status == '1')): ?>
<div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h5>
        <i class="icon fa fa-check"></i>
        Your email verify already! Please Sign in
    </h5>         
</div>
<?php endif; ?>

<!-- Message Error -->
<?php if($error = Session::has('error')): ?>
<div class="alert alert-danger alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    <h5>
        <i class="icon fa fa-ban"></i>
        <?php echo (null !== Session::get('error')) ? Session::get('error') : '';?>
    </h5>
</div>
<?php endif; ?>

<?php if(empty($user)): ?>
        <p class="login-box-msg">Sign in to start your fun.</p>
        <form action="<?php echo URL::to('login');?>" method="post" id="frm_login" enctype="multipart/form-data">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Email" name="email" />
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" value="1"> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="<?php echo URL::to('login/fb');?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
            <!-- <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a> -->
        </div>
        <!-- /.social-auth-links -->

        <a href="<?php echo URL::to('forgot');?>">I forgot my password</a>
        <br>
        <a href="<?php echo URL::to('register');?>" class="text-center">Register a new membership</a>
<?php else: ?>
        <form action="<?php echo URL::to('login');?>" method="post" id="frm_login" action="<?php echo URL::to('login');?>" enctype="multipart/form-data">
            <div class="form-group has-feedback" align="center">
            <h1>Hello, <?php echo isset($user->name)?$user->name:''; ?></h1>
<?php       if(isset($user->images[0]->url)): ?>
            <img src="<?php echo getImageProfile($user, 200, 200);?>" alt=""/>
<?php       else:?>
<?php           if(isset($user->gender) && ($user->gender == 'female')):
                    $img_rand = rand(001, 002);
                    $img_rand = sprintf("%'.03d\n", $img_rand);
                    $img_rand = 'f'.$img_rand;
                else:  
                    $img_rand = rand(001, 003);
                    $img_rand = sprintf("%'.03d\n", $img_rand);
                    $img_rand = 'm'.$img_rand;
                endif;
?>
            <img src="<?php echo getImageLink('img', 'avatar', $img_rand, 'png', 200, 200, 'avatar.jpg');?>" alt=""/>
<?php       endif;?>
            <div class="form-group has-feedback">
                <?php echo isset($user->email)?$user->email:''; ?>
            </div>
            <!-- <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Re-password" name="repassword" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div> -->
            <div class="social-auth-links text-center">
                <?php if(isset($user->status) && ($user->status == '1')): ?>
                <!-- <a href="<?php echo URL::to('');?>" class="btn btn-block btn-social btn-primary btn-flat"><i class="fa fa-sign-in"></i> Go to Dashboard</a> -->
                <a href="<?php echo URL::to('logout');?>" class="btn btn-block btn-social btn-primary btn-flat"><i class="fa fa-sign-in"></i> Go to Sign in</a>
                <?php elseif(isset($user->status) && ($user->status == '0')): ?>
                <a href="<?php echo URL::to('register/verify/sendmail?email='.$user->email);?>" class="btn btn-block btn-social btn-warning btn-flat"><i class="fa fa-envelope"></i> Send an email again</a>
                <a href="<?php echo URL::to('logout');?>" class="btn btn-block btn-social btn-primary btn-flat"><i class="fa fa-sign-in"></i> Go to Sign in</a>
                <?php endif;?>
            </div>
            </div>
        </form>
<?php endif; ?>


    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
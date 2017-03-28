<div class="register-box">
    <div class="register-logo">
        <a href="<?php echo URL::to('/');?>"><img src="<?php echo getLogo(140, 40);?>"/></a>
    </div>

    <div class="register-box-body">

        <!-- Message Success -->
        <?php if($success = Session::has('success')): ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <i class="icon fa fa-warning"></i>
            <?php echo (null !== Session::get('success')) ? Session::get('success') : '';?>
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

        <p class="login-box-msg">Forgot Password</p>
        <form action="<?php echo URL::to('forgot');?>" method="post" id="frm_register" enctype="multipart/form-data">
            <?php if(!Session::has('success')): ?>
            <div class="form-group has-feedback">
                <input name="email" id="email" type="text" class="form-control" placeholder="Email" />
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Send email for reset password</button>
                </div>
                <!-- /.col -->
            </div>
            <?php endif;?>
        </form>
        <?php if(Session::has('success')): ?>
        <div class="row">
            <div class="col-xs-12">
                <a href="<?php echo URL::to('login');?>">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Go to sing in page</button>
                </a>
            </div>
        </div>
        <?php endif;?>
        <!-- <a href="<?php echo URL::to('');?>" class="text-center">Go to sign in page</a> -->
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
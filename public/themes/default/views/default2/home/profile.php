<div class="register-box">
    <div class="register-logo">
        <a href="<?php echo URL::to('/');?>">
            <img src="<?php echo getLogo(140, 40);?>"/>
        </a>
    </div>

    <div class="register-box-body">

        <!-- Message Success -->
        <?php if($success = Session::has('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h5>
                <i class="icon fa fa-check"></i>
                <?php echo (null !== Session::get('success')) ? Session::get('success') : '';?>
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

        <p class="login-box-msg">Profile of membership</p>
        <form action="<?php echo URL::to('profile/edit');?>" method="post" id="frm_register" enctype="multipart/form-data">
            <div class="form-group has-feedback">
                <input name="name" id="name" type="text" class="form-control" placeholder="Full name" value="<?php echo array_get($data, 'name', '');?>" />
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="email" id="email" type="text" class="form-control" placeholder="Email" value="<?php echo array_get($data, 'email', '');?>" />
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <!-- <div class="form-group has-feedback">
                <input name="password" id="password" type="password" class="form-control" placeholder="Password" value="<?php echo array_get($data, 'password', '');?>" />
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="repassword" id="repassword" type="password" class="form-control" placeholder="Retype password" value="<?php echo array_get($data, 'password', '');?>" />
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div> -->
            <div class="form-group has-feedback">
                <input name="birthday" id="birthday" type="text" class="form-control datepicker input-medium" placeholder="Birth Day" data-date-language="th-th"  value="<?php echo array_get($data, 'birthday', '');?>"/>
                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="phone" id="phone" type="text" class="form-control" placeholder="Phone number" value="<?php echo array_get($data, 'phone', '');?>" />
                <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
            </div>
			<div class="form-group">
                <label class="">
                    Gender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </label>
				<label class="">
					<div class="iradio_minimal-blue" style="position: relative;" aria-checked="false" aria-disabled="false">
						<input type="radio"<?php echo (array_get($data, 'gender', '')=='male'?' checked="checked" ':' ');?>class="minimal" name="gender" style="position: absolute; opacity: 0;" value="male">
						<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;">
						</ins>
					</div>
				</label>
				<label class="">
					Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</label>
				<label class="">
					<div class="iradio_minimal-blue" style="position: relative;" aria-checked="false" aria-disabled="false">
						<input type="radio"<?php echo (array_get($data, 'gender', '')=='female'?' checked="checked" ':' ');?>class="minimal" name="gender" style="position: absolute; opacity: 0;" value="female">
						<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; border: 0px none; opacity: 0;">
						</ins>
					</div>
				</label>
				<label class="">
					Female
				</label>
			</div>
            <div class="form-group center">
                <div><label for="">Images</label></div>
                <input placeholder="" class="form-control" id="file_upload" name="file_upload" type="file" multiple="true">
                <input type="hidden" name="images" id="images" value="">
            </div>
            <div class="form-group center">
                <div id="show_image_upload" style="hight:200;margin-left:55px;" align="center">

<?php if(isset($data['images']) && is_array($data['images'])):?>
<?php foreach ($data['images'] as $key => $image): ?>
                    <div id="<?php echo $image['id'];?>"><ul class="ace-thumbnails">
                        <li>
                            <a href="javascript:void(0)">

                                <?php if(isset($image['url'])): ?>
                                    <!-- <img alt="200x200" src="<?php echo getImageLink('image', $user->id, $user->images[0]->code, $user->images[0]->extension, 200, 200);?>" width="200" height="200"> -->
                                    <img alt="200x200" src="<?php echo getImageProfile($user, 200, 200);?>" width="200" height="200">
                                <?php  else:?>
                                <?php       if(isset($user->gender) && ($user->gender == 'female')):
                                                $img_rand = 'f01'; 
                                            else:
                                                $img_rand = 'm01';
                                            endif;
                                ?>
                                    <img src="<?php echo URL::to('public/themes/adminlte2');?>/dist/img/avatar/<?php echo $img_rand;?>.png" alt="" width="200" height="200"/>
                                <?php endif;?>
                            </a>
                            <?php if(isset($image['url'])): ?>
                            <!-- <div class="tools tools-bottom">
                                <a href="javascript:void(0)" onclick="return image_delete('<?php echo $image['id'];?>', '<?php echo $image['code'];?>', '<?php echo $data['id'];?>', '<?php echo $image['extension'];?>');" title="Delete">
                                    <i class="fa fa-fw fa-trash-o"></i>
                                </a>
                            </div> -->
                            <?php endif;?>
                        </li>
                    </ul><input type="hidden" name="images" value="">
                    </div>
<?php endforeach;?>
<?php endif;?>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                       
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    
                </div>
                <!-- /.col -->
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

            <input name="id" type="hidden" value="<?php echo array_get($data, 'id', '');?>">
        </form>

        <div class="social-auth-links text-center">
            <!-- <p>- OR -</p> -->
            <!-- <a href="<?php echo URL::to('login/fb');?>" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign up using Facebook</a> -->
            <!-- <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign up using Google+</a> -->
        </div>

        <!-- <a href="<?php echo URL::to('login');?>" class="text-center">I already have a membership</a> -->
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
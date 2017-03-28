<?php

class UsersController extends BaseController
{

    private $scode;

    public function __construct(Scode $scode)
    {
        $this->scode = $scode;
    }

    public function login()
    {
        $theme = Theme::uses('default')->layout('default2');
        $theme->setTitle('Admin ranbandokmaisod :: Login');
        $theme->setDescription('Login description');
        $theme->setClassbody('login-page');
        $theme->share('user', $this->user);

        $view = array(
            'url_res' => Config::get('url.ranbandokmaisod-res')
        );

        $script = $theme->scopeWithLayout('home.jscript_login', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('home.login', $view)->render();
    }

    public function postLogin()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|between:4,40',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('login')->with('error', $message);
        }

        $email = array_get($data, 'email', '');
        $password = array_get($data, 'password', '');
        $password = $this->scode->pencode($password, '@SiamiTS!');
        
        $parameters = array(
            'email' => (isset($data['email']) ? $data['email'] : ''),
            'password' => $password,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not sign in');
            $message = 'Sorry, Email or Password is invalid!';

            return Redirect::to('login')->with('error', $message);
        }

        $user = array_get($results, 'data.record.0', array());
        $user_status = array_get($user, 'status', '');

        if ($user_status == '0') {
            return Redirect::to('register/verify?email='.$email);
        }

        $remember = (Input::has('remember')) ? true : false;

        //Auth::login($user, $remember);
        $lifetime = ($remember) ? time() + 262800000 : 0;
        $access_token = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        $access_token = $this->scode->pencode($access_token, '@SiamiTS!');
        $user = serialize($user);
        $keep = base64_encode($user);
        $user_cookie = setcookie(Config::get('web.ranbandokmaisod-cookie_name'), $keep, $lifetime, null, null, null, true);
        $access_cookie = setcookie('access_token', $access_token, $lifetime, null, null, null, true);
        
        if (!$user_cookie || !$access_cookie) {
            $message = 'Create cookie user error.';
            return Redirect::to('/login')->with('error', $message);
        }

        $message = 'You successfully sign in';

        return Redirect::to('login')->with('success', $message);
    }

    public function register()
    {
        if (isset($this->user->id)) {
            return Redirect::to('login');
        }

        $theme = Theme::uses('default')->layout('default2');
        $theme->setTitle('Admin ranbandokmaisod :: Register');
        $theme->setDescription('Register description');
        $theme->setClassbody('login-page');
        //$theme->share('user', $this->user);

        $view = array();

        $script = $theme->scopeWithLayout('home.jscript_register', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('home.register', $view)->render();
    }

    public function postRegister()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'required|between:4,40',
            'repassword' => 'required|between:4,40',
            'birthday'   => 'required',
            'phone'      => 'between:8,12',
            'gender'     => 'required|in:male,female',
            'agree'      => 'required|integer',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('register')->with('error', $message);
        }

        $name     = array_get($data, 'name', '');
        $email    = array_get($data, 'email', '');
        $phone    = array_get($data, 'phone', '');
        $password = array_get($data, 'password', '');
        $password = $this->scode->pencode($password, '@SiamiTS!');
        $active   = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $active   = $this->scode->pencode($active, '@SiamiTS!');

        $birthday = array_get($data, 'birthday', '');
        $bd       = new DateTime($birthday);
        $y        = $bd->format('Y');
        $y        = $y - 543;
        $m        = $bd->format('m');
        $d        = $bd->format('d');
        $bd       = $y.'-'.$m.'-'.$d;
        $bd       = new DateTime($bd);
        $birthday = $bd->format('Y-m-d');

        $parameters = array(
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password,
            'birthday' => $birthday,
            'gender'   => (isset($data['gender']) ? $data['gender'] : 'male'),
            'active'   => $active,
            'status'   => '0',
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not register');

            return Redirect::to('register')->with('error', $message);
        }

        $results = array_get($results, 'data.record', array());

        $user = new User;
        foreach ($results as $key => $value) {
            $user->$key = $value;
        }

        // Send email
        $active     = array_get($results, 'active', '');
        $subject    = 'Welcome New Members to ranbandokmaisod.com!';
        $from       = 'no-reply@ranbandokmaisod.com';
        $to         = $email;
        $name       = $name;
        $verify_url = URL::to('register/verify').'?email='.$email.'&token='.$active;
        $detail     = 'คุณได้ทำการสมัครสมาชิกกับเว็บไซต์ ranbandokmaisod.com แล้วครับ ';
        $detail    .= 'คุณต้องทำการยืนยันการสมัครสมาชิกผ่านอีเมล คุณจึงจะสามารถใช้บริการจากทางเว็บไซต์ได้ครับ ยืนยันการสมัคร ';
        $detail    .= '<a href="'.$verify_url.'" style="color:#0099cc" target="_blank">คลิกที่นี่</a>';

        $data = array(
            'detail' => $detail,
            'name' => $name,
        );

        $user = array(
            'email' => $to,
            'name' => $name,
            'from' => $from,
            'subject' => $subject,
        );

        $sendmail = Mail::send('emails.register', $data, function ($message) use ($user) {
            $message->from($user['from'], 'ranbandokmaisod.com');
            $message->to($user['email'], $user['name'])->subject($user['subject']);
        });

        return Redirect::to('register/verify?email='.$email);
    }

    public function profile()
    {
        $theme = Theme::uses('default')->layout('default2');
        $theme->setTitle('Admin ranbandokmaisod :: Profile');
        $theme->setDescription('Profile description');
        $theme->setClassbody('login-page');
        $theme->share('user', $this->user);

        $id = $this->user->id;
        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users/'.$id);
        $results = json_decode($results, true);
        $data = array_get($results, 'data.record', array());

        // Convert datetime to thai
        $birthday = array_get($data, 'birthday', '');
        $bd       = new DateTime($birthday);
        $y        = $bd->format('Y');
        $y        = $y + 543;
        $m        = $bd->format('m');
        $d        = $bd->format('d');
        $bd       = $y.'-'.$m.'-'.$d;
        $bd       = new DateTime($bd);
        $data['birthday'] = $bd->format('Y-m-d');

        $view = array(
            'data' => $data
        );

        $script = $theme->scopeWithLayout('home.jscript_profile', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('home.profile', $view)->render();
    }

    public function editProfile()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'id'       => 'required|integer',
            'name'     => 'required',
            'email'    => 'required|email',
            'phone'    => 'between:8,12',
            'birthday' => 'required',
            'gender'   => 'required|in:male,female',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('profile')->with('error', $message);
        }

        $id       = array_get($data, 'id', '');
        $email    = array_get($data, 'email', '');
        $phone    = array_get($data, 'phone', '');
        $images   = array_get($data, 'images', '');
        $birthday = array_get($data, 'birthday', '');
        $bd       = new DateTime($birthday);
        $y        = $bd->format('Y');
        $y        = $y - 543;
        $m        = $bd->format('m');
        $d        = $bd->format('d');
        $bd       = $y.'-'.$m.'-'.$d;
        $bd       = new DateTime($bd);
        $birthday = $bd->format('Y-m-d');

        $gender             = array_get($data, 'gender', 'male');
        $name               = array_get($data, 'name', '');
        $name_arr           = explode(' ', $name);
        $first_name = array_get($name_arr, '0', '');
        $last_name  = array_get($name_arr, '1', '');

        $parameters = array(
            'name'       => $name,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'phone'      => $phone,
            'birthday'   => $birthday,
            'gender'     => $gender,
            'images'     => $images,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->put('users/'.$id, $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not update');

            return Redirect::to('profile')->with('error', $message);
        }

        $results = array_get($results, 'data.record', array());

        $user = new User;
        foreach ($results as $key => $value) {
            $user->$key = $value;
        }

        $message = 'Profile update success';
        return Redirect::to('profile')->with('success', $message);
    }

    public function password()
    {
        $theme = Theme::uses('default')->layout('default2');
        $theme->setTitle('Admin ranbandokmaisod :: Edit password');
        $theme->setDescription('Edit password description');
        $theme->setClassbody('login-page');
        $theme->share('user', $this->user);

        $id = $this->user->id;
        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users/'.$id);
        $results = json_decode($results, true);
        $data = array_get($results, 'data.record', array());

        // Convert datetime to thai
        $birthday = array_get($data, 'birthday', '');
        $bd       = new DateTime($birthday);
        $y        = $bd->format('Y');
        $y        = $y + 543;
        $m        = $bd->format('m');
        $d        = $bd->format('d');
        $bd       = $y.'-'.$m.'-'.$d;
        $bd       = new DateTime($bd);
        $data['birthday'] = $bd->format('Y-m-d');

        $view = array(
            'data' => $data
        );

        $script = $theme->scopeWithLayout('home.jscript_password', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('home.password', $view)->render();
    }

    public function editPassword()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'id'                       => 'required|integer',
            'email'                    => 'required|email',
            'password'                 => 'required|min:4|max:30',
            'newpassword'              => 'required||min:4|max:30|confirmed',
            'newpassword_confirmation' => 'required|min:4|max:30',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('profile/password')->with('error', $message);
        }

        // $id       = array_get($data, 'id', '');
        $email       = array_get($data, 'email', '');
        $password    = array_get($data, 'password', '');
        $password    = $this->scode->pencode($password, '@SiamiTS!');
        $newpassword = array_get($data, 'newpassword', '');
        $newpassword = $this->scode->pencode($newpassword, '@SiamiTS!');

        $parameters = array(
            'email' => $email,
            'password' => $password,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = 'Sorry, Password is invalid!';

            return Redirect::to('profile/password')->with('error', $message);
        }

        $id = array_get($results, 'data.record.0.id', '0');

        if ($id == '0') {
            $message = 'Sorry, Password is invalid!';

            return Redirect::to('profile/password')->with('error', $message);
        }

        $parameters = array(
            'password' => $newpassword,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->put('users/'.$id, $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not update');

            return Redirect::to('profile/password')->with('error', $message);
        }

        $message = 'Password update success';
        return Redirect::to('profile/password')->with('success', $message);
    }

    public function checkmail()
    {
        $data = Input::all();

        $email_old  = array_get($data, 'email_old', '');
        $email      = array_get($data, 'email', '');

        if (($email != '') && ($email_old == $email)) {
            return json_encode('true');
        }

        $parameters = array(
            'email' => $email,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        $num_email = array_get($results, 'data.pagination.total', 0);

        if ($num_email > 0) {
            return json_encode('Email already exists.');
        }

        return json_encode('true');
    }

    public function verify()
    {
        $data = Input::all();
        $theme = Theme::uses('default')->layout('default2');
        $theme->setTitle('Admin ranbandokmaisod :: Login');
        $theme->setDescription('Login description');
        $theme->setClassbody('login-page');

        $email = array_get($data, 'email', '');

        if ($email =='') {
            return Redirect::to('/login')->with('error', 'Sorry, Email can\'t empty');
        }

        if (isset($this->user->id)) {
            return Redirect::to('/login');
        }

        $parameters = array(
            'email'  => $email,
        );

        // isset($data['token']) ? $parameters['active'] = $data['token'] : '';

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $user = $client->get('users', $parameters);
        $user = json_decode($user);

        if (!isset($user->data->record[0])) {
            return Redirect::to('/login')->with('error', 'Sorry, Email or somthings is invalid');
        }

        if (isset($data['token']) && ($data['token'] != '')) {
            if ($user->data->record[0]->active == $data['token']) {
                $id = $user->data->record[0]->id;
                $parameters = array(
                    'active' => '',
                    'status' => '1',
                );

                $user_u = $client->put('users/'.$id, $parameters);
                $user_u = json_decode($user_u);

                if (!isset($user_u)) {
                    return Redirect::to('/register/verify?email='.$email)->with('error', 'Sorry, Can\'t verify at this time');
                }

                return Redirect::to('/register/verify?email='.$email)->with('success', 'Verify this email already!');
            }
        }

        $this->user = $user->data->record[0];
        $theme->share('user', $this->user);

        $view = array(
            'mode' => 'verify'
        );

        $script = $theme->scopeWithLayout('home.jscript_verify', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('home.verify', $view)->render();
    }

    public function sendMailVerify()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'email'      => 'required|email',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('register')->with('error', $message);
        }

        $email    = array_get($data, 'email', '');

        $parameters = array(
            'email'    => $email,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Not found user');

            return Redirect::to('register/verify?email='.$email)->with('error', $message);
        }

        $results = array_get($results, 'data.record.0', array());
        $active     = array_get($results, 'active', '');

        if (empty($results)) {
            $message = 'Not found user';

            return Redirect::to('register/verify?email='.$email)->with('error', $message);
        }

        // Send email
        $subject    = 'Welcome New Members to ranbandokmaisod.com!';
        $from       = 'no-reply@ranbandokmaisod.com';
        $to         = $email;
        $name       = array_get($results, 'name', '');
        $verify_url = URL::to('register/verify').'?email='.$email.'&token='.$active;
        $detail     = 'คุณได้ทำการสมัครสมาชิกกับเว็บไซต์ ranbandokmaisod.com แล้วครับ ';
        $detail    .= 'คุณต้องทำการยืนยันการสมัครสมาชิกผ่านอีเมล คุณจึงจะสามารถใช้บริการจากทางเว็บไซต์ได้ครับ ยืนยันการสมัคร ';
        $detail    .= '<a href="'.$verify_url.'" style="color:#0099cc" target="_blank">คลิกที่นี่</a>';

        $data = array(
            'detail' => $detail,
            'name' => $name,
        );

        $user = array(
            'email' => $to,
            'name' => $name,
            'from' => $from,
            'subject' => $subject,
        );

        $sendmail = Mail::send('emails.register', $data, function ($message) use ($user) {
            $message->from($user['from'], 'ranbandokmaisod.com');
            $message->to($user['email'], $user['name'])->subject($user['subject']);
        });

        $message = 'We send email to '.$email.' again !';
        return Redirect::to('register/verify?email='.$email)->with('success', $message);
    }

    public function forgot()
    {
        if (isset($this->user->id)) {
            return Redirect::to('login');
        }

        $theme = Theme::uses('default')->layout('default2');
        $theme->setTitle('Admin ranbandokmaisod :: Forgot Password');
        $theme->setDescription('Forgot Password description');
        $theme->setClassbody('login-page');
        //$theme->share('user', $this->user);

        $view = array();

        $script = $theme->scopeWithLayout('home.jscript_forgot', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('home.forgot', $view)->render();
    }

    public function postForgot()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'email'      => 'required|email',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('forgot')->with('error', $message);
        }

        $email    = array_get($data, 'email', '');

        $parameters = array(
            'email'    => $email,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not forget pasword');

            return Redirect::to('forgot')->with('error', $message);
        }

        $id = array_get($results, 'data.record.0.id', '0');
        $name = array_get($results, 'data.record.0.name', '');

        if ($id == '0') {
            $message = 'User not found';

            return Redirect::to('forgot')->with('error', $message);
        }

        // Create Token
        $active   = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $active   = $this->scode->pencode($active, '@SiamiTS!');
        $parameters = array(
            'active' => $active
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->put('users/'.$id, $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not forget pasword');

            return Redirect::to('forgot')->with('error', $message);
        }

        // Send email
        $subject    = 'Reset password for ranbandokmaisod.com member!';
        $from       = 'no-reply@ranbandokmaisod.com';
        $to         = $email;
        $verify_url = URL::to('forgot/password').'?email='.$email.'&token='.$active;
        $detail     = 'คุณได้ทำการตั้งค่ารหัสผ่านใหม่กับเว็บไซต์ ranbandokmaisod.com ';
        $detail    .= 'คุณต้องทำการตั้งค่ารหัสผ่านใหม่ คุณจึงจะสามารถใช้บริการจากทางเว็บไซต์ได้ครับ ตั้งค่ารหัสผ่านใหม่ ';
        $detail    .= '<a href="'.$verify_url.'" style="color:#0099cc" target="_blank">คลิกที่นี่</a>';

        $data = array(
            'detail' => $detail,
            'name' => $name,
        );

        $user = array(
            'email' => $to,
            'name' => $name,
            'from' => $from,
            'subject' => $subject,
        );

        $sendmail = Mail::send('emails.register', $data, function ($message) use ($user) {
            $message->from($user['from'], 'ranbandokmaisod.com');
            $message->to($user['email'], $user['name'])->subject($user['subject']);
        });

        $message = 'We send link for reset password to '.$email;
        return Redirect::to('forgot')->with('success', $message);
    }

    public function setPassword()
    {
        $data = Input::all();
        $theme = Theme::uses('default')->layout('default2');
        $theme->setTitle('Admin ranbandokmaisod :: Reset password');
        $theme->setDescription('Reset password description');
        $theme->setClassbody('login-page');
        // $theme->share('user', $this->user);

        $email = array_get($data, 'email', '');
        $token = array_get($data, 'token', '');
        $parameters = array(
            'email' => $email,
        );
        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'User not found');

            return Redirect::to('login')->with('error', $message);
        }

        $active = array_get($results, 'data.record.0.active', '0');
        if (!Session::has('success')) {
            if ($active != $token) {
                $message = 'Sorry, Invalid Token';

                return Redirect::to('login')->with('error', $message);
            }
        }

        $data = array_get($results, 'data.record.0', array());

        $view = array(
            'data' => $data
        );

        $script = $theme->scopeWithLayout('home.jscript_resetpassword', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('home.resetpassword', $view)->render();
    }

    public function postForgotPassword()
    {
        $data = Input::all();
        $token       = array_get($data, 'token', '');
        $email       = array_get($data, 'email', '');

        // Validator request
        $rules = array(
            'id'                       => 'required|integer',
            'token'                    => 'required',
            'email'                    => 'required|email',
            'newpassword'              => 'required||min:4|max:30|confirmed',
            'newpassword_confirmation' => 'required|min:4|max:30',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('forgot/password?email='.$email.'&token='.$token)->with('error', $message);
        }

        // $id       = array_get($data, 'id', '');
        $newpassword = array_get($data, 'newpassword', '');
        $newpassword = $this->scode->pencode($newpassword, '@SiamiTS!');

        $parameters = array(
            'email' => $email,
            'token' => $email,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not reset password');

            return Redirect::to('forgot/password?email='.$email.'&token='.$token)->with('error', $message);
        }

        $id = array_get($results, 'data.record.0.id', '0');

        if ($id == '0') {
            $message = 'Sorry, User not found';

            return Redirect::to('forgot/password?email='.$email.'&token='.$token)->with('error', $message);
        }

        $parameters = array(
            'password' => $newpassword,
            'active'   => '',
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->put('users/'.$id, $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not reset password');

            return Redirect::to('forgot/password?email='.$email.'&token='.$token)->with('error', $message);
        }

        $message = 'Reset password success';
        return Redirect::to('forgot/password?email='.$email.'&token='.$token)->with('success', $message);
    }
}

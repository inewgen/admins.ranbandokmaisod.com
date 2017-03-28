<?php

class AuthController extends \BaseController
{
    private $scode;
    private $images;

    public function __construct(Scode $scode, Images $images)
    {
        $this->scode = $scode;
        $this->images = $images;
    }

    public function getTwitterLogin($auth = null)
    {
        if ($auth == 'auth') {
            Hybrid_Endpoint::process();
            return;
        }

        try {
            $oauth = new Hybrid_Auth(app_path() . '/config/twitterAuth.php');
            $provider = $oauth->authenticate('Twitter');
            $profile = $provider->getUserProfile();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return var_dump($profile) . '<a href="logout">Log Out</a>';

    }

    //this is the code for facebook Login
    public function getFacebookLogin($auth = null)
    {
        if ($auth == 'auth') {
            try {
                Hybrid_Endpoint::process();
            } catch (Exception $e) {
                return Redirect::to('fbauth');
            }
            return;
        }

        if ($_SERVER['MY_LARAVEL_ENV'] == 'production') {
            $oauth = new Hybrid_Auth(app_path() . '/config/fb_auth.php');
        } else {
            $oauth = new Hybrid_Auth(app_path() . '/config/local/fb_auth.php');
        }

        $provider = $oauth->authenticate('Facebook');
        $profile = $provider->getUserProfile();

        if (empty($profile)) {
            return Redirect::to('/login')->with('error', 'Data not found facebook.');
        }

        //Upload images
        $code = $this->scode->imageCode();

        $photo_facebook = str_replace('150', '200', $profile->photoURL);
        $password = $this->scode->pencode('@ranbandokmaisod12345!', '@SiamiTS!');
        $extension = 'jpg';
        $name = 'facebook.' . $extension;

        $images[] = array(
            'code' => $code,
            'name' => $name,
            'extension' => strtolower($extension),
        );

        $parameters = array(
            'email' => $profile->email,
            'password' => $password,
            'name' => $profile->displayName,
            'birthday' => date("Y-m-d", strtotime($profile->birthYear.'-'.$profile->birthMonth.'-'.$profile->birthDay)),
            'photo' => $photo_facebook,
            'images' => $images,
            'uid_fb' => $profile->identifier,
            'first_name' => $profile->firstName,
            'last_name' => $profile->lastName,
            'gender' => $profile->gender,
            'link_fb' => $profile->profileURL,
            'locale' => $profile->language,
            'access_token_fb' => '',
            'remember_token' => '',
            'status' => '1',
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('users', $parameters);
        $results = json_decode($results, true);

        if (empty($results['data']['record']['id'])) {
            $message = array_get($results, 'data.message', 'Create facebook user error');
            return Redirect::to('/login')->with('error', $message);
        }

        $images_old = array_get($results, 'data.images_old', array());
        $user = array_get($results, 'data.record', array());

        //$this->access_token_fb = $this->fb->getToken();
        $user_id = array_get($user, 'id', 0);

        // Delete old images
        foreach ($images_old as $key => $value) {
            $code_old = array_get($value, 'code', 0);
            $extension_old = array_get($value, 'extension', 'jpg');

            $ext = strtolower($extension_old);
            $source_folder = '../res/public/uploads/'. $user_id ;
            $filelist = array();
            if ($handle = opendir($source_folder)) {
                while ($entry = readdir($handle)) {
                    if (strpos($entry, $code_old) === 0) {
                        $image_path = '../res/public/uploads/' . $user_id . '/' . strtolower($entry);
                        $image_delete = $this->images->deleteFile($image_path);
                        $filelist[] = $entry;

                        if (!$image_delete) {
                            $message = array_get($results, 'data.message', 'Delete old image user error.');
                            return Redirect::to('/login')->with('error', $message);
                        }
                    }
                }
                closedir($handle);
            }
        }

        // Upload images
        $targetFolder = '../res/public/uploads/' . $user_id;
        $targetFile = '../res/public/uploads/' . $user_id . '/' . $code . '.' . strtolower($extension);

        if (!file_exists($targetFolder)) {
            $makeFolder = $this->images->makeFolder($targetFolder);

            if (!$makeFolder) {
                $message = array_get($results, 'data.message', 'Create image folder user error.');
                return Redirect::to('/login')->with('error', $message);
            }
        }

        $photo = $this->images->saveImage($photo_facebook, $targetFile);
        if (!$photo) {
            $message = array_get($results, 'data.message', 'Create image user error.');
            return Redirect::to('/login')->with('error', $message);
        }
        
        //Auth::loginUsingId($user->id);
        $remember = false;
        $lifetime = ($remember) ? time() + 262800000 : 0;
        $access_token = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        $access_token = $this->scode->pencode($access_token, '@SiamiTS!');
        $user = serialize($user);
        $keep = base64_encode($user);
        $user_cookie = setcookie(Config::get('web.ranbandokmaisod-cookie_name'), $keep, $lifetime, null, null, null, true);
        $access_cookie = setcookie('access_token', $access_token, $lifetime, null, null, null, true);
        
        if (!$user_cookie || !$access_cookie) {
            $message = array_get($results, 'data.message', 'Create cookie user error.');
            return Redirect::to('/login')->with('error', $message);
        }

        return Redirect::to('/login')->with('message', 'You are login with facebook already');
    }

    //this is the method that will handle the Google Login

    public function getGoogleLogin($auth = null)
    {
        if ($auth == 'auth') {
            Hybrid_Endpoint::process();

        }
        
        try {
            if ($_SERVER['MY_LARAVEL_ENV'] == 'production') {
                $oauth = new Hybrid_Auth(app_path() . '/config/google_auth.php');
            } else {
                $oauth = new Hybrid_Auth(app_path() . '/config/local/google_auth.php');
            }

            $provider = $oauth->authenticate('Google');
            $profile = $provider->getUserProfile();
        } catch (exception $e) {
            return $e->getMessage();
        }

        if (empty($profile)) {
            return Redirect::to('/login')->with('error', 'Data not found google.');
        }

        //Upload images
        $code = $this->scode->imageCode();

        $photo_google = $profile->photoURL;
        $password = $this->scode->pencode('@ranbandokmaisod12345!', '@SiamiTS!');
        $extension = 'jpg';
        $name = 'google.' . $extension;

        $images[] = array(
            'code' => $code,
            'name' => $name,
            'extension' => strtolower($extension),
        );

        $parameters = array(
            'email' => $profile->email,
            'password' => $password,
            'name' => $profile->displayName,
            'birthday' => date("Y-m-d", strtotime($profile->birthYear.'-'.$profile->birthMonth.'-'.$profile->birthDay)),
            'photo' => $photo_google,
            'images' => $images,
            'uid_g' => $profile->identifier,
            'first_name' => $profile->firstName,
            'last_name' => $profile->lastName,
            'gender' => $profile->gender,
            'link_g' => $profile->profileURL,
            'locale' => $profile->language,
            'access_token_g' => '',
            'remember_token' => '',
            'status' => '1',
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('users', $parameters);
        $results = json_decode($results, true);

        if (empty($results['data']['record']['id'])) {
            $message = array_get($results, 'data.message', 'Create google user error.');
            return Redirect::to('/login')->with('error', $message);
        }

        $images_old = array_get($results, 'data.images_old', array());
        $user = array_get($results, 'data.record', array());

        //$this->access_token_fb = $this->fb->getToken();
        $user_id = array_get($user, 'id', 0);

        // Delete old images
        foreach ($images_old as $key => $value) {
            $code_old = array_get($value, 'code', 0);
            $extension_old = array_get($value, 'extension', 'jpg');

            $ext = strtolower($extension_old);
            $source_folder = '../res/public/uploads/'. $user_id ;
            $filelist = array();
            if ($handle = opendir($source_folder)) {
                while ($entry = readdir($handle)) {
                    if (strpos($entry, $code_old) === 0) {
                        $image_path = '../res/public/uploads/' . $user_id . '/' . strtolower($entry);
                        $image_delete = $this->images->deleteFile($image_path);
                        $filelist[] = $entry;

                        if (!$image_delete) {
                            $message = array_get($results, 'data.message', 'Delete old image user error.');
                            return Redirect::to('/login')->with('error', $message);
                        }
                    }
                }
                closedir($handle);
            }
        }

        // Upload images
        $targetFolder = '../res/public/uploads/' . $user_id;
        $targetFile = '../res/public/uploads/' . $user_id . '/' . $code . '.' . $extension;

        if (!file_exists($targetFolder)) {
            $makeFolder = $this->images->makeFolder($targetFolder);

            if (!$makeFolder) {
                $message = array_get($results, 'data.message', 'Create image folder user error.');
                return Redirect::to('/login')->with('error', $message);
            }
        }

        $photo = $this->images->saveImage($photo_google, $targetFile);
        if (!$photo) {
            $message = array_get($results, 'data.message', 'Create image user error.');
            return Redirect::to('/login')->with('error', $message);
        }
        
        //Auth::loginUsingId($user->id);
        $remember = false;
        $lifetime = ($remember) ? time() + 262800000 : 0;
        $access_token = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        $access_token = $this->scode->pencode($access_token, '@SiamiTS!');
        $user = serialize($user);
        $keep = base64_encode($user);
        $user_cookie = setcookie(Config::get('web.ranbandokmaisod-cookie_name'), $keep, $lifetime, null, null, null, true);
        $access_cookie = setcookie('access_token', $access_token, $lifetime, null, null, null, true);
        
        if (!$user_cookie || !$access_cookie) {
            $message = array_get($results, 'data.message', 'Create cookie user error.');
            return Redirect::to('/login')->with('error', $message);
        }

        return Redirect::to('/login')->with('message', 'You are login with google already');
    }

    public function getLoggedOut()
    {
        //Auth::logout();
        $domain = static::getDomain(); //alert($domain);die();
        $domain = null;
        $lifetime = time() - 3600;
        $keep  = '';
        setcookie(Config::get('web.ranbandokmaisod-cookie_name'), $keep, $lifetime, '/', $domain);
        setcookie('access_token', $keep, $lifetime, '/', $domain);

        // clear session all
        Session::flush();

        // $hauth = new Hybrid_Auth(app_path() . '/config/twitterAuth.php');
        // $hauth = new Hybrid_Auth(app_path() . '/config/fb_auth.php');
        //You can use any of the one provider to get the variable, I am using google
        //this is important to do, as it clears out the cookie
        $hauth = new Hybrid_Auth(app_path() . '/config/google_auth.php');
        $hauth->logoutAllProviders();
        return Redirect::to('login');
    }

    public static function getDomain()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            preg_match('/[^.]+\.[^.]+$/', $_SERVER['HTTP_HOST'], $matches);

            return '.'.$matches[0];
        }

        return null;
    }

}

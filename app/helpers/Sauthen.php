<?php

class Sauthen
{
    /**
     * [$key description] for encrypt
     * @var string
     */
    protected static $key = "8r0tg3jc4h4oak0gmvogm25be1";

    /**
     * [$cookie_name description] for set cookie
     * @var string
     */
    protected static $cookie_name = 'zsid';

    /**
     * [$user_id description]
     * @var boolean
     */
    protected static $user_id = false;

    /**
     * [__construct description]
     */
    public function __construct()
    {

    }

    /**
     * [getDomain description]
     * @return [type] [description]
     */
    public function getDomain()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            preg_match('/[^.]+\.[^.]+$/', $_SERVER['HTTP_HOST'], $matches);

            return '.'.$matches[0];
        }

        return null;
    }

    /**
     * [authenticate description]
     * @return [type] [description]
     */
    public static function authenticateOpenID(array $credentials, $remember = false)
    {
        if (array_get($credentials, 'uid') && array_get($credentials, 'service')) {

        }

        return false;
    }

    /**
     * [authenticate description]
     * @return [type] [description]
     */
    public static function authenticate(array $credentials, $remember = false)
    {
        $client = new Client(Config::get('url.api-account'));
        $parameters =  array(
            'username'=>$credentials['user'],
            'password'=> $credentials['password']
        );

        $auth   = $client->get('auth', $parameters);
        $auth   = json_decode($auth);

        if (isset($auth->status_code) && $auth->status_code != '0') {
            return $auth ;
        }

        if (isset($auth->status_code) && isset($auth->data->sso->access_token) && $auth->status_code == '0') {

            //$config = \Config::get('session');
            $access_token =  $auth->data->sso->access_token;
            $expires = $auth->data->sso->expires;

            $keep = array(
                'user_id'        => $auth->data->profile->id,
                'email'          => $auth->data->profile->email,
                'first_name'     => $auth->data->profile->first_name,
                'last_name'      => $auth->data->profile->last_name,
                'display_name'   => $auth->data->profile->display_name,
                'display_status' => $auth->data->profile->display_status,
                'phone'          => $auth->data->profile->phone,
                'idcard'         => $auth->data->profile->idcard,
                'birthday'       => $auth->data->profile->birthday,
                'sex'            => $auth->data->profile->sex,
                'type'           => isset($auth->data->user->login_type)?$auth->data->user->login_type:'email',
                'activated'      => $auth->data->profile->activated,
                'connect'        => (array) $auth->data->profile->connect,
                'time'           => time()
            );

            $domain = static::getDomain();
            //$lifetime = ($remember) ? time() + 262800000 : 0;
            self::setCookie($keep, $expires, '/', $domain);
            setcookie("access_token", $access_token, $expires, '/', $domain);

            return $auth;
        }
    }

    /**
     * [authenticateWithFacebook description]
     * @return [type] [description]
     */
    public static function authenticateWithFacebook(array $credentials, $remember = false)
    {
        $client = new Client(Config::get('url.api-account'));
        $parameters =  array(
            'email'=>$credentials['email'],
        );

        $auth   = $client->get('social/login', $parameters);
        $auth   = json_decode($auth);

        if (isset($auth->status_code) && $auth->status_code != '0') {
            return $auth ;
        }

        if (isset($auth->status_code) && isset($auth->data->sso->access_token) && $auth->status_code == '0') {

            //$config = \Config::get('session');
            $access_token =  $auth->data->sso->access_token;
            $expires = $auth->data->sso->expires;

            $keep = array(
                'user_id' => $auth->data->profile->id,
                'email' => $auth->data->profile->email,
                'first_name' => $auth->data->profile->first_name,
                'last_name' => $auth->data->profile->last_name,
                'display_name' => $auth->data->profile->display_name,
                'display_status'=>$auth->data->profile->display_status,
                'phone'=>$auth->data->profile->phone,
                'idcard'=>$auth->data->profile->idcard,
                'birthday'=>$auth->data->profile->birthday,
                'sex'=>$auth->data->profile->sex,
                'type' => $auth->data->user->login_type,
                'activated'=> $auth->data->profile->activated,
                'connect' => (array) $auth->data->profile->connect,
                'time' => time()
            );

            $domain = static::getDomain();
            //$lifetime = ($remember) ? time() + 262800000 : 0;
            self::setCookie($keep, $expires, '/', $domain);
            setcookie("access_token", $access_token, $expires, '/', $domain);

            return $auth;
        }
    }

    /**
     * [check description]
     * @return [type] [description]
     */
    public static function check()
    {
        if (isset($_COOKIE[Config::get('web.ranbandokmaisod-cookie_name')]) && isset($_COOKIE['access_token'])) {

            try {
                $user = unserialize(base64_decode($_COOKIE[Config::get('web.ranbandokmaisod-cookie_name')]));
                if (isset($user['id']) && is_numeric($user['id']) && $user['id'] > 0) {
                    self::$user_id = $user['id'];

                    return true;
                }
            } catch (Exception $e) {
                return false;
            }

        }

        return false;
    }

    /**
     * [getUserID description]
     * @return [type] [description]
     */
    public static function getUserID()
    {
        // ioc container
        if (self::$user_id) {
            return self::$user_id;
        }

        if (isset($_COOKIE[Config::get('web.ranbandokmaisod-cookie_name')])) {
            $user = unserialize(base64_decode(static::$key, $_COOKIE[Config::get('web.ranbandokmaisod-cookie_name')]));
            if (isset($user['id']) && is_numeric($user['id']) && $user['id'] > 0) {
                return $user['id'];
            }
        }

        return false;
    }

    /**
     * [getUserID description]
     * @return [type] [description]
     */
    public static function getStoreID()
    {
        if (isset($_COOKIE[static::$cookie_name])) {
            // ioc container
            if (self::$user_id) {
                $_user_id = self::$user_id;
            } else {
                $user = unserialize(\Hashing\Rc4crypt::decrypt(static::$key, $_COOKIE[static::$cookie_name]));
                if (isset($user['user_id']) && is_numeric($user['user_id']) && $user['user_id'] > 0) {
                    $_user_id = $user['user_id'];
                }
            }

            if (isset($_user_id)) {
                $client = new Client();
                $store = $client->get('store', array(
                    'user_id' => $_user_id,
                    'fields' => 'id'
                ));
                $store = json_decode($store);

                if ($store->status_code == '0' && isset($store->data->record[0])) {
                    return $store->data->record[0]->id;
                }
            }
        }

        return false;

    }

    /**
     * [getUserwithParallel description]
     * @return [type] [description]
     */
    public static function getUserwithParallel()
    {
        $parallel = App::make('Parallel');
        if (isset($parallel['user'])) {
            $store = $parallel['user'];
            //$store = Client::get('user/'.$user['user_id']);
            $store = json_decode($store);
            if ($store && $store->status_code == '0') {
                return $store->data->record[0];
            }

            self::logout();

            return false;
        }

        return false;
    }

    /**
     * [hasAccess description]
     * @return boolean [description]
     */
    public static function getUser()
    {
        if (isset($_COOKIE[static::$cookie_name])) {
            // ioc container
            if (self::$user_id) {
                $_user_id = self::$user_id;
            } else {
                $user = unserialize(\Hashing\Rc4crypt::decrypt(static::$key, $_COOKIE[static::$cookie_name]));

                if (isset($user['user_id']) && is_numeric($user['user_id']) && $user['user_id'] > 0) {
                    $_user_id = $user['user_id'];
                }
            }

            if (isset($_user_id)) {

                $client = new Client(Config::get('url.api-account'));
                $auth   = $client->get('user/' . $_user_id);
                $store   = json_decode($auth);

                if ($store && $store->status_code == '0') {
                    $cookie_user = unserialize(\Hashing\Rc4crypt::decrypt(static::$key, $_COOKIE[static::$cookie_name]));
                    $store->data->record[0]->type=isset($cookie_user['type']) ? $cookie_user['type'] : '';

                    return $store->data->record[0];
                }

                self::logout();

                return false;
            }
        }

        return false;
    }

    /**
     * [checkOwner description]
     * @return [type] [description]
     */
    public static function checkOwner()
    {
        if ($user = Intelligent::getUser()) {
            $store = App::make('Store');
            if ($store->user_id == $user->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * [checkOwner description]
     * @return [type] [description]
     */
    public static function checkStoreOwner()
    {
        if ($user = Intelligent::getUser()) {
            $store = App::make('Store');
            if ($store->user_id == $user->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * [hasAccess description]
     * @return boolean [description]
     */
    public function hasAccess()
    {

    }

    /**
     * [logout description]
     * @return [type] [description]
     */
    public static function logout()
    {
        $domain = static::getDomain();

        self::setCookie(array(), time() - 3600, '/', $domain);
        setcookie('access_token','',time() - 3600, '/', $domain);

        return true;
    }

    /**
     * Actually sets the cookie.
     *
     * @param  mixed  $value
     * @param  int    $lifetime
     * @param  string $path
     * @param  string $domain
     * @param  bool   $secure
     * @param  bool   $httpOnly
     * @return void
     */
    public static function setCookie($value, $lifetime, $path = null, $domain = null, $secure = null, $httpOnly = true)
    {
        $value = serialize($value);
        $keep  = \Hashing\Rc4crypt::encrypt(static::$key, $value);
        setcookie(static::$cookie_name, $keep, $lifetime, $path, $domain, $secure, $httpOnly);
    }

    /**
     * [register description]
     * @return [type] [description]
     */
    public static function registerStoreUserExist($data)
    {
        $client = new Client();
        $store = $client->post('store/register_token', $data);

        return json_decode($store);
    }

    /**
     * [registerWithStore description]
     * @return [type] [description]
     */
    public static function registerStore($data)
    {
        $client = new Client();
        $store = $client->post('store/register', $data);

        return json_decode($store);
    }

    /**
     * Generate a random string. If your server has
     * @return string
     */
    public static function getRandomString($length = 42)
    {
        // We'll check if the user has OpenSSL installed with PHP. If they do
        // we'll use a better method of getting a random string. Otherwise, we'll
        // fallback to a reasonably reliable method.
        if (function_exists('openssl_random_pseudo_bytes')) {
            // We generate twice as many bytes here because we want to ensure we have
            // enough after we base64 encode it to get the length we need because we
            // take out the "/", "+", and "=" characters.
            $bytes = openssl_random_pseudo_bytes($length * 2);

            // We want to stop execution if the key fails because, well, that is bad.
            if ($bytes === false) {
                throw new \RuntimeException('Unable to generate random string.');
            }

            return substr(str_replace(array(
                '/',
                '+',
                '='
            ), '', base64_encode($bytes)), 0, $length);
        }

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}

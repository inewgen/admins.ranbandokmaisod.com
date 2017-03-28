<?php

if (!function_exists('routeLoad')) {

    function routeLoad($uri = false, $req_path = false, $route_conf = false)
    {
        // preload, fixed when use API::xxx()
        $preload = $route_conf['preload'];
        if ($preload) {
            foreach ($preload as $_uri => $v) {
                if (strpos($req_path, $_uri) !== false) {
                    if (isset($v['include']) && $v['include']) {
                        // include from route optimize
                        foreach ($v['include'] as $_uri) {
                            if (strpos($uri, $_uri) !== false) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        if (App::environment() == 'testing') {
            return true;
        }

        if (strpos($req_path, $uri) !== false) {
            return true;
        }

        return false;
    }
}

if (!function_exists('checkLogin')) {

    function checkLogin()
    {
        if (isset($_COOKIE[Config::get('web.ranbandokmaisod-cookie_name')]) && isset($_COOKIE['access_token'])) {
            try {
                $user = unserialize(base64_decode($_COOKIE[Config::get('web.ranbandokmaisod-cookie_name')]));
                if (isset($user['id']) && is_numeric($user['id']) && $user['id'] > 0) {
                    //self::$user_id = $user['id'];

                    return $user['id'];
                }
            } catch (Exception $e) {
                return false;
            }

        }

        return false;
    }
}

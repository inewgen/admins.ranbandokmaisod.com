<?php

class BaseController extends Controller
{

    protected $user;

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }

        // Get user
        $this->user = array();
        if ($id = self::check()) {
            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $user = $client->get('users/' . $id);
            $user = json_decode($user);

            if (!isset($user->data->record)) {
                return Redirect::to('/login')->with('error', 'Sorry, Please try to login again (' . $id . ')');
            }

            $this->user = $user->data->record;

            App::singleton('User', function ($app) {
                return $this->user;
            });

            // Admin
            if (!isset($this->user->roles[0]->id) || ($this->user->roles[0]->id != '1')) {
                return Redirect::to('');
            }
            

            // The Default Language
            date_default_timezone_set('Asia/Bangkok');
        }
    }

    protected function check()
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

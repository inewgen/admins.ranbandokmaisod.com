<?php

class MembersController extends BaseController
{
    private $scode;

    public function __construct(Scode $scode)
    {
        $this->scode = $scode;
    }

    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getIndex()
    {
        $data = Input::all();

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Members');
        $theme->setDescription('Members description');
        $theme->share('user', $this->user);

        $page    = array_get($data, 'page', '1');
        $perpage = array_get($data, 'perpage', '10');
        $order   = array_get($data, 'order', 'updated_at');
        $sort    = array_get($data, 'sort', 'desc');

        $parameters = array(
            'page'    => $page,
            'perpage' => $perpage,
            'order'   => $order,
            'sort'    => $sort,
        );

        if ($s = array_get($data, 's', false)) {
            $parameters['s'] = $s;
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('members')->with('error', $message);
            }
        }

        if (isset($_GET['sdebug'])) {
            alert($entries);
            die();
        }

        $entries = array_get($results, 'data.record', array());

        $table_title = array(
            'id'         => array('ID', 1),
            'email'      => array('Email', 1),
            'name'       => array('Name', 1),
            'image'      => array('Image', 0),
            'status'     => array('Status', 1),
            'gender'     => array('Gender', 1),
            'role'       => array('Role', 0),
            'created_at' => array('Created', 1),
            'updated_at' => array('Updated', 1),
            'manage' => array('Manage', 0),
        ); 

        $view = array(
            'num_rows'    => count($entries),
            'data'        => $entries,
            'param'       => $parameters,
            'table_title' => $table_title,
        );

        //Pagination
        if ($pagination = self::getDataArray($results, 'data.pagination')) {
            $view['pagination'] = self::getPaginationsMake($pagination, $entries);
        }

        $script = $theme->scopeWithLayout('members.jscript_index', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('members.index', $view)->render();
    }

    public function getAdd()
    {
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Add members');
        $theme->setDescription('Add members description');
        $theme->share('user', $this->user);

        $view = array();

        $script = $theme->scopeWithLayout('members.jscript_add', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('members.add', $view)->render();
    }

    public function postAdd()
    {
        $data = Input::all();
        $referer = array_get($data, 'referer', 'members');

        // Validator request
        $rules = array(
            'name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'required|between:4,40',
            'repassword' => 'required|between:4,40',
            'birthday'   => 'required',
            'phone'      => 'between:8,12',
            'gender'     => 'required|in:male,female',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to($referer)->with('error', $message);
        }

        $name     = array_get($data, 'name', '');
        $email    = array_get($data, 'email', '');
        $phone    = array_get($data, 'phone', '');
        $images   = array_get($data, 'images', array());
        $password = array_get($data, 'password', '');
        $password = $this->scode->pencode($password, Config::get('web.ranbandokmaisod-keys'));

        $birthday = array_get($data, 'birthday', '');
        $bd       = new DateTime($birthday);
        $y        = $bd->format('Y');
        $y        = $y - 543;
        $m        = $bd->format('m');
        $d        = $bd->format('d');
        $bd       = $y.'-'.$m.'-'.$d;
        $bd       = new DateTime($bd);
        $birthday = $bd->format('Y-m-d');

        $role     = array_get($data, 'role', array());
        $photo    = array_get($data, 'photo', '');
        $uid_fb   = array_get($data, 'uid_fb', '');
        $link_fb  = array_get($data, 'link_fb', '');
        $uid_g    = array_get($data, 'uid_g', '');
        $link_g   = array_get($data, 'link_g', '');
        $locale   = array_get($data, 'locale', 'th-TH');
        $timezone = array_get($data, 'timezone', '7');
        $status   = array_get($data, 'status', '1');
        $active   = array_get($data, 'active', '');

        $parameters = array(
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password,
            'birthday' => $birthday,
            'gender'   => (isset($data['gender']) ? $data['gender'] : 'male'),
            'active'   => $active,
            'status'   => $status,
            'images'   => $images,
            'role'     => $role,
            'photo'    => $photo,
            'uid_fb'   => $uid_fb,
            'link_fb'  => $link_fb,
            'uid_g'    => $uid_g,
            'link_g'   => $link_g,
            'locale'   => $locale,
            'timezone' => $timezone,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('users', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not register');

            return Redirect::to($referer)->with('error', $message);
        }

        $message = 'Your successfully add';
        return Redirect::to($referer)->with('success', $message);
    }

    public function getEdit($id)
    {
        $data = Input::all();
        $data['id'] = $id;
        $referer = array_get($data, 'referer', 'members');

        // Validator request
        $rules = array(
            'id'       => 'required|integer',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to($referer)->with('error', $message);
        }

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Edit members');
        $theme->setDescription('Edit members description');
        $theme->share('user', $this->user);

        $parameters = array(
            'user_id' => $id,
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('users/' . $id, $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not created members');

            return Redirect::to($referer)->with('error', $message);
        }

        $entry = array_get($results, 'data.record', array());
        $entries = array();
        foreach ($entry as $key => $value) {
            if ($key == 'birthday') {
                $birthday = $value;
                $bd       = new DateTime($birthday);
                $y        = $bd->format('Y');
                $y        = $y + 543;
                $m        = $bd->format('m');
                $d        = $bd->format('d');
                $bd       = $y.'-'.$m.'-'.$d;
                $bd       = new DateTime($bd);
                $birthday = $bd->format('Y-m-d');
                $value    = $birthday;
            } else if ($key == 'roles') {
                $value = array_get($value, '0.id', '');
            }
            
            $entries[$key] = $value;
        }

        $view = array(
            'data' => $entries,
        );

        $script = $theme->scopeWithLayout('members.jscript_edit', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('members.edit', $view)->render();
    }

    public function postEdit()
    {
        $data = Input::all();

        $rules = array(
            'action' => 'required',
        );

        $referer = array_get($data, 'referer', 'members');
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to($referer)->with('error', $message);
        }

        $action = array_get($data, 'action', null);

        // Delete
        if ($action == 'delete') {
            // Validator request
            $rules = array(
                'id' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $id = array_get($data, 'id', 0);

            // Delete images folder
            $image_path = '../res/public/uploads/' . $id ;

            $image_delete = true;
            if (file_exists($image_path)) {
                $image_delete = File::deleteDirectory($image_path);
            }

            if ($image_delete) {
                // Delete members
                $client = new Client(Config::get('url.ranbandokmaisod-api'));
                $results = $client->delete('users/' . $id);
                $results = json_decode($results, true);

                if (array_get($results, 'status_code', false) != '0') {
                    $message = array_get($results, 'status_txt', 'Can not delete members');

                    return Redirect::to($referer)->with('error', $message);
                }
            }

            $message = 'You successfully delete';

            // Edit
        } else {
            // Validator request
            $rules = array(
                'id'           => 'required|integer',
                'name'         => 'required',
                'email'        => 'required|email',
                'password'     => 'required|between:4,40',
                'password_old' => 'required|between:4,40',
                'repassword'   => 'required|between:4,40',
                'birthday'     => 'required',
                'phone'        => 'between:8,12',
                'gender'       => 'required|in:male,female',
            );

            $id = array_get($data, 'id', '');
            $referer = array_get($data, 'referer', 'members/'.$id);

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $id       = array_get($data, 'id', '');
            $name     = array_get($data, 'name', '');
            $email    = array_get($data, 'email', '');
            $phone    = array_get($data, 'phone', '');
            $images   = array_get($data, 'images', array());
            $password_old = array_get($data, 'password_old', '');
            $password = array_get($data, 'password', '');

            if ($password != $password_old) {
                $password = $this->scode->pencode($password, Config::get('web.ranbandokmaisod-keys'));
            }

            $birthday = array_get($data, 'birthday', '');
            $bd       = new DateTime($birthday);
            $y        = $bd->format('Y');
            $y        = $y - 543;
            $m        = $bd->format('m');
            $d        = $bd->format('d');
            $bd       = $y.'-'.$m.'-'.$d;
            $bd       = new DateTime($bd);
            $birthday = $bd->format('Y-m-d');

            $role     = array_get($data, 'role', array());
            $photo    = array_get($data, 'photo', '');
            $uid_fb   = array_get($data, 'uid_fb', '');
            $link_fb  = array_get($data, 'link_fb', '');
            $uid_g    = array_get($data, 'uid_g', '');
            $link_g   = array_get($data, 'link_g', '');
            $locale   = array_get($data, 'locale', 'th-TH');
            $timezone = array_get($data, 'timezone', '7');
            $status   = array_get($data, 'status', '1');
            $active   = array_get($data, 'active', '');

            $parameters = array(
                'name'     => $name,
                'email'    => $email,
                'phone'    => $phone,
                'password' => $password,
                'birthday' => $birthday,
                'gender'   => (isset($data['gender']) ? $data['gender'] : 'male'),
                'active'   => $active,
                'status'   => $status,
                'images'   => $images,
                'role'     => $role,
                'photo'    => $photo,
                'uid_fb'   => $uid_fb,
                'link_fb'  => $link_fb,
                'uid_g'    => $uid_g,
                'link_g'   => $link_g,
                'locale'   => $locale,
                'timezone' => $timezone,
            );

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->put('users/'.$id, $parameters);
            $results = json_decode($results, true);

            if ($status_code = array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not edit');

                return Redirect::to($referer)->with('error', $message);
            }

            $results = array_get($results, 'data.record', array());

            $user = new User;
            foreach ($results as $key => $value) {
                $user->$key = $value;
            }

            $message = 'Your successfully edit';
        }

        return Redirect::to($referer)->with('success', $message);
    }

    private function getPaginationsMake($pagination, $record)
    {
        $total = array_get($pagination, 'total', 0);
        $limit = array_get($pagination, 'perpage', 0);
        $paginations = Paginator::make($record, $total, $limit);
        return isset($paginations) ? $paginations : '';
    }

    private function getDataArray($data, $key)
    {
        return array_get($data, $key, false);
    }
}

<?php

class ContactsController extends BaseController
{
    private $scode;
    private $images;

    public function __construct(Scode $scode, Images $images)
    {
        $this->scode = $scode;
        $this->images = $images;
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
        $theme->setTitle('Admin ranbandokmaisod :: Contacts');
        $theme->setDescription('Contacts description');
        $theme->share('user', $this->user);

        $page = array_get($data, 'page', '1');
        $perpage = array_get($data, 'perpage', '10');
        $order = array_get($data, 'order', 'updated_at');
        $sort = array_get($data, 'sort', 'desc');

        $parameters = array(
            'page' => $page,
            'perpage' => $perpage,
            'order' => $order,
            'sort' => $sort,
        );

        // Filter
        $fild_arr = array(
            'id',
            'name',
            'email',
            'message',
            'user_id',
            // 'status',
            'ip',
            'url',
            'note',
            's',
        );

        foreach ($fild_arr as $value) {
            !empty($data[$value]) ? $parameters[$value] = array_get($data, $value, '') : '';
        }

        if (isset($data['status'])) {
            $parameters['status'] = $data['status'];
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('contacts', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('contacts')->with('error', $message);
            }
        }

        $entries = array_get($results, 'data.record', array());

        $table_title = array(
            'id' => array('ID ', 1),
            'name' => array('Name', 1),
            'email' => array('Email', 1),
            'mobile' => array('Mobile', 1),
            'message' => array('Message', 1),
            'user_id' => array('User_id', 1),
            'status' => array('Status', 2), // 0=Closed,1=New,2=Read,3=Active
            'ip' => array('IP', 1),
            // 'url' => array('URL', 1),
            // 'note' => array('Note', 1),
            'updated_at' => array('Updated_at', 4),
            'manage' => array('Manage', 0),
        );

        $view = array(
            'num_rows' => count($entries),
            'data' => $entries,
            'param' => $parameters,
            'table_title' => $table_title,
        );

        //Pagination
        if ($pagination = getDataArray($results, 'data.pagination')) {
            $view['pagination'] = getPaginationsMake($pagination, $entries);
        }

        $script = $theme->scopeWithLayout('contacts.jscript_list', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('contacts.list', $view)->render();
    }

    public function getAdd()
    {
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Add Contacts');
        $theme->setDescription('Add Contacts description');
        $theme->share('user', $this->user);

        $view = array();

        $script = $theme->scopeWithLayout('contacts.jscript_add', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('contacts.add', $view)->render();
    }

    public function postAdd()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
            'commentable_type' => 'required',
            'commentable_id' => 'required',
            //'number'           => 'required',
            'ip' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('contacts/add')->with('error', $message);
        }

        // Parameters
        $parameters_allow = array(
            'name' => '',
            'email' => '',
            'message' => '',
            'message2' => '',
            'user_id' => '',
            'commentable_type' => '',
            'commentable_id' => '',
            //'number' => '1',
            'status' => '1',
            'ip' => '',
        );
        $parameters = array();
        foreach ($parameters_allow as $key => $val) {
            $parameters[$key] = array_get($data, $key, $val);
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('contacts', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not created');

            return Redirect::to('contacts/add')->with('error', $message);
        }

        $message = 'You successfully created';
        return Redirect::to('contacts')->with('success', $message);
    }

    public function getEdit($id)
    {
        $data = Input::all();
        $data['id'] = $id;

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Edit Contacts');
        $theme->setDescription('Edit Contacts description');
        $theme->share('user', $this->user);

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('contacts/' . $id);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not show contacts');

            return Redirect::to('contacts')->with('error', $message);
        }

        $contacts = array_get($results, 'data.record.0', array());

        $view = array(
            'data' => $contacts,
        );

        $script = $theme->scopeWithLayout('contacts.jscript_edit', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('contacts.edit', $view)->render();
    }

    public function postEdit()
    {
        $data = Input::all();

        $rules = array(
            'action' => 'required',
        );

        $referer = array_get($data, 'referer', 'contacts');
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

            $id = array_get($data, 'id', '');

            // Delete contacts
            $parameters = array(
                'id' => $id,
            );

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->delete('contacts/' . $id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not delete');

                return Redirect::to('contacts')->with('error', $message);
            }

            $message = 'You successfully delete';

            // Edit
        } else {
            // Validator request
            $rules = array(
                'id' => 'required',
                'name' => 'required',
                'email' => 'required',
                'message' => 'required',
                'url' => 'required',
                'ip' => 'required',
            );

            $id = array_get($data, 'id', 0);

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            // Parameters
            $parameters_allow = array(
                'name',
                'email',
                'mobile',
                'message',
                'user_id',
                // 'status',
                'ip',
                'url',
                'note',
            );

            $parameters = array();
            foreach ($parameters_allow as $val) {
                if ($val2 = array_get($data, $val, false)) {
                    $parameters[$val] = $val2;
                }
            }

            $parameters['status'] = $data['status']; // 0=Closed,1=New,2=Read,3=Active

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->put('contacts/' . $id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not edit');

                return Redirect::to($referer)->with('error', $message);
            }

            $message = 'You successfully edit';
        }

        return Redirect::to($referer)->with('success', $message);
    }

    public function ajaxUpdate()
    {
        $data = Input::all();
        $response = array();

        $rules = array(
            'action' => 'required',
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $referer = array_get($data, 'referer', 'contacts');
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response['message'] = $validator->messages()->first();

            return $client->createResponse($response, 1003);
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
                $response['message'] = $validator->messages()->first();

                return $client->createResponse($response, 1003);
            }

            $id = array_get($data, 'id', '');

            // Delete contacts
            $parameters = array(
                'id' => $id,
            );

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->delete('contacts/' . $id, $parameters);
            $results = json_decode($results, true);

            if ($status_code = array_get($results, 'status_code', false) != '0') {
                $response['message'] = array_get($results, 'status_txt', 'Can not delete');

                return $client->createResponse($response, $status_code);
            }

            $response['message'] = 'You successfully delete';

            // Edit
        } else {
            // Validator request
            $rules = array(
                'id' => 'required',
                // 'name'    => 'required',
                // 'email'   => 'required',
                // 'message' => 'required',
                // 'url'     => 'required',
                // 'ip'      => 'required',
            );

            $id = array_get($data, 'id', 0);

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();

                return $client->createResponse($response, 1003);
            }

            // Parameters
            $parameters_allow = array(
                'name',
                'email',
                'mobile',
                'message',
                'user_id',
                'ip',
                'url',
                'note',
            );

            foreach ($parameters_allow as $val) {
                if ($val2 = array_get($data, $val, false)) {
                    $parameters[$val] = $val2;
                }
            }

            if (isset($data['status'])) {
                $parameters['status'] = $data['status'];
            }

            if (!isset($parameters)) {
                $response['data'] = $data;
                return $client->createResponse($response, 1018);
            }

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->put('contacts/' . $id, $parameters);
            $results = json_decode($results, true);

            if ($status_code = array_get($results, 'status_code', false) != '0') {
                $response['message'] = array_get($results, 'status_txt', 'Can not edit');

                return $client->createResponse($response, $status_code);
            }

            $response['message'] = 'You successfully edit';
        }

        return $client->createResponse($response, 0);
    }
}

<?php

class NavigationsController extends BaseController
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
        $theme->setTitle('Admin ranbandokmaisod :: Navigations');
        $theme->setDescription('Navigations description');
        $theme->share('user', $this->user);

        $page    = array_get($data, 'page', '1');
        $perpage = array_get($data, 'perpage', '10');
        $order   = array_get($data, 'order', 'id');
        $sort    = array_get($data, 'sort', 'desc');

        $parameters = array(
            'user_id' => '1',
            'page'    => $page,
            'perpage' => $perpage,
            'order'   => $order,
            'sort'    => $sort,
        );

        if ($s = array_get($data, 's', false)) {
            $parameters['s'] = $s;
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('navigations', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('navigations')->with('error', $message);
            }
        }

        if (isset($_GET['sdebug'])) {
            alert($results);
            die();
        }

        $entries = array_get($results, 'data.record', array());

        $table_title = array(
            'id'           => array('ID ', 1),
            'position'     => array('Position', 1),
            'title'        => array('Title', 1),
            // 'subtitle'     => array('Subtitle', 1),
            // 'button'       => array('Button', 1),
            // 'button_title' => array('Button_title', 1),
            // 'button_url'   => array('Button_url', 1),
            'url'         => array('Url', 1),
            // 'images'       => array('Image', 0),
            'status'       => array('Status', 1),
            'manage'       => array('Manage', 0),
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

        $script = $theme->scopeWithLayout('navigations.jscript_list', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('navigations.list', $view)->render();
    }

    public function getAdd()
    {
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Add navigations');
        $theme->setDescription('Add navigations description');
        $theme->share('user', $this->user);

        $parameters = array(
            'user_id' => '1',
            'perpage' => '100',
            'order'   => 'id',
            'sort'    => 'desc'
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('navigations', $parameters);
        $results = json_decode($results, true);

        $id_max = array_get($results, 'data.record.0.id', '0');

        $view = array(
            'id_max' => $id_max
        );

        $script = $theme->scopeWithLayout('navigations.jscript_add', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('navigations.add', $view)->render();
    }

    public function postAdd()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'user_id'  => 'required',
            'title'    => 'required',
            'position' => 'required',
            'url'      => 'required',
            'status'   => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('navigations/add')->with('error', $message);
        }

        // Add banner
        $parameters = array(
            'user_id'  => array_get($data, 'user_id', ''),
            'title'    => array_get($data, 'title', ''),
            'position' => array_get($data, 'positon', '1'),
            'url'      => array_get($data, 'url', ''),
            'status'   => (array_get($data, 'status', 1) ? 'true' : 'false'),
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('navigations', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not created navigations');

            return Redirect::to('navigations/add')->with('error', $message);
        }

        $message = 'You successfully created';
        return Redirect::to('navigations')->with('success', $message);
    }

    public function getEdit($id)
    {
        $data = Input::all();
        $data['id'] = $id;

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Edit navigations');
        $theme->setDescription('Edit navigations description');
        $theme->share('user', $this->user);

        $parameters = array(
            'user_id' => '1'
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('navigations/'.$id, $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not created navigations');

            return Redirect::to('navigations')->with('error', $message);
        }

        $navigations = array_get($results, 'data.record', array());
        $id_max  = array_get($navigations, 'id', '0');

        $view = array(
            'id_max'  => $id_max,
            'navigations' => $navigations,
        );

        $script = $theme->scopeWithLayout('navigations.jscript_edit', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('navigations.edit', $view)->render();
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
                'id'        => 'required',
                // 'user_id' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $id = array_get($data, 'id', '');

            // Delete navigations
            $parameters = array(
                'id' => $id,
            );

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->delete('navigations/'.$id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not delete navigations');

                return Redirect::to('navigations')->with('error', $message);
            }

            $message = 'You successfully delete';

        // Order
        } else if ($action == 'order') {
            // Validator request
            $rules = array(
                'id_sel'    => 'required',
                'user_id' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to('navigations')->with('error', $message);
            }

            $user_id = array_get($data, 'user_id', 0);

            if ($id_sel = array_get($data, 'id_sel', false)) {
                $i = 1;
                foreach ($id_sel as $value) {
                    $id = $value;
                    $parameters2 = array(
                        'user_id' => $user_id,
                        'position'  => $i,
                    );

                    $client = new Client(Config::get('url.ranbandokmaisod-api'));
                    $results = $client->put('navigations/'.$id, $parameters2);
                    $results = json_decode($results, true);

                    $i++;
                }
            }

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not order navigations');

                return Redirect::to('navigations')->with('error', $message);
            }

            $message = 'You successfully order';

        // Edit
        } else {
            // Validator request
            $rules = array(
                'title'    => 'required',
                'position' => 'required',
                'url'      => 'required',
                // 'status'   => 'required',
            );

            $id = array_get($data, 'id', 0);

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to('navigations')->with('error', $message);
            }
       
            $parameters = array(
                'user_id'  => $data['user_id'],
                'title'    => (isset($data['title'])?$data['title']:''),
                'position' => (isset($data['position'])?$data['position']:'1'),
                'url'      => (isset($data['url'])?$data['url']:''),
                'status'   => (isset($data['status']) ? 'true' : 'false'),
            );

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->put('navigations/'.$id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not edit navigations');

                return Redirect::to('navigations')->with('error', $message);
            }

            $message = 'You successfully edit';
        }

        return Redirect::to('navigations')->with('success', $message);
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

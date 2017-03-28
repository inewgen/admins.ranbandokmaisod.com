<?php

class QuotesController extends BaseController
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
        $theme->setTitle('Admin ranbandokmaisod :: Quotes');
        $theme->setDescription('Quotes description');
        $theme->share('user', $this->user);

        $page = array_get($data, 'page', '1');
        $perpage = array_get($data, 'perpage', '10');
        $order = array_get($data, 'order', 'id');
        $sort = array_get($data, 'sort', 'desc');

        $parameters = array(
            'page' => $page,
            'perpage' => $perpage,
            'order' => $order,
            'sort' => $sort,
        );

        if ($s = array_get($data, 's', false)) {
            $parameters['s'] = $s;
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('quotes', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('quotes')->with('error', $message);
            }
        }

        $entries = array_get($results, 'data.record', array());

        $table_title = array(
            'id' => array('ID ', 1),
            'position' => array('Position', 1),
            'title' => array('Title', 1),
            'description' => array('Description', 1),
            // 'button'       => array('Button', 1),
            // 'button_title' => array('Button_title', 1),
            // 'button_url'   => array('Button_url', 1),
            'type' => array('Type', 1),
            'images' => array('Image', 0),
            'status' => array('Status', 1),
            'manage' => array('Manage', 0),
        );

        $view = array(
            'num_rows' => count($entries),
            'data' => $entries,
            'param' => $parameters,
            'table_title' => $table_title,
        );

        //Pagination
        if ($pagination = self::getDataArray($results, 'data.pagination')) {
            $view['pagination'] = self::getPaginationsMake($pagination, $entries);
        }

        $script = $theme->scopeWithLayout('quotes.jscript_list', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('quotes.list', $view)->render();
    }

    public function getAdd()
    {
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Add Quotes');
        $theme->setDescription('Add Quotes description');
        $theme->share('user', $this->user);

        $view = array();

        $script = $theme->scopeWithLayout('quotes.jscript_add', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('quotes.add', $view)->render();
    }

    public function postAdd()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'images' => 'required',
            'user_id' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('quotes/add')->with('error', $message);
        }

        // Parameters
        $parameters_allow = array(
            'title' => '',
            'description' => '',
            'author' => '',
            'user_id' => '1',
            'position' => '0',
            'images' => '0',
            'type' => '0',
            'status' => '1',
        );
        $parameters = array();
        foreach ($parameters_allow as $key => $val) {
            $parameters[$key] = array_get($data, $key, $val);
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('quotes', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not created quotes');

            return Redirect::to('quotes/add')->with('error', $message);
        }

        $message = 'You successfully created';
        return Redirect::to('quotes')->with('success', $message);
    }

    public function getEdit($id)
    {
        $data = Input::all();
        $data['id'] = $id;

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Edit Quotes');
        $theme->setDescription('Edit Quotes description');
        $theme->share('user', $this->user);

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('quotes/' . $id);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not show quotes');

            return Redirect::to('quotes')->with('error', $message);
        }

        $quotes = array_get($results, 'data.record.0', array());

        $view = array(
            'data' => $quotes,
        );

        $script = $theme->scopeWithLayout('quotes.jscript_edit', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('quotes.edit', $view)->render();
    }

    public function postEdit()
    {
        $data = Input::all();

        $rules = array(
            'action' => 'required',
        );

        $referer = array_get($data, 'referer', 'quotes');
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
                'images_id' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $id = array_get($data, 'id', '');
            $images_id = array_get($data, 'images_id', '');
            $user_id = array_get($data, 'user_id', '');

            $delete_file = true;
            if ($name = array_get($data, 'code', false)) {
                $path = '../res/public/uploads/' . $user_id; // upload path

                // Delete old image
                $delete_file = $this->images->deleteFileAll($path, $name);
            }

            if ($delete_file) {
                // Delete quotes
                $parameters = array(
                    'id' => $id,
                    'images_id' => $images_id,
                );

                $client = new Client(Config::get('url.ranbandokmaisod-api'));
                $results = $client->delete('quotes/' . $id, $parameters);
                $results = json_decode($results, true);

                if (array_get($results, 'status_code', false) != '0') {
                    $message = array_get($results, 'status_txt', 'Can not delete quotes');

                    return Redirect::to('quotes')->with('error', $message);
                }
            }

            $message = 'You successfully delete';

            // Order
        } else if ($action == 'order') {
            // Validator request
            $rules = array(
                'id_sel' => 'required',
                'user_id' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to('quotes')->with('error', $message);
            }

            $user_id = array_get($data, 'user_id', 0);

            if ($id_sel = array_get($data, 'id_sel', false)) {
                $i = 1;
                foreach ($id_sel as $value) {
                    $id = $value;
                    $parameters2 = array(
                        'user_id' => $user_id,
                        'position' => $i,
                    );

                    $client = new Client(Config::get('url.ranbandokmaisod-api'));
                    $results = $client->put('quotes/' . $id, $parameters2);
                    $results = json_decode($results, true);

                    $i++;
                }
            }

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not order quotes');

                return Redirect::to('quotes')->with('error', $message);
            }

            $message = 'You successfully order';

            // Edit
        } else {
            // Validator request
            $rules = array(
                'id' => 'required',
            );

            if (!isset($data['images_old'])) {
                $rules['images'] = 'required';
            }

            $id = array_get($data, 'id', 0);

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $delete_file = true;
            if (!empty(array_get($data, 'images', ''))) {
                if ($name = array_get($data, 'images_old.code', false)) {
                    $path = '../res/public/uploads/' . array_get($data, 'images_old.user_id', ''); // upload path

                    // Delete old image
                    $delete_file = $this->images->deleteFileAll($path, $name);
                }
            }

            // Parameters
            $parameters_allow = array(
                'title',
                'description',
                'author',
                'user_id',
                'position',
                'images',
                'images_old',
                'type',
            );

            $parameters = array();
            foreach ($parameters_allow as $val) {
                if ($val2 = array_get($data, $val, false)) {
                    $parameters[$val] = $val2;
                }
            }

            $parameters['status'] = array_get($data, 'status', '0');

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->put('quotes/' . $id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not edit quotes');

                return Redirect::to($referer)->with('error', $message);
            }

            $message = 'You successfully edit';
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

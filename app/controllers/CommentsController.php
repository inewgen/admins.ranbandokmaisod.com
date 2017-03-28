<?php

class CommentsController extends BaseController
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
        $theme->setTitle('Admin ranbandokmaisod :: Comments');
        $theme->setDescription('Comments description');
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
        $results = $client->get('comments', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('comments')->with('error', $message);
            }
        }

        $entries = array_get($results, 'data.record', array());

        $table_title = array(
            'id'           => array('ID ', 1),
            'name'     => array('Name', 1),
            'email'     => array('Email', 1),
            'message'         => array('Message', 1),
            'user_id'       => array('User_id', 1),
            'commentable_type'       => array('Commentable_type', 1),
            'commentable_id'       => array('Commentable_id', 1),
            'number'       => array('Number', 1),
            'status'       => array('Status', 1),
            'ip'       => array('IP', 1),
            'updated_at'       => array('Updated_at', 1),
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

        $script = $theme->scopeWithLayout('comments.jscript_list', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('comments.list', $view)->render();
    }

    public function getBlockwords()
    {
        $data = Input::all();

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Blockwords Comments');
        $theme->setDescription('Blockwords Comments description');
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

        // Filter
        $fild_arr = array(
            'id', 
            'name', 
            'email', 
            'message', 
            'user_id', 
            'commentable_type', 
            'commentable_id', 
            'number', 
            'status', 
            'blockwords_type', 
            'ip', 
            's'
        );
        
        foreach ($fild_arr as $value) {
            !empty($data[$value]) ? $parameters[$value] = array_get($data, $value, ''):'';
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('comments/blockwords', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('comments/blockwords')->with('error', $message);
            }
        }

        $entries = array_get($results, 'data.record', array());

        $table_title = array(
            'id'               => array('ID', 1),
            'name'             => array('Name', 1),
            // 'email'         => array('Email', 1),
            'message'          => array('Message', 1),
            'message2'         => array('Message2', 4),
            'blockwords_type'  => array('blockwords_type', 3),
            // 'user_id'       => array('User_id', 1),
            'commentable_type' => array('Commentable_type', 1),
            'commentable_id'   => array('Commentable_id', 1),   
            // 'number'        => array('Number', 1),
            'status'           => array('Status', 2),
            // 'ip'            => array('IP', 1),
            // 'updated_at'    => array('Updated_at', 1),
            'manage'           => array('Manage', 0),
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

        $script = $theme->scopeWithLayout('comments.jscript_blockwords', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('comments.blockwords', $view)->render();
    }

    public function getAdd()
    {
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Add Comments');
        $theme->setDescription('Add Comments description');
        $theme->share('user', $this->user);

        $view = array();

        $script = $theme->scopeWithLayout('comments.jscript_add', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('comments.add', $view)->render();
    }

    public function postAdd()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'name'             => 'required',
            'email'            => 'required',
            'message'          => 'required',
            'commentable_type' => 'required',
            'commentable_id'   => 'required',
            //'number'           => 'required',
            'ip'               => 'required'
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return Redirect::to('comments/add')->with('error', $message);
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
        $results = $client->post('comments', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not created');

            return Redirect::to('comments/add')->with('error', $message);
        }

        $message = 'You successfully created';
        return Redirect::to('comments')->with('success', $message);
    }

    public function getEdit($id)
    {
        $data = Input::all();
        $data['id'] = $id;

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Edit Comments');
        $theme->setDescription('Edit Comments description');
        $theme->share('user', $this->user);

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('comments/'.$id);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Can not show comments');

            return Redirect::to('comments')->with('error', $message);
        }

        $comments = array_get($results, 'data.record.0', array());

        $view = array(
            'data' => $comments,
        );

        $script = $theme->scopeWithLayout('comments.jscript_edit', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        return $theme->scopeWithLayout('comments.edit', $view)->render();
    }

    public function postEdit()
    {
        $data = Input::all();

        $rules = array(
            'action' => 'required',
        );

        $referer = array_get($data, 'referer', 'comments');
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
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $id = array_get($data, 'id', '');
  
            // Delete comments
            $parameters = array(
                'id'        => $id
            );

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->delete('comments/'.$id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not delete');

                return Redirect::to('comments')->with('error', $message);
            }

            $message = 'You successfully delete';

        // Blockwords
        } else if ($action == 'blockwords') {
            // Validator request
            $rules = array(
                'action'    => 'required',
                'referer' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                $message = $validator->messages()->first();

                return Redirect::to($referer)->with('error', $message);
            }

            $action = array_get($data, 'action', '');
            $referer = array_get($data, 'referer', '');
            $referer2 = substr($referer, 1);
            $referer2 = str_replace('blockwords', 'genblockwords', $referer2);
            $referer2 = str_replace('genblockwords_type', 'blockwords_type', $referer2);

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->get($referer2);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not blockwords comments');

                return Redirect::to($referer)->with('error', $message);
            }

            $message = 'You successfully blockwords ('.array_get($results, 'total', 0).')';

        // Edit
        } else {
            // Validator request
            $rules = array(
				'id'     => 'required',
                'name'             => 'required',
                'email'            => 'required',
                'message'          => 'required',
                'commentable_type' => 'required',
                'commentable_id'   => 'required',
                //'number'           => 'required',
                'ip'               => 'required'
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
                'message',
                'message2',
                'user_id',
                'commentable_type',
                'commentable_id',
                //'number',
                'status',
                'ip',
			);

			$parameters = array();
			foreach ($parameters_allow as $val) {
				if ($val2 = array_get($data, $val, false)) {
					$parameters[$val] = $val2;
				}
			}

            $parameters['status'] = $data['status'];

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->put('comments/'.$id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                $message = array_get($results, 'status_txt', 'Can not edit');

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

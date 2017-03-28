<?php
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;

class PagesController extends BaseController
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getIndex()
    {
        $data = Input::all();

        // Get cache value
        $key_cache = 'admin.0.pages.getindex.0.'.md5(serialize($data));
        if ($render = getCache($key_cache)) {
            return $render;
        }

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Pages');
        $theme->setDescription('Pages description');
        $theme->share('user', $this->user);

        $page    = array_get($data, 'page', '1');
        $perpage = array_get($data, 'perpage', '10');
        $order   = array_get($data, 'order', 'id');
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
        $results = $client->get('pages', $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', 'Data not found');

            if ($status_code != '1004') {
                return Redirect::to('pages')->with('error', $message);
            }
        }

        if (isset($_GET['sdebug'])) {
            alert($results);
            die();
        }

        $results2 = array_get($results, 'data.record', array());
        
        // Loop data
        $entries = array();
        foreach ($results2 as $key => $value) {
            $entry = array();
            foreach ($value as $key2 => $value2) {
                if (($key2 == 'images') && isset($value2) && is_array($value2)) {
                    $entr = array();
                    foreach ($value2 as $key3 => $value3) {
                        $ent = array();
                        foreach ($value3 as $key4 => $value4) {
                            if ($key4 == 'url') {
                                $w = 200;
                                $width = array_get($value3, 'width', 200);
                                $height = array_get($value3, 'height', 200);
                                $user_id = array_get($value3, 'user_id', '');
                                $image_code = array_get($value3, 'code', '');
                                $extension = array_get($value3, 'extension', '');
                                $name = array_get($value3, 'name', '');

                                $h = 200;
                                if ($width != 0) {
                                    $h = (int) ceil($w * $height / $width);
                                }
                            
                                $ent[$key4] = getImageLink('image', $user_id, $image_code, $extension, $w, $h, $name);
                                $ent['url_real'] = getImageLink('image', $user_id, $image_code, $extension, $width, $height, $name);
                            } else {
                                $ent[$key4] = $value4;
                            }
                        }
                        
                        $entr[$key3] = $ent;
                    }

                    $entry[$key2] = $entr;
                } else {
                    $entry[$key2] = $value2;
                }
            }

            $entries[] = $entry;
        }

        $table_title = array(
            'id'         => array('ID ', 1),
            'title'      => array('Title', 1),
            'images'     => array('Image', 0),
            'reference'  => array('Reference', 1),
            // 'category'   => array('Category', 1),
            'type'       => array('Type', 1),
            'created_at' => array('Created', 1),
            'updated_at' => array('Updated', 1),
            'manage'     => array('Manage', 0),
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

        $script = $theme->scopeWithLayout('pages.jscript_list', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        $render = $theme->scopeWithLayout('pages.list', $view)->render();

        // Save cache value
        if (!Session::has('success') && !Session::has('error') && !Session::has('warning')) {
            $contents = sanitize_output($render->original);
            saveCache($key_cache, $contents);
        }

        return $render;
    }

    public function getAdd()
    {
        $data = Input::all();

        // Get cache value
        $key_cache = 'admin.0.pages.getadd.0.'.md5(serialize($data));
        if ($render = getCache($key_cache)) {
            return $render;
        }

        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Add Pages');
        $theme->setDescription('Add Pages description');
        $theme->share('user', $this->user);

        $parameters = array(
            'user_id' => '1',
            'type'    => '3' //1=banners,2=news,3=pages
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('categories', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            // Clear cache value
            clearCache('admin.0.pages.getindex');
            $message = array_get($results, 'status_txt', false);

            return Redirect::to('pages')->with('error', $message);
        }

        $categories = array_get($results, 'data.record', array());

        if (isset($_GET['sdebug'])) {
            alert($results);
            die();
        }

        $user_id = '1';
        $cate = 'pages';
        $cate_id = '2';
        $datetime = date("YmdHis");
        $random = rand(0, 9);

        $ids = $user_id . $cate_id . $datetime . $random;

        $view = array(
            'user_id' => '1',
            'perpage' => '100',
            'order' => 'id',
            'sort' => 'desc',
            'cate' => $cate,
            'cate_id' => $cate_id,
            'user_id' => $user_id,
            'ids' => $ids,
            'categories' => $categories,
        );

        $script = $theme->scopeWithLayout('pages.jscript_add', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        $render = $theme->scopeWithLayout('pages.add', $view)->render();

        // Save cache value
        if (!Session::has('success') && !Session::has('error') && !Session::has('warning')) {
            $contents = sanitize_output($render->original);
            saveCache($key_cache, $contents);
        }

        return $render;
    }

    public function postAdd()
    {
        $data = Input::all();

        // Validator request
        $rules = array(
            'title'           => 'required',
            'sub_description' => 'required',
            'description'     => 'required',
            'images'          => 'required',
            'position'        => 'required',
            'status'          => 'required',
            'type'            => 'required',
            'user_id'         => 'required',
            'category_id'     => 'required',
            'tags'            => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            // Clear cache value
            clearCache('admin.0.pages.getadd');
            $message = $validator->messages()->first();

            return Redirect::to('pages/add')->with('error', $message);
        }

        $user_id  = array_get($data, 'user_id', 0);

        // Add pages
        $parameters = array(
            'title'           => (isset($data['title']) ? $data['title'] : ''),
            'sub_description' => (isset($data['sub_description']) ? $data['sub_description'] : ''),
            'description'     => (isset($data['description']) ? $data['description'] : ''),
            'images'          => (isset($data['images']) ? $data['images'] : ''),
            'position'        => (isset($data['position']) ? $data['position'] : '0'),
            'status'          => (isset($data['status']) ? $data['status'] : '1'),
            'type'            => (isset($data['type']) ? $data['type'] : '1'),
            'user_id'         => (isset($data['user_id']) ? $data['user_id'] : '0'),
            'reference'       => (isset($data['reference']) ? $data['reference'] : ''),
            'reference_url'   => (isset($data['reference_url']) ? $data['reference_url'] : '0'),
            'tags'            => (isset($data['tags']) ? $data['tags'] : ''),
            'category_id'     => (isset($data['category_id']) ? $data['category_id'] : '0'),
        );

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->post('pages', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            // Clear cache value
            clearCache('admin.0.pages.getadd');
            $message = array_get($results, 'status_txt', 'Can not created pages');

            return Redirect::to('pages/add')->with('error', $message);
        }

        // Clear cache value
        clearCache('admin.0.pages.getindex');
        clearCache('admin.0.pages.getadd');

        $message = 'You successfully created';
        return Redirect::to('pages')->with('success', $message);
    }

    public function getEdit($id)
    {
        $data = Input::all();
        $data['id'] = $id;

        // Get cache value
        $key_cache = 'admin.0.pages.getedit.'.$id.'.'.md5(serialize($data));
        if ($render = getCache($key_cache)) {
            return $render;
        }

        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $theme = Theme::uses('default')->layout('adminlte2');
        $theme->setTitle('Admin ranbandokmaisod :: Edit Pages');
        $theme->setDescription('Edit Pages description');
        $theme->share('user', $this->user);

        $results = $client->get('pages/' . $id);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            // Clear cache value
            clearCache('admin.0.pages.getindex');

            $message = array_get($results, 'status_txt', 'Can not get pages');

            return Redirect::to('pages')->with('error', $message);
        }

        $pages = array_get($results, 'data.record.0', array());

        // Loop data
        $entries = array();
        foreach ($pages as $key => $value) {
            if ($key == 'images') {
                $entry = array();
                foreach ($value as $key2 => $value2) {
                    $entry2 = array();
                    foreach ($value2 as $key3 => $value3) {
                        if ($key3 == 'url') {
                            $w = 200;
                            $width = array_get($value2, 'width', 200);
                            $height = array_get($value2, 'height', 200);
                            $user_id = array_get($value2, 'user_id', '');
                            $image_code = array_get($value2, 'code', '');
                            $extension = array_get($value2, 'extension', '');
                            $name = array_get($value2, 'name', '');

                            $h = 200;
                            if ($width != 0) {
                                $h = (int) ceil($w * $height / $width);
                            }
                        
                            $entry2[$key3] = getImageLink('image', $user_id, $image_code, $extension, $w, $h, $name);
                            $entry2['url_real'] = getImageLink('image', $user_id, $image_code, $extension, $width, $height, $name);
                            //$entry2[$key3] = $value3;
                        } else {
                            $entry2[$key3] = $value3;
                        }
                    }
                    $entry[$key2] = $entry2;
                }
                $entries[$key] = $entry;

            } else if ($key == 'tags') {
                $entry = array();
                $tages = array();
                foreach ($value as $key2 => $value2) {
                    foreach ($value2 as $key3 => $value3) {
                        if ($key3 == 'title') {
                            $tages[] = $value3;
                        }
                    }
                }
                $entries[$key] = implode(',', $tages);
            } else {
                $entries[$key] = $value;
            }
        }

        $parameters = array(
            'user_id' => '1',
            'type'    => '3' //1=banners,2=news,3=pages
        );

        $results = $client->get('categories', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $message = array_get($results, 'status_txt', false);

            return Redirect::to('pages')->with('error', $message);
        }

        $categories = array_get($results, 'data.record', array());

        $ids = array_get($pages, 'images.0.ids', 0);
        if ($ids == 0) {
            $user_id = '1';
            $cate = 'pages';
            $cate_id = '2';
            $datetime = date("YmdHis");
            $random = rand(0, 9);
            $ids = $user_id . $cate_id . $datetime . $random;
        }

        $num_image = count(array_get($pages, 'images', array()));

        $view = array(
            'pages'       => $entries,
            'categories' => $categories,
            'cate'       => 'pages',
            'cate_id'    => '2',
            'id'         => $id,
            'ids'        => $ids,
            'user_id'    => $user_id,
            'num_image'  => $num_image,
        );

        $script = $theme->scopeWithLayout('pages.jscript_edit', $view)->content();
        $theme->asset()->container('inline_script')->usePath()->writeContent('custom-inline-script', $script);

        $render = $theme->scopeWithLayout('pages.edit', $view)->render();

        // Save cache value
        if (!Session::has('success') && !Session::has('error') && !Session::has('warning')) {
            $contents = sanitize_output($render->original);
            saveCache($key_cache, $contents);
        }

        return $render;
    }

    public function postEdit()
    {
        $data = Input::all();
        $client = new Client(Config::get('url.ranbandokmaisod-api'));

        $rules = array(
            'action' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            // Clear cache value
            clearCache('admin.0.pages.getindex');
            $message = $validator->messages()->first();

            return Redirect::to('pages')->with('error', $message);
        }

        $action = array_get($data, 'action', null);

        // Delete
        if ($action == 'delete') {
            // Validator request
            $rules = array(
                'id'         => 'required',
                // 'images'     => 'required',
                'user_id'  => 'required',
                // 'images_old' => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                // Clear cache value
                clearCache('admin.0.pages.getindex');
                $message = $validator->messages()->first();

                return Redirect::to('pages')->with('error', $message);
            }

            $id         = array_get($data, 'id', 0);
            $images     = array_get($data, 'images', '');
            $user_id  = array_get($data, 'user_id', 0);

            // Delete pages
            $parameters = array(
                'id'        => $id,
                // 'images'    => $images,
                'user_id' => $user_id,
            );

            $results = $client->delete('pages/' . $id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                // Clear cache value
                clearCache('admin.0.pages.getindex');
                $message = array_get($results, 'status_txt', 'Can not delete pages');

                return Redirect::to('pages')->with('error', $message);
            }

            // Clear cache value
            clearCache('admin.0.pages.getindex');
            $message = 'You successfully delete';

        // Edit
        } else {
            // Validator request
            $rules = array(
                'id'              => 'required',
                'ids'             => 'required|min:1',
                'title'           => 'required',
                'sub_description' => 'required',
                'description'     => 'required',
                'images'          => 'required',
                'position'        => 'required',
                'status'          => 'required',
                'type'            => 'required',
                'user_id'       => 'required',
                'category_id'     => 'required',
                'tags'            => 'required',
                // 'views'        => 'required',
                // 'likes'        => 'required',
                // 'share'        => 'required',
            );

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                // Clear cache value
                clearCache('admin.0.pages.getindex');
                $message = $validator->messages()->first();

                return Redirect::to('pages/'.array_get($data, 'id', ''))->with('error', $message);
            }

            $user_id  = array_get($data, 'user_id', 0);

            // Edit pages
            $parameters = array();
            isset($data['title']) ? $parameters['title'] = $data['title']: '';
            isset($data['sub_description']) ? $parameters['sub_description'] = $data['sub_description']: '';
            isset($data['description']) ? $parameters['description'] = $data['description']: '';
            isset($data['images']) ? $parameters['images'] = $data['images']: '';
            isset($data['position']) ? $parameters['position'] = $data['position']: '';
            isset($data['status']) ? $parameters['status'] = $data['status']: '';
            isset($data['type']) ? $parameters['type'] = $data['type']: '';
            isset($data['user_id']) ? $parameters['user_id'] = $data['user_id']: '';
            isset($data['reference']) ? $parameters['reference'] = $data['reference']: '';
            isset($data['reference_url']) ? $parameters['reference_url'] = $data['reference_url']: '';
            isset($data['category_id']) ? $parameters['category_id'] = $data['category_id']: '';
            isset($data['tags']) ? $parameters['tags'] = $data['tags']: '';
            // isset($data['views']) ? $parameters['views'] = $data['views']: '';
            // isset($data['likes']) ? $parameters['likes'] = $data['likes']: '';
            // isset($data['share']) ? $parameters['share'] = $data['share']: '';
            $id = array_get($data, 'id', 0);

            $client = new Client(Config::get('url.ranbandokmaisod-api'));
            $results = $client->put('pages/'.$id, $parameters);
            $results = json_decode($results, true);

            if (array_get($results, 'status_code', false) != '0') {
                // Clear cache value
                clearCache('admin.0.pages.getindex');
                $message = array_get($results, 'status_txt', 'Can not updated pages');

                return Redirect::to('pages')->with('error', $message);
            }

            // Clear cache value
            clearCache('admin.0.pages.getindex');
            clearCache('admin.0.pages.getedit.'.$id);

            $message = 'You successfully updated';
            return Redirect::to('pages/' . $id)->with('success', $message);
        }

        // Clear cache value
        clearCache('admin.0.pages.getindex');
        return Redirect::to('pages')->with('success', $message);
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

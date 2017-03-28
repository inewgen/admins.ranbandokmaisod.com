<?php

class ImagesController extends BaseController
{
    private $scode;
    private $images;

    public function __construct(Scode $scode, Images $images)
    {
        $this->scode = $scode;
        $this->images = $images;
    }

    public function uploads()
    {
        $data = Input::all();
        $client = new Client(Config::get('url.ranbandokmaisod-api'));

        // Validator request
        $rules = array(
            'user_id' => 'required',
            'token' => 'required',
            'timestamp' => 'required',
            'Filedata' => 'required|mimes:gif,jpg,jpeg,png|image|image_size:<=1500,<=800|max:3000',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = array(
                'message' => $validator->messages()->first(),
            );

            return $client->createResponse($response, 2004);
        }

        $response = array();

        // Define a destination
        $user_id = isset($data['user_id']) ? $data['user_id'] : '';
        $verify_token = md5(Config::get('web.ranbandokmaisod-keys') . $data['timestamp']);

        if ($data['token'] != $verify_token) {
            return $client->createResponse($response, 2003);
        }

        if (Input::hasFile('Filedata')) {
            $images = $data['Filedata'];
            $image_code = $this->scode->imageCode();
            $target_folder = '../res/public/uploads/' . $user_id; // Relative to the root
            $target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_folder;

            if (!File::exists($target_path)) {
                $folder_create = File::makeDirectory($target_path, 0775, true);
            }

            $name = $images->getClientOriginalName();
            $extension = $images->getClientOriginalExtension();
            $extension = strtolower($extension);
            $size = $images->getSize();
            $file_name = $image_code . '.' . $extension; // renameing image
            list($width, $height, $type, $attr) = getimagesize($images);

            $uploadSuccess = Input::file('Filedata')->move($target_path, $file_name);

            if ($uploadSuccess) {
                // Add images
                $parameters = array(
                    'code' => $image_code,
                    'name' => $name,
                    'extension' => $extension,
                    'url' => '',
                    'type' => '0',
                    'size' => $size,
                    'width' => $width,
                    'height' => $height,
                    'position' => '0',
                    'status' => '1',
                    'user_id' => $user_id,
                );

                $results = $client->post('images', $parameters);
                $results = json_decode($results, true);

                if (array_get($results, 'status_code', false) != '0') {
                    $response = array(
                        'message' => 'Errors insert image.',
                    );
                    return $client->createResponse($response, 2001);
                } else {
                    $w = array_get($data, 'w', 200);
                    $h = array_get($data, 'h', 143);
                    // $h = (int) ceil($w * $height / $width);

                    if ($section = array_get($data, 'section', false)) {
                        if ($section == 'banners') {
                            $w = 1440;
                            $h = 500;
                        }
                    }

                    $url_img = getImageLink('image', $user_id, $image_code, $extension, $w, $h, $name);
                    $url_img_real = getImageLink('image', $user_id, $image_code, $extension, $width, $height, $name);
                    $id = array_get($results, 'id', '');

                    $jsons_return = array(
                        'id' => $id,
                        'code' => $image_code,
                        'url' => $url_img,
                        'url_real' => $url_img_real,
                        'extension' => $extension,
                        'user_id' => $user_id,
                    );

                    $response = array(
                        'data' => $jsons_return,
                    );
                    return $client->createResponse($response, 0);
                }

            } else {
                $response = array(
                    'message' => 'Can not upload file',
                );
                return $client->createResponse($response, 2001);
            }
        }

        return $client->createResponse($response, 2002);
    }

    public function getDeleteImage()
    {
        $data = Input::all();
        $client = new Client(Config::get('url.ranbandokmaisod-api'));

        $response = array(
            'data' => $data,
        );

        // Validator request
        $rules = array(
            'id' => 'required',
            'code' => 'required',
            'user_id' => 'required',
            'extension' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = array(
                'message' => $validator->messages()->first(),
            );

            return $client->createResponse($response, 1003);
        }

        $id = array_get($data, 'id', 0);
        $user_id = array_get($data, 'user_id', 0);

        $parameters = array(
            'user_id' => $user_id,
        );

        $results = $client->delete('images/' . $id, $parameters);
        $results = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            $status_txt = array_get($results, 'status_txt', '');
            return $client->createResponse($status_txt, $status_code);
        }

        $code = array_get($data, 'code', 0);
        $extension = strtolower(array_get($data, 'extension', 0));
        $path = '../res/public/uploads/' . $user_id; // upload path
        $file_path = $path . '/' . $code . '.' . $extension;

        if (!file_exists($file_path)) {
            //return $client->createResponse($data, 2005);
        }

        // Delete old images
        $code_old = $code;
        $ext = strtolower($extension);
        $source_folder = '../res/public/uploads/' . $user_id;

        $filelist = array();
        if ($handle = opendir($source_folder)) {
            while ($entry = readdir($handle)) {
                if (strpos($entry, $code_old) === 0) {
                    $image_path = '../res/public/uploads/' . $user_id . '/' . $entry;
                    $image_delete = $this->images->deleteFile($image_path);
                    $filelist[] = $entry;

                    if (!$image_delete) {
                        $message = array_get($results, 'data.message', 'Delete old image user error.');
                        return $client->createResponse($response, 2005);
                    }
                }
            }
            closedir($handle);
        }

        return $client->createResponse($response, 0);
    }

    public function getClearImage()
    {
        $data = Input::all();
        $client = new Client(Config::get('url.ranbandokmaisod-api'));

        // Validator request
        $rules = array(
            'user_id' => 'required|integer|min:1',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = array(
                'message' => $validator->messages()->first(),
            );

            return $client->createResponse($response, 1003);
        }

        $user_id = array_get($data, 'user_id', '');
        $source_folder = '../res/public/uploads/' . $user_id;

        if (!file_exists($source_folder)) {
            return $client->createResponse(null, 2005);
        }

        $filelist = array();
        $not_allow = array(
            '.', '..', '.DS_Store',
        );

        // Get file in folder
        $entries = array();
        if ($handle = opendir($source_folder)) {
            while ($entry = readdir($handle)) {
                if (!in_array($entry, $not_allow)) {
                    $entry = explode('.', $entry);
                    $entry = $entry[0];
                    $entry = explode('_', $entry);
                    $entry = $entry[0];
                    $entries[$entry] = $entry;
                }
            }
            closedir($handle);
        }

        $filelist = array();
        $entry = array();
        foreach ($entries as $key => $value) {
            $filelist[] = $key;
        }

        $code = implode(',', $filelist);

        if (empty($code)) {
            return $client->createResponse(null, 2005);
        }

        $parameters = array(
            'code' => $code,
        );

        $results = $client->get('images/clear', $parameters);
        $results = json_decode($results, true);

        if (array_get($results, 'status_code', false) != '0') {
            $status_code = array_get($results, 'status_code', '');
            $status_txt = array_get($results, 'status_txt', '');
            return $client->createResponse($status_txt, $status_code);
        }

        $images = array_get($results, 'data.record', array());
        $filelist = array();
        foreach ($images as $key => $value) {
            if ($value == '0') {
                // Delete old images
                $code_old = $key;
                $source_folder = '../res/public/uploads/' . $user_id;

                if ($handle = opendir($source_folder)) {
                    while ($entry = readdir($handle)) {
                        if (strpos($entry, $code_old) === 0) {
                            $image_path = '../res/public/uploads/' . $user_id . '/' . $entry;
                            $image_delete = $this->images->deleteFile($image_path);
                            $filelist[] = $entry;

                            if (!$image_delete) {
                                $message = array_get($results, 'data.message', 'Delete old image user error.');
                                return $client->createResponse($response, 2005);
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }

        $response = array(
            'total_deleted' => count($filelist),
            'image_deleted' => $filelist
        );

        return $client->createResponse($response, 0);
    }
}

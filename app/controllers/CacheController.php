<?php

class CacheController extends BaseController
{
    public function clearCache()
    {
        $data = Input::all();
        $response = array();
        $client = new Client(Config::get('url.ranbandokmaisod-api'));

        // Validator request
        $rules = array(
            'key' => 'required|min:3',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = array(
                'message' => $validator->messages()->first(),
            );

            return $client->createResponse($response, 1003);
        }

        if ($key_cache = array_get($data, 'key', false)) {
            $get_keys_all = CachedSettings::getKeys();
            $i = 0;

            foreach ($get_keys_all as $key => $value) {
                if (strpos($value, $key_cache) === 0) {
                    $keys[] = $value;
                    if (!CachedSettings::has($value)) {
                        //return $client->createResponse($response, 3002);
                    }

                    $clear_cache = clearCache($value);
                    $i++;
                }
            }

            if ($i == 0) {
                return $client->createResponse($response, 3002);
            }

            $response = array(
                'message' => 'Success clear cache'
            );
            return $client->createResponse($response, 0);
        }

        return $client->createResponse($response, 3001);
    }
}

<?php

class TagsController extends BaseController
{
    public function checkTags()
    {
        $data = Input::all();
        $client = new Client(Config::get('url.ranbandokmaisod-api'));

        // Validator request
        $rules = array(
            'term' => 'required',
        );

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $message = $validator->messages()->first();

            return json_encode(false);
        }

        $search = array_get($data, 'term', 0);
  
        $parameters = array(
            's' => urlencode($search),
        );
        $client = new Client(Config::get('url.ranbandokmaisod-api'));
        $results    = $client->get('tags', $parameters);
        $results    = json_decode($results, true);

        if ($status_code = array_get($results, 'status_code', false) != '0') {
            return json_encode(false);
        }

        $results = array_get($results, 'data.record', array());

        $entry = array();
        foreach ($results as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if ($key2 == 'title') {
                    $entry[] = $value2;
                }
            }
        }

        return json_encode($entry);
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

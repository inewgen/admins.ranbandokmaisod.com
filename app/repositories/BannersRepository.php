<?php

class BannersRepository implements BannersRepositoryInterface
{
    public function lists($parameters)
    {
        // required
        if (!isset($parameters['member_id'])) {
            return false;
        }

        $client   = new Client(Config::get('url.ranbandokmaisod-api'));
        $results = $client->get('banners', $parameters);

        // error request
        if (!isset($results)||!is_object($results)) {
            return false;
        }

        $results = json_decode($results, true);

        // error status code
        if (!isset($results['status_code'])) {
            return false;
        }

        return $results;
    }

}

<?php

use App\Repositories\CachedRepository;

class PagesRepository implements PagesRepositoryInterface
{

    public function __construct(CachedRepository $cachedRepository)
    {
        $this->cachedRepository = $cachedRepository;
        $this->pathcache        = 'web.0.pages';
    }

    public function get($parameters)
    {
        $keycache = getKeyCache($this->pathcache . '.get', $parameters);

        // Get cache
        $response = $this->cachedRepository->get($keycache);
        if ($response) {
            return $response;
        }

        if (isset($_GET['nocache'])) {
            $parameters['nocache'] = $_GET['nocache'];
        }

        $client   = new Client(Config::get('url.ranbandokmaisod-api'));
        $results  = $client->get('pages', $parameters);
        $response = json_decode($results, true);

        // Save cache
        $this->cachedRepository->put($keycache, $response);

        return $response;
    }
}

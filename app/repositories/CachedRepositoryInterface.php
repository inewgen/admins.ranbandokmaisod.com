<?php
namespace App\Repositories;

interface CachedRepositoryInterface
{
    public function get($keycache);

    public function put($keycache, $response);

    public function clear($keycache);
}

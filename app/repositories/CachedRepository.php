<?php
namespace App\Repositories;

use CachedSettings;

class CachedRepository implements CachedRepositoryInterface
{
    public function get($keycache)
    {
        if (isset($_GET['nocache'])) {
            return false;
        }
        
        $value = false;
        if ($value = CachedSettings::get($keycache, false)) {
            $value = unserialize($value);

            if ($value) {
                $value['cached'] = true;
            }
        }

        return $value;
    }

    public function put($keycache, $response)
    {
        if (empty($keycache) || empty($response)) {
            return false;
        }

        return CachedSettings::set($keycache, serialize($response));
    }

    public function clear($keycache)
    {
        if (!empty($keycache)) {
            $get_keys_all = CachedSettings::getKeys();
            $i = 0;

            foreach ($get_keys_all as $key => $value) {
                if (strpos($value, $keycache) === 0) {
                    $keys[] = $value;
                    if (!CachedSettings::has($value)) {
                        //return false;
                    }

                    CachedSettings::delete($value);
                    Cache::forget($value);
                    $i++;
                }
            }

            if ($i == 0) {
                return false;
            }

            return true;
        }

        return false;
    }
}

<?php

/**
 * Print the given value and kill the script.
 *
 * @param  mixed  $value
 * @return void
 */
if (!function_exists('getPaginationsMake')) {
    function getPaginationsMake($pagination, $record)
    {
        $total = array_get($pagination, 'total', 0);
        $limit = array_get($pagination, 'perpage', 0);
        $paginations = Paginator::make($record, $total, $limit);
        return isset($paginations) ? $paginations : '';
    }
}

if (!function_exists('getDataArray')) {
    function getDataArray($data, $key)
    {
        return array_get($data, $key, false);
    }
}

if (!function_exists('checkAlertMessage')) {
	function checkAlertMessage() { ?>
        <!-- Message Success -->
        <?php if($success = Session::has('success')): ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">*</button>
            <i class="icon fa fa-check"></i>
            <?php echo (null !== Session::get('success')) ? Session::get('success') : '';?>       
        </div>
        <?php endif; ?>

        <!-- Message Error -->
        <?php if($error = Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">*</button>
            <i class="icon fa fa-ban"></i>
            <?php echo (null !== Session::get('error')) ? Session::get('error') : '';?>
        </div>
        <?php endif; ?>

        <!-- Message Warning -->
        <?php if($error = Session::has('warning')): ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">*</button>
            <i class="icon fa fa-warning"></i>
            <?php echo (null !== Session::get('warning')) ? Session::get('warning') : '';?>
        </div>
        <?php endif; ?>
<?php }
} 

if (!function_exists('checkAlertMessageFlash')) {
    function checkAlertMessageFlash() { ?>
        <!-- Message Success -->
        <?php if($success = Session::has('success')): ?>
        toastr["success"]('<?php echo (null !== Session::get('success')) ? Session::get('success') : '';?>');
        <?php endif; ?>

        <!-- Message Error -->
        <?php if($error = Session::has('error')): ?>
        toastr["error"]('<?php echo (null !== Session::get('error')) ? Session::get('error') : '';?>');
        <?php endif; ?>

        <!-- Message Warning -->
        <?php if($error = Session::has('warning')): ?>
        toastr["warning"]('<?php echo (null !== Session::get('warning')) ? Session::get('warning') : '';?>');
        <?php endif; ?>
<?php }
}

if (!function_exists('getImageLink')) {
    // img|image, default|user_id, array(), 100, 100
    function getImageLink($type, $section, $code, $extension, $w, $h, $name = 'ranbandokmaisod.jpg')
    {
        if (empty($type) || empty($section) || empty($code) || empty($extension)) {
            return false;
        }

        $ranbandokmaisod_res = Config::get('url.ranbandokmaisod-res');

        if ($type == 'img') {
            return $ranbandokmaisod_res . '/img/' . $section . '/' . $code . '/' . $extension . '/' . $w . '/' . $h .'/'.$name;
        }
        $user_id = $section;

        return $ranbandokmaisod_res . '/image/' . $user_id . '/' . $code . '/' . $extension . '/' . $w . '/' . $h.'/'.$name;
    }
}

if (!function_exists('getImageProfile')) {
    function getImageProfile($user, $w, $h)
    {
        if (empty($user) || empty($w) || empty($h)) {
            return false;
        }

        $ranbandokmaisod_res = Config::get('url.ranbandokmaisod-res');
        $user_id = $user->id;
        $code = $user->images[0]->code;
        $extension = $user->images[0]->extension;
        $name = 'profile.jpg';

        return $ranbandokmaisod_res . '/image/' . $user_id . '/' . $code . '/' . $extension . '/' . $w . '/' . $h.'/'.$name;
    }
}

if (!function_exists('getLogo')) {
    function getLogo($w, $h)
    {
        if (empty($w) || empty($h)) {
            return false;
        }
        $ranbandokmaisod_res = Config::get('url.ranbandokmaisod-res');
        $name = 'logo.jpg';

        return $ranbandokmaisod_res . '/img/default/ranbandokmaisod_logo/png/' . $w . '/' . $h.'/'.$name;
    }
}

if (!function_exists('saveCache')) {
    function saveCache($key_cache, $value_cache)
    {
        $value_cache .= '<!-- Cached at ' . date('Y-m-d H:i:s') . $key_cache . ' -->';
        //$value_cache = serialize($value_cache);
        return CachedSettings::set($key_cache, $value_cache);
    }
}

if (!function_exists('getCache')) {
    function getCache($key_cache)
    {return false;
        if (Input::get('nocache')) {
            return false;
        }

        $value = false;
        if ($value = CachedSettings::get($key_cache, false)) {
            // $value = unserialize($value);
        }

        return $value;
    }
}

if (!function_exists('clearCache')) {
    function clearCache($key_cache)
    {
        if (!empty($key_cache)) {
            $get_keys_all = CachedSettings::getKeys();
            $i = 0;

            foreach ($get_keys_all as $key => $value) {
                if (strpos($value, $key_cache) === 0) {
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

if (!function_exists('sanitize_output')) {
    function sanitize_output($buffer)
    {
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1'
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }
}

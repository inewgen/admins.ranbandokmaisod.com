<?php

/**
 * Print the given value and kill the script.
 *
 * @param  mixed  $value
 * @return void
 */
if (!function_exists('alert')) {
    function alert($value, $die = false)
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
        if ($die) {
            die;
        }
    }
}

if (!function_exists('sdebug')) {
    function sdebug($value, $die = false)
    {
        if (isset($_GET['sdebug'])) {
            echo "<pre>";
            print_r($value);
            echo "</pre>";
            if ($die) {
                die;
            }

        }
    }
}

if (!function_exists('jsn')) {
    function jsn($value, $pre = false)
    {
        echo ($pre) ? "<pre>" : "";
        echo json_encode($value);
        echo ($pre) ? "</pre>" : "";
        echo "<br><br>";
        die;
    }
}

if (!function_exists('arr')) {

    function arr($value, $pre = false)
    {
        echo ($pre) ? "<pre>" : "";
        var_export($value);
        echo ($pre) ? "</pre>" : "";
        die;

    }
}

if (!function_exists('aq')) {
    function aq($value, $die = true)
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
        if ($die) {
            die;
        }

    }
}

/**
 * Dump the given value and kill the script.
 *
 * @param  mixed  $value
 * @return void
 */
if (!function_exists('dump')) {
    function dump($value, $die = false)
    {
        echo "<pre>";
        var_dump($value);
        echo "</pre>";
        if ($die) {
            die;
        }

    }
}

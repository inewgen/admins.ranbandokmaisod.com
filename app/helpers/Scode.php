<?php

class Scode
{
    public function getadomain($a=null)
    {return true;
        if (isset($_SERVER['HTTP_HOST'])) {
            preg_match('/[^.]+\.[^.]+$/', $_SERVER['HTTP_HOST'], $matches);

            return '.'.$matches[0];
        }

        return null;
    }

    public function encode($string, $key)
    {
        $key = sha1($key);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        $j = 0;
        $hash = '';
        for ($i = 0; $i < $strLen; $i++) {
            $ordStr = ord(substr($string, $i, 1));
            if ($j == $keyLen) {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j++;
            $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
        }
        return $hash;
    }

    public function decode($string, $key)
    {
        $key = sha1($key);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        $j = 0;
        $hash = '';
        for ($i = 0; $i < $strLen; $i += 2) {
            $ordStr = hexdec(base_convert(strrev(substr($string, $i, 2)), 36, 16));
            if ($j == $keyLen) {
                $j = 0;
            }
            $ordKey = ord(substr($key, $j, 1));
            $j++;
            $hash .= chr($ordStr - $ordKey);
        }
        return $hash;
    }

    public function pencode($string, $key)
    {
        return md5(base64_encode($string.$key));
    }

    public function imageCode()
    {
        $t        = microtime(true);
        $micro    = sprintf("%06d", ($t - floor($t)) * 1000000);
        $d        = new DateTime(date('Y-m-d H:i:s.'. $micro, $t));
        $datetime = $d->format("YmdHisu");
        $random   = rand(0, 9).rand(0, 9);
        $code      = $random . $datetime;

        return $code;
    }
}

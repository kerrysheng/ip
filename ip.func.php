<?php

function get_variable($key, $type = 'G', $default_value = '')
{
    $type = strtoupper($type);
    $count = strlen($type);
    $has_value = false;


    for ($i = 0, $j = $count; $i < $j; $i++) {

        $t = $type[$i];

        switch ($t) {
            case 'G':
            default:
                $var = &$_GET;
                break;
            case 'P':
                $var = &$_POST;
                break;
            case 'C':
                $var = &$_COOKIE;
                break;
            case 'R':
                $var = &$_REQUEST;
                break;
            case 'S':
                $var = &$_SESSION;
                break;
            case 'F':
                $var = &$_FILES;
                break;
        }

        if (isset($var[$key])) {
            $has_value = true;
            break;
        }
    }
    if (!$has_value) return $default_value;
    return $var[$key];
}

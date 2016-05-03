<?php

class Dns
{
    public static function get_address($domain, $server)
    {
        $sh_ret = shell_exec("dig {$domain} @{$server} +noadditional +noauthority +nostats +nocomment +nocmd");

        $sh_ret_arr = explode("\n", trim($sh_ret));

        var_dump($sh_ret_arr);
    }
}
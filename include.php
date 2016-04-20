<?php
/* Mar 24 ,2014
 * IP Geolocation Finder 5.0
 * core ip api for public
 * author:shengyueming@gmail.com
 *
 */

define('DS', DIRECTORY_SEPARATOR);
define('SROOT', dirname(__file__) . DS);

require_once(SROOT . 'config.php');
//require_once(SROOT . 'ip.class.php');
require_once(SROOT . 'ip.func.php');

spl_autoload_register(function ($class) {
    include_once strtolower($class) . '.class.php';
});


class Spent_Time
{
    private $start_time = 0;
    private $stop_time = 0;

    function start()
    {
        $this->start_time = microtime(true);
    }

    function stop()
    {
        $this->stop_time = microtime(true);
    }

    function spent($n = 4, $format = 'ms')
    {
        $spent_time = $this->stop_time - $this->start_time;
        if ($format == 'ms') {
            $spent_time *= 1000;
        }
        return number_format($spent_time, $n);
    }
}


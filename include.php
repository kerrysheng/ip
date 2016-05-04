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
require_once (SROOT.'vendor'.DS.'redis'.DS.'Autoloader.php');

spl_autoload_register(function ($class) {
    include_once strtolower($class) . '.class.php';
});

Predis\Autoloader::register();


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

class Ret_Code{
    const RET_OK=0;
    const RET_IP_ERROR=1;
    const RET_DB_ERROR=2;
    const RET_INTERNAL_ERROR=3;

    const ERR_MSG=array(
        self::RET_IP_ERROR=>'输入IP有误',
        self::RET_DB_ERROR=>'数据库查询有误'
    );
}


$redis=new Predis\Client();
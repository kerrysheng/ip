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

spl_autoload_register(function($class){
   include_once strtolower($class).'.class.php';
});
// class runtime {
//     var $StartTime = 0;
//     var $StopTime = 0;
//     function get_microtime() {
//         $mtime = array_sum(explode(" ", microtime()));
//         return (float)$mtime;
//     }
//     function start() {
//         $this->StartTime = $this->get_microtime();
//     }
//     function stop() {
//         $this->StopTime = $this->get_microtime();
//     }
//     function spent() {
//         $ttime = $this->StopTime - $this->StartTime;
//         return number_format($ttime, 4);
//         ;
//     }
// }
//try {
//    $db = new PDO("mysql:host={$_CONFIG['db']['server']};dbname={$_CONFIG['db']['database']}", $_CONFIG['db']['user'], $_CONFIG['db']['password']);
//} catch (PDOException $e) {
//    exit($e->getmessage());
//}

//$timerun = new runtime();

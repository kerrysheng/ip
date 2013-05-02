<?php

/*
* IP FINDER
* PROGRAMMED BY SWIMM
* DATE:2013/1/18
*/
error_reporting(E_ALL & ~ E_NOTICE);

define(DS, DIRECTORY_SEPARATOR);
define('SROOT', dirname(__file__) . DS);
define('AURL', 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')));
date_default_timezone_set ( 'asia/shanghai' );

require_once (SROOT . "template/Smarty.class.php");
require_once (SROOT . 'ip.class.php');
require_once (SROOT . 'ip.func.php');
require_once (SROOT . 'config.php');

class runtime {
    var $StartTime = 0;
    var $StopTime = 0;
    function get_microtime() {
        $mtime = array_sum(explode(" ", microtime()));
        return (float)$mtime;
    }
    function start() {
        $this->StartTime = $this->get_microtime();
    }
    function stop() {
        $this->StopTime = $this->get_microtime();
    }
    function spent() {
        $ttime = $this->StopTime - $this->StartTime;
        return number_format($ttime, 4);
        ;
    }
}

$db = new mysqli($_CONFIG['db']['server'], $_CONFIG['db']['user'], $_CONFIG['db']['password'], $_CONFIG['db']['database']);
$tpl = new smarty();
$timerun = new runtime();
<?php

require_once 'include.php';

$ip_addr = get_variable('ip', 'gp');
$ip_addr = empty($ip_addr) ? IPLoc::get_ip() : $ip_addr;
$ip_geo = new IPLoc($_CONFIG['db']);
$ip_geo_result = $ip_geo->get_location($ip_addr);
$format = get_variable('format', 'gp', 'json');
$cb = get_variable('callback', 'gp');

$api = array();
$api['code'] = 0;
$api['ip'] = $ip_addr;
$api = array_merge($api, $ip_geo_result->getAll());

switch ($format) {
    case 'json':
    default:
        if ($cb === '') {
            header('Content-Type:application/json');
            echo json_encode($api);
        } else {
            header('Content-Type:text/javascript');
            echo "if(window.{$cb}){" . $cb . "(" . json_encode($api) . ")};";
        }
        break;
    case 'text':
        header('Content-Type:text/plain');
        array_shift($api);
        echo implode("\t", $api);
}